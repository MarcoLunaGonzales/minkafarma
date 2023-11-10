<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = $_GET["codprov"];
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";
$tipoProveedor  = "";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto, p.cod_tipoproveedor as tipoProveedor 
    FROM proveedores AS p 
    WHERE p.cod_proveedor = $codProv ORDER BY p.nombre_proveedor ASC
";
$rs=mysqli_query($enlaceCon,$consulta);
$nroregs=mysqli_num_rows($rs);
if($nroregs==1)
   {$reg=mysqli_fetch_array($rs);
    //$codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
    $tipoProveedor  = $reg["tipoProveedor"];
   }

?>
<center>
    <br/>
    <h2>Editar Proveedor & Representante</h2>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Tipo de Proveedor</th>
        </tr>
        <tr>
            <td><span id="codpro"><?php echo "$codProv"; ?></span></td>
            <td><input type="text" id="nompro" value="<?php echo "$nomProv"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$direccion"; ?>"/></td>
            <td>
                <select name="tipoProveedor" id="tipoProveedor">
                    <?php 
                        $consulta="SELECT tp.cod_tipoventa, tp.nombre_tipoventa
                                    FROM tipos_proveedor AS tp 
                                    WHERE tp.estado = 1
                                    ORDER BY tp.cod_tipoventa ASC";
                        $rs=mysqli_query($enlaceCon,$consulta);//se actualizo la conexion
                        $cont=0;
                        while($reg=mysqli_fetch_array($rs)){
                    ?>
                    <option value="<?=$reg['cod_tipoventa']?>"><?=$reg['nombre_tipoventa']?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
            <th>Contacto</th>
            <th></th>
        </tr>
        <tr>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
            <td><input type="text" id="tel2" value="<?php echo "$telefono2"; ?>"/></td>
            <td><input type="text" id="contacto" value="<?php echo "$contacto"; ?>"/></td>
            <td></td>
        </tr>
    </table>
</center>
<div class="divBotones"> 
	<input class="boton" type="button" value="Modificar" onclick="javascript:modificarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>
