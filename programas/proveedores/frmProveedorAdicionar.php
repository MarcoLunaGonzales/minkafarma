<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = "";
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";
$tipoProveedor  = "";

?>
<center>
    <br/>
    <h2>Adicionar Proveedor & Representante</h2>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Tipo de Proveedor</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codProv"; ?></span></td>
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
    <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>