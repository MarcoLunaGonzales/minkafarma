<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");


$codCliente = "";
$nomCliente = "";
$nitCliente = "";
$dirCliente = "";
$telefono1  = "";
$email      = "";
$codArea    = "";
$nomFactura = "";
$nomArea    = "";

$cadComboCiudad = "";
$consulta="SELECT c.cod_ciudad, c.descripcion FROM ciudades AS c WHERE 1 = 1 ORDER BY c.descripcion ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codCiudad = $reg["cod_ciudad"];
    $nomCiudad = $reg["descripcion"];
    $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad'>$nomCiudad</option>";
   }

$cadTipoPrecio="";
$consulta1="select t.`codigo`, t.`nombre` from `tipos_precio` t";
$rs1=mysqli_query($enlaceCon,$consulta1);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["codigo"];
    $nomTipo = $reg1["nombre"];
    $cadTipoPrecio=$cadTipoPrecio."<option value='$codTipo'>$nomTipo</option>";
   }

  
?>
<center>
    <br/>
    <h1>Adicionar Cliente</h1>
	
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Cliente</th>
            <th>NIT</th>
            <th>Direccion</th>
            <th>Telefono</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codCliente"; ?></span></td>
            <td><input type="text" id="nomcli" value="<?php echo "$nomCliente"; ?>"/></td>
            <td><input type="text" id="nit" value="<?php echo "$nitCliente"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$dirCliente"; ?>"/></td>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
        </tr>
        <tr>
            <th>Correo</th>
            <th>Factura</th>
            <th colspan="2">Ciudad</th>
            <th>Tipo de Precio</th>
        </tr>
        <tr>
            <td><input type="text" id="mail" value="<?php echo "$email"; ?>"/></td>
            <td><input type="text" id="fact" value="<?php echo "$nomFactura"; ?>"/></td>
            <td colspan="2"><select id="area"><?php echo "$cadComboCiudad"; ?></select></td>
            <td><select id="tipo_precio" name="tipo_precio"><?php echo "$cadTipoPrecio"; ?></select></td>
        </tr>
    </table>
    <br/>
	<div class="divBotones">
		<input class="boton" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
		<input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
	</div>
    <br/>
</center>
