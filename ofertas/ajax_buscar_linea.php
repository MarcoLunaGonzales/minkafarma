<?php
$estilosVenta=1;
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");

$codigo_registro=$_POST["codigo_registro"];


$sqlNombre="";
if(isset($_POST['nombre'])&&$_POST['nombre']!=""){
  $sqlNombre="and d.nombre_linea_proveedor like '%".$_POST['nombre']."%'";
}

$stringProvX="";
if(isset($_POST['proveedor'])&&count($_POST['proveedor'])>0){
  $stringProvX=" and d.cod_proveedor in (".implode(",",$_POST['proveedor']).") ";
}


$sqllimit="";
if($sqlNombre==""&&$stringProvX=="")
{
  //$sqllimit="LIMIT 100";
}

$sql="(SELECT s.cod_linea_proveedor,d.cod_linea_proveedor,d.cod_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor
from tipos_precio_lineas s join proveedores_lineas d on d.cod_linea_proveedor=s.cod_linea_proveedor where s.cod_tipoprecio=$codigo_registro and d.estado=1)
   UNION (SELECT d.cod_linea_proveedor,0 as cod_linea_proveedor,d.cod_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor
from proveedores_lineas d where d.estado=1 and d.cod_linea_proveedor not in (SELECT s.cod_linea_proveedor from tipos_precio_lineas s join proveedores_lineas d on d.cod_linea_proveedor=s.cod_linea_proveedor where s.cod_tipoprecio=$codigo_registro and d.estado=1) $stringProvX $sqlNombre order by 4,5 $sqllimit)";
  //echo $sql;

$resp=mysqli_query($enlaceCon,$sql); 


echo "<table class='table table-sm table-bordered' id='tabla_productos'>";
  echo "<tr class='bg-info text-white font-weight-bold'>
  <th width='10%'><div class='btn-group'><a href='#' class='btn btn-sm btn-warning' onClick='seleccionar_todo()'>T</a><a href='#' onClick='deseleccionar_todo()' class='btn btn-sm btn-default'>N</a></div></th>
  <th>Proveedor</th>
  <th>Linea</th>
  <th>Estado</th>
  </tr>";
  $index=0;
  while($dat=mysqli_fetch_array($resp))
  {
    $index++;    
    $lineas=$dat[4];
    $proveedor=$dat[3];
    $estado="";
    $checked="";
    if($dat[1]>0){
         $estiloTexto="text-success font-weight-bold";
         $checked="checked";
         $estado="REGISTRADO";
    }
    echo "<tr>
    <td><input type='checkbox' name='codigo[]' value='$dat[0]' $checked></td>
    <td>$proveedor</td>
    <td>$lineas</td>
    <td>$estado</td>
    </tr>";
  }
  echo "</table>";
