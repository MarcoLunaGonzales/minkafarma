<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$globalAgencia=$_COOKIE["global_agencia"];
$globalAlmacen=$_COOKIE["global_almacen"];

echo "<br>";
echo "<h1>Clientes</h1>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>";
echo "<br>";
echo "<br>";
echo "<center>";
echo "<table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Cliente</th><th>NIT</th><th>Direccion</th><th>Ciudad</th>";
echo "</tr>";
$consulta="
    SELECT c.cod_cliente, c.nombre_cliente, c.nit_cliente, c.dir_cliente, c.cod_area_empresa, a.descripcion
    FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad 
    WHERE c.cod_area_empresa='$globalAgencia' ORDER BY c.nombre_cliente ASC
";
$rs=mysqli_query($enlaceCon,$consulta);
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codCliente = $reg["cod_cliente"];
    $nomCliente = $reg["nombre_cliente"];
    $nitCliente = $reg["nit_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $codArea = $reg["cod_area_empresa"];
    $nomArea = $reg["descripcion"];
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' value='$codCliente' ></td><td>$nomCliente</td><td>$nitCliente</td><td>$dirCliente</td><td>$nomArea</td>";
    echo "</tr>";
   }
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>";

?>
