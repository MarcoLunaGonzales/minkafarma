<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$globalSucursal=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];

$descDefault=0;
$consultDesc="SELECT abreviatura FROM tipos_precio where codigo=$codigo_registro;";
$rspdesc=mysqli_query($enlaceCon,$consultDesc);
while($datDesc=mysqli_fetch_array($rspdesc)){
  $descDefault = $datDesc["abreviatura"];
}


$sql="(SELECT s.cod_material,d.codigo_material,d.descripcion_material,(select cod_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor) as cod_proveedor,d.cod_linea_proveedor,porcentaje_material from tipos_precio_productos s join material_apoyo d on d.codigo_material=s.cod_material where s.cod_tipoprecio=$codigo_registro and d.estado=1 order by 1)
   UNION (select d.codigo_material,0 as codigo_material,d.descripcion_material,(select cod_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor) as cod_proveedor,d.cod_linea_proveedor, 0 as porcentaje_material from material_apoyo d
      where d.estado=1 and d.codigo_material not in (SELECT s.cod_material from tipos_precio_productos s join material_apoyo d on d.codigo_material=s.cod_material where s.cod_tipoprecio=$codigo_registro and d.estado=1) order by 3 limit 20)";

//echo $sql;

$resp=mysqli_query($enlaceCon,$sql); 
?>
<script type="text/javascript">
  function generarProductosLinea(){
    alert("ok");
  }
  function seleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=1
   }
  function deseleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=0
   } 

   function calcularPrecioFinalDescuento(cod){
    var desc=$("#descuento"+cod).val();
    var precio=$("#precio"+cod).val();
    var precio_fin=precio*((100-desc)/100);
    precio_fin=precio_fin.toFixed(2);
    $("#precio_fin"+cod).val(precio_fin);
   }
   function calcularDescuentoFinal(cod){
    var precio_fin=$("#precio_fin"+cod).val();
    var precio=$("#precio"+cod).val();
    var desc=100-((precio_fin*100)/precio);
    desc=desc.toFixed(4);
    $("#descuento"+cod).val(desc);
   }
</script>


<?php

$cadComboLinea="";
$consult="select t.`cod_linea_proveedor`, t.`nombre_linea_proveedor`,p.nombre_proveedor from `proveedores_lineas` t join proveedores p on p.cod_proveedor=t.cod_proveedor where p.estado=1";
//echo $consult;

$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["cod_linea_proveedor"];
    $nomTipo = $reg1["nombre_linea_proveedor"];
    $nomProv = $reg1["nombre_proveedor"];
    $cadComboLinea.="<option value='$codTipo' data-subtext='$nomProv'>$nomTipo</option>";
   }
$cadComboForma="";
$consult="select e.cod_forma_far, e.nombre_forma_far from formas_farmaceuticas e where e.estado=1 order by 2";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["cod_forma_far"];
    $nomTipo = $reg1["nombre_forma_far"];
    $cadComboForma.="<option value='$codTipo'>$nomTipo</option>";
   }
$cadComboAccion="";
$consult="select e.cod_accionterapeutica, e.nombre_accionterapeutica from acciones_terapeuticas e where e.estado=1 order by 2";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["cod_accionterapeutica"];
    $nomTipo = $reg1["nombre_accionterapeutica"];
    $cadComboAccion.="<option value='$codTipo'>$nomTipo</option>";
   }
echo "<form action='$urlSaveEditProducto' method='post' name='f1'>";
echo "<h1>Modificar Producto del Clasificador</h1>";
echo "<input type='hidden' value='$codigo_registro' name='tipo' id='tipo'>";
echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
  <div class='float-right'><a href='#' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modalBuscarProducto'>&nbsp;<i class='material-icons'>search</i></a>  
  </div>
</div>";
	echo "<center><table class='table table-sm table-bordered' id='tabla_productos'>";
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
    $proveedor=obtenerNombreProveedor($dat[3]);
    $linea=obtenerNombreProveedorLinea($dat[4]);
		$producto=$dat[2];
    $porcentDesc=$descDefault;
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
	echo "</table></center><br>";




echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";

echo "</form>";
?>


<!-- modal devolver solicitud -->
<div class="modal fade" id="modalBuscarProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#732590; !important;color:#fff;">
        <h4 class="modal-title">Buscar Producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
      </div>
      <div class="modal-body">        
        <div class="row">
          <label class="col-sm-1 col-form-label" style="color:#7e7e7e"><span id=""><small>Codigo<br>Producto</small></span></label>
          <div class="col-sm-2">
            <div class="form-group" >
              <input type="text" class="form-control" name="buscar_codigo" id="buscar_codigo" style="background-color:#e2d2e0">              
            </div>
          </div>
          <label class="col-sm-1 col-form-label" style="color:#7e7e7e"><span id=""><small>Nombre<br>Producto</small></span></label>
          <div class="col-sm-8">
            <div class="form-group" >              
                <input type="text" class="form-control" name="buscar_nombre" id="buscar_nombre" style="background-color:#e2d2e0">
            </div>
          </div>
        </div> 
        <div class="row">
          <label class="col-sm-1 col-form-label" style="color:#7e7e7e"><span id=""><small >Lineas</small></span></label>
          <div class="col-sm-11">
            <div class="form-group" >              
                <select class="selectpicker form-control form-control-sm" data-live-search="true" title="-- Elija una linea --" name="buscar_linea[]" id="buscar_linea" multiple data-actions-box="true" data-style="select-with-transition" data-actions-box="true" data-size="10">
                    <?php echo $cadComboLinea;?>
                </select>
            </div>
          </div>
        </div> 
        <div class="row">
                    <div class="col-sm-6">
                      <div class="row">
                       <label class="col-sm-2 col-form-label" style="color:#7e7e7e"><small>Forma Farmacéutica</small></label>
                       <div class="col-sm-10">
                        <div class="form-group">
                              <select class="selectpicker form-control form-control-sm" name="buscar_forma[]" id="buscar_forma" data-style="select-with-transition" multiple data-actions-box="true" data-live-search="true" data-size="10">
                                 <?php echo $cadComboForma;?>
                                   </select>                           
                            </div>
                        </div>
                   </div>
                     </div>
                    <div class="col-sm-6">
                      <div class="row">
                       <label class="col-sm-2 col-form-label" style="color:#7e7e7e"><small>Acción Terapéutica</small></label>
                       <div class="col-sm-10">
                        <div class="form-group">
                                <select class="selectpicker form-control form-control-sm" name="buscar_accion[]" id="buscar_accion" data-style="select-with-transition" multiple data-actions-box="true" data-size="10" data-live-search="true">
                                 <?php echo $cadComboAccion;?>    
                                 </select>
                            </div>
                        </div>
                    </div>
              </div>
                  </div><!--div row-->     
      </div>
      <br>  
      <div class="modal-footer">
        <a href="#" class="btn btn-success btn btn-sm" style="background:#732590 !important;" onclick="buscarProductoLista('ajax_buscar_producto.php')"><i class="material-icons">search</i> BUSCAR PRODUCTO</a>
      </div>
    </div>
  </div>
</div>
<!-- modal reenviar solicitud devuelto -->