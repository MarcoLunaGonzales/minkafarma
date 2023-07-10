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
echo "<th>&nbsp;</th>
        <th>Cliente</th>
        <th>NIT</th>
        <th>CI</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Direccion</th>
        <th>Ciudad</th>
        <th>Acciones</th>";
echo "</tr>";
$consulta="
    SELECT c.cod_cliente, CONCAT(c.nombre_cliente, ' ', c.paterno) as nombre_cliente, c.nit_cliente, c.ci_cliente, c.email_cliente, c.telf1_cliente, c.dir_cliente, c.cod_area_empresa, a.descripcion
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
    $ciCliente = $reg["ci_cliente"];
    $emailCliente = $reg["email_cliente"];
    $telefonoCliente = $reg["telf1_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $codArea = $reg["cod_area_empresa"];
    $nomArea = $reg["descripcion"];
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' value='$codCliente' ></td>
            <td>$nomCliente</td>
            <td>$nitCliente</td>
            <td>$ciCliente</td>
            <td>$emailCliente</td>
            <td>$telefonoCliente</td>
            <td>$dirCliente</td>
            <td>$nomArea</td>
    <td>
        <a href='../../clientePrecio.php?cod_cliente=$codCliente' title='Precios Clientes' class='text-dark'><i class='material-icons'>description</i></a>
        <a href='../../clienteDocumento.php?cod_cliente=$codCliente' target='_blank' title='Carga de Documentos' class='text-dark'><i class='material-icons'>cloud_upload</i></a>
    </td>";
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
