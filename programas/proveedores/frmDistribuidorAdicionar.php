<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = "";
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";

?>
<center>
    <h2>Adicionar Distribuidor</h2>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codProv"; ?></span></td>
            <td><input type="text" id="nompro" value="<?php echo "$nomProv"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$direccion"; ?>"/></td>
        </tr>
        <tr>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
            <th>Contacto</th>
        </tr>
        <tr>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
            <td><input type="text" id="tel2" value="<?php echo "$telefono2"; ?>"/></td>
            <td><input type="text" id="contacto" value="<?php echo "$contacto"; ?>"/></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarDistribuidor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoDistribuidores();" />
</div>