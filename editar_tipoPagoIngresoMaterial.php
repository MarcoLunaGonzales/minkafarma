<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funcion_nombres.php");

echo "<link rel='stylesheet' type='text/css' href='stilos.css'/>";

$codIngreso=$_GET['codigo_ingreso'];

// Detalle de Ingreso
$sql1="SELECT ia.nro_correlativo, ia.fecha, ia.observaciones, UPPER(p.nombre_proveedor) as nombre_proveedor
        FROM ingreso_almacenes ia
        LEFT JOIN proveedores p ON p.cod_proveedor = ia.cod_proveedor
        WHERE ia.cod_ingreso_almacen = '$codIngreso'";
$resp1=mysqli_query($enlaceCon,$sql1);
$nro_correlativo  = '';
$fecha            = '';
$observaciones    = '';
$nombre_proveedor = '';
while($dat1=mysqli_fetch_array($resp1)){
    $nro_correlativo  = $dat1[0];
    $fecha            = $dat1[1];
    $observaciones    = $dat1[2];
    $nombre_proveedor = $dat1[3];
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="guarda_editarTipoPagoIngresoMaterial.php" method="post">
                <h1 class="text-center">Modificar Tipo de Pago de Ingreso</h1>
                <input type="hidden" name="codIngreso" id="codIngreso" value="<?php echo $codIngreso?>">
                <div class="table-responsive">
                    <table class="table texto">
                        <thead>
                            
                            <tr>
                                <th>Nro de Ingreso:</th>
                                <th>Fecha de Ingreso:</th>
                            </tr>
                            <tr>
                                <td><?=$nro_correlativo?></td>
                                <td><?=$fecha?></td>
                            </tr>
                            <tr>
                                <th>Proveedor:</th>
                                <th>Observación:</th>
                            </tr>
                            <tr>
                                <td><?=$nombre_proveedor?></td>
                                <td><?=$observaciones?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <select name="cod_tipopago" id="cod_tipopago" class="form-control">
                                    <?php
                                        $sql1="SELECT tp.cod_tipopago, tp.nombre_tipopago
                                                FROM tipos_pago tp
                                                WHERE tp.cod_tipopago = 1
                                                OR tp.cod_tipopago = 4
                                                ORDER BY tp.cod_tipopago ASC ";
                                        $resp1=mysqli_query($enlaceCon,$sql1);
                                        while($dat1=mysqli_fetch_array($resp1)){
                                            $codigo=$dat1[0];
                                            $nombre=$dat1[1];
                                            echo "<option value='$codigo'>$nombre</option>";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="select_tipo_pago" hidden>
                                <th colspan="2">Días de Credito:</th>
                            </tr>
                            <tr class="select_tipo_pago" hidden>
                                <td colspan="2">
                                    <input type="number" class="form-control" id="dias_credito" name="dias_credito" size="50"/>
                                </td>
                            </tr>
                            <tr class="select_tipo_pago" hidden>
                                <th colspan="2">Fecha Documento Proveedor: </th>
                            </tr>
                            <tr class="select_tipo_pago" hidden>
                                <td colspan="2"><input type="date" class="form-control" id="fecha_factura_proveedor" name="fecha_factura_proveedor" size="80"/></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <div class="divBotones text-center">
                <input class="boton" type="submit" value="Guardar"/>
                <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegador_ingresomateriales.php'" />
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#cod_tipopago').change(function() {
            var tipoPago = $(this).val();
            var selectTipoPago = $('.select_tipo_pago');
            var dias_credito = $('#dias_credito');
            var fecha_factura_proveedor = $('#fecha_factura_proveedor');

            if (tipoPago === "4") {
                selectTipoPago.removeAttr("hidden");
            } else {
                selectTipoPago.attr("hidden", true);
                dias_credito.val("");
                fecha_factura_proveedor.val("");
            }
        });
    });
</script>