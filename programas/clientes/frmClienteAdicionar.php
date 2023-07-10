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

$apellidos  = "";
$ci         = "";
$direccion  = "";
$genero     = "";
$edad       = "";

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
while($reg1=mysqli_fetch_array($rs1)){
    $codTipo = $reg1["codigo"];
    $nomTipo = $reg1["nombre"];
    $cadTipoPrecio=$cadTipoPrecio."<option value='$codTipo'>$nomTipo</option>";
}

// GENERO
$comboGenero="";
$consult="select t.`cod_genero`, t.`descripcion` from `generos` t where cod_estadoreferencial=1";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1)){
    $codTipo = $reg1["cod_genero"];
    $nomTipo = $reg1["descripcion"];
    $comboGenero=$comboGenero."<option value='$codTipo'>$nomTipo</option>";
}
// EDAD
$comboEdad = "";
$consultaEdad="SELECT c.codigo,c.nombre, c.abreviatura FROM tipos_edades AS c WHERE c.estado = 1 ORDER BY 1";
$rs=mysqli_query($enlaceCon,$consultaEdad);
while($reg=mysqli_fetch_array($rs))
{
    $codigoEdad = $reg["codigo"];
    $nomEdad = $reg["abreviatura"];
    $desEdad = $reg["nombre"];
    $comboEdad=$comboEdad."<option value='$codigoEdad'>$nomEdad ($desEdad)</option>";
}
   
?>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-container h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-container table {
        width: 100%;
        margin-bottom: 20px;
    }

    .form-container th {
        text-align: left;
        padding: 8px;
        border-bottom: 1px solid #ccc;
    }

    .form-container td {
        padding: 8px;
        border-bottom: 1px solid #ccc;
    }

    .form-container input[type="text"],
    .form-container textarea,
    .form-container select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border-radius: 4px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        transition: border-color 0.3s ease;
    }

    .form-container input[type="text"]:focus,
    .form-container textarea:focus,
    .form-container select:focus {
        outline: none;
        border-color: #5a9dd5;
    }

    .form-container textarea {
        resize: vertical;
        height: 80px;
    }

    .form-container .divBotones {
        text-align: center;
    }

    .form-container .divBotones input[type="button"] {
        margin-right: 10px;
        padding: 8px 20px;
        font-size: 16px;
        border-radius: 4px;
    }

    .form-container .divBotones input[type="button"]:last-child {
        margin-right: 0;
    }
</style>

<div class="form-container">
    <div class="text-center">
        <h3>Adicionar Cliente</h3>
    </div>
    <form>
        <table>
            <tr>
                <th>Codigo</th>
                <th colspan="3"><span id="id"><?php echo "$codCliente"; ?></span></th>
            </tr>
            <tr>
                <th>Nombre (*)</th>
                <th><input type="text" id="nomcli" value="<?php echo "$nomCliente"; ?>"/></th>
                <th>Apellidos</th>
                <th><input type="text" id="apellidos" value="<?php echo "$apellidos"; ?>"/></th>
            </tr>
            <tr>
                <th>Teléfono (*)</th>
                <th><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></th>
                <th>Email (*)</th>
                <th><input type="text" id="mail" value="<?php echo "$email"; ?>"/></th>
            </tr>
            <tr>
                <th>NIT</th>
                <th><input type="text" id="nit" value="<?php echo "$nitCliente"; ?>"/></></th>
                <th>CI</th>
                <th><input type="text" id="ci" value="<?php echo "$ci"; ?>"/></th>
            </tr>
            <tr>
                <th>Razon Social ó<br> Nombre Factura</th>
                <th><input type="text" id="fact" value="<?php echo "$nomFactura"; ?>"/></th>
                <th>Ciudad</th>
                <th><select id="area"><?php echo "$cadComboCiudad"; ?></select></th>
            </tr>
            <tr>
                <th>Dirección</th>
                <th colspan="3"><input type="text" id="dir" value="<?php echo "$direccion"; ?>"/></th>
            </tr>
            <tr>
                <th>Género</th>
                <th><select id="genero"><option value="0" selected>--SELECCIONE--</option><?php echo "$comboGenero"; ?></select></th>
                <th>Edad</th>
                <th><select id="edad"><option value="0" selected>--SELECCIONE--</option><?php echo "$comboEdad"; ?></select></th>
            </tr>
        </table>
        <div class="divBotones">
            <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
            <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
        </div>
    </form>
</div>