<?php
$estilosVenta=1;
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$globalSucursal=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
 

$codigo_registro=$_POST["codigo_registro"];
$sqlCodigo="";
if(isset($_POST['codigo'])&&$_POST['codigo']!=""){
  $sqlCodigo="and d.codigo_material in (".$_POST['codigo'].")";
}

$sqlNombre="";
if(isset($_POST['nombre'])&&$_POST['nombre']!=""){
  $sqlNombre="and d.descripcion_material like '%".$_POST['nombre']."%'";
}

$stringLineasX="";
if(isset($_POST['lineas'])&&count($_POST['lineas'])>0){
  $stringLineasX="and d.cod_linea_proveedor in (".implode(",",$_POST['lineas']).") ";
}

$stringFormasX="";
if(isset($_POST['formas'])&&count($_POST['formas'])>0){
  $stringFormasX="and d.cod_forma_far in (".implode(",",$_POST['formas']).") ";
}

$stringAccionesX="";
if(isset($_POST['acciones'])&&count($_POST['acciones'])>0){
  $stringAccionesX="and d.codigo_material in (select codigo_material from material_accionterapeutica where cod_accionterapeutica in (".implode(",",$_POST['acciones'])."))";
}

$sqllimit="";
if($sqlCodigo==""&&$sqlNombre==""&&$stringLineasX==""&&$stringFormasX==""&&$stringAccionesX=="")
{
  $sqllimit="LIMIT 100";
}
$descDefault=0;
$consultDesc="SELECT abreviatura FROM tipos_precio where codigo=$codigo_registro;";
$rspdesc=mysqli_query($enlaceCon,$consultDesc);
while($datDesc=mysqli_fetch_array($rspdesc)){
  $descDefault = $datDesc["abreviatura"];
}

$sql="(SELECT s.cod_material,d.codigo_material,d.descripcion_material,(select cod_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor) as cod_proveedor,d.cod_linea_proveedor,s.porcentaje_material from tipos_precio_productos s join material_apoyo d on d.codigo_material=s.cod_material where s.cod_tipoprecio=$codigo_registro and d.estado=1 order by 1)
   UNION (select d.codigo_material,0 as codigo_material,d.descripcion_material,(select cod_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor) as cod_proveedor,d.cod_linea_proveedor, 0 as porcentaje_material from material_apoyo d where d.estado=1 and d.codigo_material not in (SELECT s.cod_material from tipos_precio_productos s join material_apoyo d on d.codigo_material=s.cod_material where s.cod_tipoprecio=$codigo_registro and d.estado=1) $sqlCodigo $stringLineasX $sqlNombre $stringFormasX $stringAccionesX order by 1 $sqllimit) order by 1";
 // echo $sql;

$resp=mysqli_query($enlaceCon,$sql); 


echo "<table class='table table-sm table-bordered' id='tabla_productos'>";
  echo "<tr class='bg-info text-white font-weight-bold'>
  <th width='10%'><div class='btn-group'><a href='#' class='btn btn-sm btn-warning' onClick='seleccionar_todo()'>T</a><a href='#' onClick='deseleccionar_todo()' class='btn btn-sm btn-default'>N</a></div></th>
  <th>Proveedor</th>
  <th>Producto</th>  
  <th>Oferta <br> Stock Limitado?</th>
  <th>Precio Actual</th>
  <th>% Desc</th>
  <th>Precio Final</th>
  </tr>";
  $index=0;
  while($dat=mysqli_fetch_array($resp))
  {
    $index++;    
    $producto=$dat[2];
    $porcentDesc=$descDefault;
    $proveedor=obtenerNombreProveedor($dat[3]);
    $linea=obtenerNombreProveedorLinea($dat[4]);
    $checked="";
    $estiloTexto="";
    $estado="";
    $estiloInput="#C6CCCC";  
    if($dat[1]!=0){
         $estiloTexto="text-success font-weight-bold";
         $checked="checked";
         $estado="REGISTRADO";         
         $porcentDesc=$dat['porcentaje_material'];
         $estiloInput="#FFF";         
    }

    $stockProductoX=stockProducto($enlaceCon,$globalAlmacen,$dat[0]);
    $txtStockProducto="";
    if($stockProductoX==0){
      $txtStockProducto="-";
    }else{
      $txtStockProducto="<span style='color:red'><b>$stockProductoX</b></span>";
    }

    $precio=number_format(precioProductoSucursalCalculado($enlaceCon,$dat[0],$globalSucursal),2,'.','');
    $precioFin=number_format($precio*((100-$porcentDesc)/100),2,'.','');
    $inpPorcent="<input style='width:60px; background:$estiloInput; border:none;border-bottom:1px solid #B2E6E2' id='descuento$dat[0]' type='number' name='descuento$dat[0]' value='$porcentDesc' step='any' onchange='calcularPrecioFinalDescuento($dat[0]); return false;' onkeyup='calcularPrecioFinalDescuento($dat[0]); return false;'> %";

    $inpPrecio="<input style='width:60px; background:$estiloInput; border:none;border-bottom:1px solid #B2E6E2' id='precio$dat[0]' type='number' name='precio$dat[0]' value='$precio' step='any' readonly>";

    $inpPrecioFinal="<input style='width:60px; background:$estiloInput; border:none;border-bottom:1px solid #B2E6E2' id='precio_fin$dat[0]' type='number' name='precio_fin$dat[0]' value='$precioFin' step='any' onchange='calcularDescuentoFinal($dat[0]); return false;' onkeyup='calcularDescuentoFinal($dat[0]); return false;'>";
    echo "<tr class='$estiloTexto'>
    <td><input type='checkbox' name='codigo[]' value='$dat[0]' $checked></td>
    <td><small>$proveedor ($linea)</small></td>
    <td>($dat[0]) $producto</td>
    <td align='center'>$txtStockProducto</td>
    <td>$inpPrecio</td>    
    <td width='10%'>$inpPorcent</td>
    <td>$inpPrecioFinal</td>
    </tr>";
  }
  echo "</table>";
