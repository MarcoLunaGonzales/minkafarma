<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
require("funciones.php");
require("funcion_nombres.php");
$globalAgencia=$_COOKIE['global_agencia'];


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$cod_material=$_GET["cod_material"];
$consulta="select p.`precio` from precios p where p.`codigo_material`='$cod_material' and p.cod_precio=1 and cod_ciudad='$globalAgencia'";

$rs=mysqli_query($enlaceCon,$consulta);
$registro=mysqli_fetch_array($rs);
$precioMaterial=$registro[0];
if($precioMaterial>0){
  $precioMaterial=number_format($precioMaterial,2,'.','');
}
$dateActual=date("Y-m-d");

  $sql="SELECT a.cod_almacen,c.descripcion from ciudades c join almacenes a on a.cod_ciudad=c.cod_ciudad 
  where c.cod_ciudad>0 order by c.descripcion";
  
  //echo $sql;
  
  $resp=mysqli_query($enlaceCon,$sql); 

  $index=0;
  echo "<table>
  <th width='10%'>-</th>
  <th width='40%'>Producto</th>
  <th width='20%'>Sucursal</th>
  <th width='15%'>Stock</th>
  <th width='15%'>Precio</th></table>
  ";
  while($dat=mysqli_fetch_array($resp))
  {
   
   $codAlmacen=$dat[0];
   $sucursal=$dat[1];
   $producto=obtenerNombreProductoSimple($enlaceCon, $cod_material);
   $stock=stockProducto($enlaceCon, $codAlmacen, $cod_material);
   $estiloTexto="";    
    $index++; 
    echo "<table width='100%'>
    <tr>
    <td width='10%'>$index</td>
    <td width='40%'>$producto</td>
    <td width='20%'><i class='material-icons float-left' style='color:#6035B8'>place</i> $sucursal</td>
    <td width='15%'>$stock</td>
    <td width='15%'>$precioMaterial</td>
    </tr></table>";
  }
