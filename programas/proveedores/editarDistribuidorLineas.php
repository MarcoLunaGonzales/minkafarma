<?php
require("../../conexionmysqli.php");

require("../../estilos_almacenes.inc");

require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codDistribuidor    = $_GET['codDistribuidor'];
$codLineaProveedor  = $_GET['codLineaProveedor'];
$nombreDistribuidor=nombreProveedor($enlaceCon,$codDistribuidor);
             
$consulta="SELECT dl.cod_distribuidor, dl.cod_linea_proveedor, UPPER(pl.nombre_linea_proveedor) as nombre_linea_proveedor, p.nombre_proveedor
            FROM distribuidores_lineas dl
            LEFT JOIN proveedores_lineas pl ON dl.cod_linea_proveedor = pl.cod_linea_proveedor
            LEFT JOIN proveedores p ON p.cod_proveedor = pl.cod_proveedor
            WHERE dl.cod_distribuidor = '$codDistribuidor'
            AND dl.cod_linea_proveedor = '$codLineaProveedor'
            LIMIT 1";

$rs = mysqli_query($enlaceCon, $consulta);

while ($reg = mysqli_fetch_array($rs)) {
    $nombreProveedor = $reg['nombre_proveedor'];
}
?>
<form action="actualizarDistribuidorLinea.php" method="post">
    <input type="hidden" name="codDistribuidor" id="codDistribuidor" value="<?php echo $codDistribuidor?>">
    <center>
        <br/>
        <h1>Cambiar Linea<br>Distribuidor - <?php echo $nombreDistribuidor;?></h1>
        <h1>Proveedor: <?php echo $nombreProveedor;?></h1>

        <input type="hidden" name="cod_linea_proveedor_antiguo" value="<?= $codLineaProveedor; ?>">
        <table class="texto" style="width: 50%;">
            <tr>
                <th width="50%">Lineas de Proveedor</th>
            </tr>
            <tr>
                <td>
                    <select name='cod_linea_proveedor' id='cod_linea_proveedor' class='selectpicker form-control' data-live-search="true" required data-style="btn btn-rose">
                        <option value=''>Seleccionar proveedor</option>
                        <?php
                            $consulta="SELECT pl.cod_linea_proveedor, pl.nombre_linea_proveedor
                                        FROM proveedores_lineas pl
                                        WHERE pl.estado = 1
                                        AND pl.cod_proveedor = (
                                            SELECT spl.cod_proveedor
                                            FROM proveedores_lineas spl
                                            WHERE spl.cod_linea_proveedor = '$codLineaProveedor'
                                            LIMIT 1
                                        )
                                        ORDER BY pl.nombre_linea_proveedor";
                            $rs=mysqli_query($enlaceCon,$consulta);
                            while($reg=mysqli_fetch_array($rs)){
                        ?>
                            <option value="<?= $reg["cod_linea_proveedor"]; ?>" <?= $reg["cod_linea_proveedor"] == $codLineaProveedor ? 'selected' : ''; ?>><?= $reg["nombre_linea_proveedor"]; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </center>
    <div class="divBotones">
        <input class="boton" type="submit" value="Actualizar"/>
        <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegadorListaDistribuidorLineas.php?codProveedor=<?php echo $codDistribuidor;?>'" />
    </div>
</form>