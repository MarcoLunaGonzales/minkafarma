<?php
require("conexionmysqli.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require("funciones.php");
?>

<html>
    <header>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    </header>
<body>

<?php
// Capturamos variables
$rpt_almacen = $_GET['rpt_almacen'];
$rpt_item    = $_GET['rpt_item'];

$consulta = "SELECT 
                ia.nro_correlativo, 
                ia.cod_almacen, 
                ia.nro_factura_proveedor, 
                DATE_FORMAT(ia.fecha,'%d-%m-%Y') as fecha, 
                ti.nombre_tipoingreso, 
                p.nombre_proveedor, 
                COALESCE(ROUND((((ida.cantidad_unitaria/m.cantidad_presentacion)*(ida.precio_bruto*m.cantidad_presentacion))-((ida.cantidad_unitaria/m.cantidad_presentacion) * (ida.precio_bruto*m.cantidad_presentacion)) * (ida.descuento_unitario/100)), 2), 0) as monto,
                ida.fecha_vencimiento,

                ida.cod_ingreso_almacen,
                ida.cod_material,
                ida.lote
            FROM ingreso_almacenes ia
            LEFT JOIN ingreso_detalle_almacenes ida ON ida.cod_ingreso_almacen = ia.cod_ingreso_almacen
            LEFT JOIN tipos_ingreso ti ON ti.cod_tipoingreso = ia.cod_tipoingreso
            LEFT JOIN proveedores p ON p.cod_proveedor = ia.cod_proveedor
            LEFT JOIN material_apoyo m ON m.codigo_material = ida.cod_material
            WHERE ida.cod_material = '$rpt_item'
            AND ia.cod_almacen = '$rpt_almacen'";
// echo $consulta;
$resp = mysqli_query($enlaceCon, $consulta);
?>
<div class="p-4">
    <h1>Ingreso de Materiales</h1>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-danger btn-sm btn-volver" href="navegador_op_fecha_vencimiento.php">Volver</a>
        </div>
    </div>

    <div id='divCuerpo' class="mt-4">
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th>Nro Correlativo</th>
                    <th>Cod Almacen</th>
                    <th>Nro Factura Proveedor</th>
                    <th>Fecha</th>
                    <th>Tipo Ingreso</th>
                    <th>Proveedor</th>
                    <th>Monto</th>
                    <th>Fecha Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $index = 0;
                    while ($dat = mysqli_fetch_array($resp)) { 
                        $index++;
                ?>
                    <tr>
                        <td><?= $dat['nro_correlativo'] ?></td>
                        <td><?= $dat['cod_almacen'] ?></td>
                        <td><?= $dat['nro_factura_proveedor'] ?></td>
                        <td><?= $dat['fecha'] ?></td>
                        <td><?= $dat['nombre_tipoingreso'] ?></td>
                        <td><?= $dat['nombre_proveedor'] ?></td>
                        <td><?= $dat['monto'] ?></td>
                        <td>
                            <input type="date" id="fecha<?=$index?>" value="<?= $dat['fecha_vencimiento'] ?>">
                            <span class="badge badge-info actualizarFecha" style="cursor: pointer; padding: 5px;" data-cod_ingreso_almacen="<?=$dat['cod_ingreso_almacen']?>" data-cod_material="<?=$dat['cod_material']?>" data-lote="<?=$dat['lote']?>" data-index="<?=$index?>" title="Actualizar">
                                <i class="fas fa-sync-alt"></i>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>

<script>
    $(document).ready(function () {
        $(".actualizarFecha").click(function () {
            // Obtén los datos necesarios desde los atributos de los elementos HTML
            var codIngresoAlmacen     = $(this).data("cod_ingreso_almacen");
            var codMaterial           = $(this).data("cod_material");
            var lote                  = $(this).data("lote");
            var index                 = $(this).data("index");
            var nuevaFechaVencimiento = $("#fecha" + index).val();

            // Realiza la solicitud AJAX
            $.ajax({
                type: "POST",
                url: "ajaxActualizaFechaVencimiento.php", // Reemplaza con la ruta de tu backend
                data: {
                    codIngresoAlmacen: codIngresoAlmacen,
                    codMaterial: codMaterial,
                    lote: lote,
                    nuevaFechaVencimiento: nuevaFechaVencimiento
                },
                success: function (response) {
                    // Maneja la respuesta del servidor
                    if (response.status) {
                        // La actualización fue exitosa
                        Swal.fire({
                            type: 'success',
                            title: 'Exito',
                            text: response.message
                        });
                    } else {
                        // Hubo un problema durante la actualización
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Maneja errores de la solicitud AJAX
                    Swal.fire({
                        type: 'error',
                        title: 'Error en la solicitud AJAX',
                        text: status + " - " + error
                    });
                }
            });
        });
    });
</script>
</html>
