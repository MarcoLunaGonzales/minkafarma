<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codCliente = $_GET["codcli"];
$nomCliente = "";
$nitCliente = "";
$dirCliente = "";
$telefono1  = "";
$email      = "";
$codArea    = "";
$nomFactura = "";
$nomArea    = "";
$codTipoEdad= "";
$codGenero  = "";
$cadComboCiudad = "";
$consulta="
    SELECT c.cod_cliente, c.nombre_cliente, c.paterno, c.nit_cliente, c.ci_cliente, c.dir_cliente, c.telf1_cliente, c.email_cliente, c.cod_area_empresa, c.nombre_factura, a.cod_ciudad, a.descripcion, c.cod_tipo_edad, c.cod_genero
    FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad
    WHERE c.cod_cliente = $codCliente ORDER BY c.nombre_cliente ASC
";
$rs=mysqli_query($enlaceCon,$consulta);
$nroregs=mysqli_num_rows($rs);
if($nroregs==1){
    $reg=mysqli_fetch_array($rs);
    //$codCliente = $reg["cod_cliente"];
    $nomCliente = $reg["nombre_cliente"];
    $nitCliente = $reg["nit_cliente"];
    $ciCliente  = $reg["ci_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $apellidos  = $reg["paterno"];
    $telefono1  = $reg["telf1_cliente"];
    $email      = $reg["email_cliente"];
    $codArea    = $reg["cod_area_empresa"];
    $nomFactura = $reg["nombre_factura"];
    $nomArea    = $reg["descripcion"];
    $codTipoEdad= $reg["cod_tipo_edad"];
    $codGenero  = $reg["cod_genero"];
    $consulta="SELECT c.cod_ciudad, c.descripcion FROM ciudades AS c WHERE 1 = 1 ORDER BY c.descripcion ASC";
    $rs=mysqli_query($enlaceCon,$consulta);
    while($reg=mysqli_fetch_array($rs)){
        $codCiudad = $reg["cod_ciudad"];
        $nomCiudad = $reg["descripcion"];
        if($codArea==$codCiudad) {
            $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad' selected>$nomCiudad</option>";
        } else {
            $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad'>$nomCiudad</option>";
        }
    }
}

// GENERO
$comboGenero="";
$consult="select t.`cod_genero`, t.`descripcion` from `generos` t where cod_estadoreferencial=1";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1)){
    $codTipo = $reg1["cod_genero"];
    $nomTipo = $reg1["descripcion"];
    $comboGenero=$comboGenero."<option value='$codTipo' ".($codGenero==$codTipo ? 'selected':'').">$nomTipo</option>";
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
    $comboEdad=$comboEdad."<option value='$codigoEdad' ".($codTipoEdad==$codigoEdad ? 'selected':'').">$nomEdad ($desEdad)</option>";
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
        <h3>Editar Cliente</h3>
    </div>
    <form>
        <table>
            <tr>
                <th>Codigo</th>
                <th colspan="3"><span id="codcli"><?php echo "$codCliente"; ?></span></th>
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
                <th><input type="text" id="ci" value="<?php echo "$ciCliente"; ?>"/></th>
            </tr>
            <tr>
                <th>Razon Social ó<br> Nombre Factura</th>
                <th><input type="text" id="fact" value="<?php echo "$nomFactura"; ?>"/></th>
                <th>Ciudad</th>
                <th><select id="area"><?php echo "$cadComboCiudad"; ?></select></th>
            </tr>
            <tr>
                <th>Dirección</th>
                <th colspan="3"><input type="text" id="dir" value="<?php echo "$dirCliente"; ?>"/></th>
            </tr>
            <tr>
                <th>Género</th>
                <th><select id="genero"><option value="0" selected>--SELECCIONE--</option><?php echo "$comboGenero"; ?></select></th>
                <th>Edad</th>
                <th><select id="edad"><option value="0" selected>--SELECCIONE--</option><?php echo "$comboEdad"; ?></select></th>
            </tr>
        </table>
        <div class="divBotones">
            <input class="boton" type="button" value="Guardar" onclick="javascript:modificarCliente();" />
            <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
        </div>
    </form>
</div>
