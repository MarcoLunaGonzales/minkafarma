<html>
    <head>
        <title>Clientes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../lib/css/paneles.css"/>
        <link rel="stylesheet" type="text/css" href="../../stilos.css"/>
        <script type="text/javascript" src="../../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../../lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
/*proceso inicial*/
$(document).ready(function() {
    //
    listadoClientes();
    //
});
/*proceso inicial*/
function listadoClientes() {
    cargarPnl("#pnl00","prgListaClientes.php","");
}
//procesos
function frmAdicionar() {
    cargarPnl("#pnl00","frmClienteAdicionar.php","");
}

function frmModificar() {
    var primerCheckboxSeleccionado = null;

    // Recorrer todos los checkboxes
    $("input[type='checkbox']").each(function() {
      if (this.checked) {
        primerCheckboxSeleccionado = this;
        return false;
      }
    });

    if (primerCheckboxSeleccionado != null) {
        var cod = $(primerCheckboxSeleccionado).val();
        cargarPnl("#pnl00","frmClienteEditar.php","codcli="+cod);
    } else {
        alert("Seleccione un elememnto para editar.");
    }
}
function frmEliminar() {
    var total=$("#idtotal").val();
    var tag,sel,cods="0",c=0;
    for(var i=1;i<=total;i++) {
        tag=$("#idchk"+i);
        sel=tag.attr("checked");
        if(sel==true) {
            cods=cods+","+tag.val(); c++;
        }
    }
    if(c>0) {
        if(confirm("Esta seguro de eliminar "+c+" elemento(s) ?")) {
            eliminarCliente(cods);
        }
    } else {
        alert("Seleccione para eliminar.");
    }
}
function adicionarCliente() {
    var nomcli = $("#nomcli").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    
    var apellidos = $("#apellidos").val();
    var ci        = $("#ci").val();
    var genero    = $("#genero").val();
    var edad      = $("#edad").val();
    var parms="nomcli="+nomcli+"&nit="+nit+"&dir="+dir+"&tel1="+tel1+"&mail="+mail+"&area="+area+"&fact="+fact+"&apcli="+apellidos+"&ci="+ci+"&genero="+genero+"&edad="+edad;
    // console.log(parms)
    cargarPnl("#pnl00","prgClienteAdicionar.php",parms);
}
function modificarCliente() {
    var codcli = $("#codcli").text();
    var nomcli = $("#nomcli").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    
    var apellidos = $("#apellidos").val();
    var ci        = $("#ci").val();
    var genero    = $("#genero").val();
    var edad      = $("#edad").val();
    var parms="codcli="+codcli+"&nomcli="+nomcli+"&nit="+nit+"&dir="+dir+"&tel1="+tel1+"&mail="+mail+"&area="+area+"&fact="+fact+"&apcli="+apellidos+"&ci="+ci+"&genero="+genero+"&edad="+edad;
    cargarPnl("#pnl00","prgClienteModificar.php",parms);
}
function eliminarCliente(cods) {
    var codcli = cods;
    var parms="codcli="+codcli+"";
    cargarPnl("#pnl00","prgClienteEliminar.php",parms);
}
        </script>
    </head>
    <body>
        <div id='pnl00'></div>
        <div id='pnldlgfrm'></div>
        <div id='pnldlggeneral'></div>
        <div id='pnldlgenespera'></div>
    </body>
    
    <!-- 
        PREPARACIÓN DE DATOS JSON
    -->
    <script type="text/javascript" src="../../assets/js/core/jquery.min.js"></script>
    <script type="text/javascript" src="../../assets/js/plugins/sweetalert2.js"></script>
    <script type="text/javascript" src="../../assets/alerts/xlsx.full.min.js"></script>
    <script type="text/javascript">
        /**
         * Abre Modal de Documento
         */
        $('body').on('click', '.modal_documento', function(){
            $('#cargar_doc').val();
            $('#cargar_cod_cliente').val($(this).data('cod_cliente'));
            $('#cargarModal').modal('show');
        });
        /**
         * Función para obtener JSON de Excel
         */
        function obtenerJSONDesdeExcel(file, callback) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var sheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[sheetName];
                var jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });
                // console.log(jsonData.length)
                // Realizar el cambio de nombres de propiedades
                var nuevoJSON = jsonData.map(function(item) {
                        return {
                            cod_producto: item.Codigo,
                            precio_producto: item['PRECIO A CARGAR']
                        };
                    });
                // console.log(nuevoJSON)
                callback(nuevoJSON);
                // var primeros5Registros = nuevoJSON.slice(0, 1000); // Obtener los primeros 1000 registros
                // callback(primeros5Registros);
            };
            reader.readAsArrayBuffer(file);
        }
        /**
         * Cargar Documento Precio Cliente
         */
        $('body').on('click', '#cargar_save', function() {
            var cod_cliente = $('#cargar_cod_cliente').val();
            var file = $('#cargar_doc')[0].files[0];
            if (!file) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, selecciona un archivo.',
                    type: 'error'
                });
                return; // Detener el flujo de ejecución si el campo está vacío
            }
            var datos = [];
            Swal.fire({
                title: 'Confirmar',
                text: '¿Estás seguro de reemplazar los datos existentes con los del archivo cargado?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    var datos = [];
                    obtenerJSONDesdeExcel(file, function(jsonData) {
                        // Dividir los datos en lotes más pequeños
                        var batchSize = 200; // Tamaño del lote (ajústalo según tus necesidades)
                        var batches = [];
                        for (var i = 0; i < jsonData.length; i += batchSize) {
                            batches.push(jsonData.slice(i, i + batchSize));
                        }
                        /**
                         * LIMPIA REGISTRO
                         **/
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: "clientePrecioArchivoSave.php",
                            data: {
                                cod_cliente: cod_cliente,
                                tipo: 1
                            },
                            success: function(resp) {
                                // console.log('Listo, limpio');
                                // Iniciar el procesamiento por lotes
                                procesarLotes(0, batches, cod_cliente);
                                $('#cargarModal').modal('hide');
                            },
                            error: function() {
                                Swal.fire('Error', 'Se produjo un error al procesar el lote ' + (index + 1), 'error');
                            }
                        });
                    });
                }
            });
        });
        /**
         * Proceso de Almacenamiento LOTE
         */
        function procesarLotes(index, batches, cod_cliente) {
            if (index >= batches.length) {
                Swal.fire('Registro Correcto', '', 'success');
                return; // Finalizar el procesamiento por lotes
            }

            var batch = batches[index];

            // Mostrar indicador de carga para el lote actual
            Swal.fire({
                title: 'Cargando (Lote ' + (index + 1) + '/' + batches.length + ')',
                text: 'Por favor, espera...',
                icon: 'info',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            /**
             * Enviar el lote actual al servidor
             */
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "clientePrecioArchivoSave.php",
                data: {
                    items: batch,
                    cod_cliente: cod_cliente,
                    tipo: 2
                },
                success: function(resp) {
                    // console.log(resp)
                    Swal.close(); // Ocultar indicador de carga del lote actual
                    // Procesar el siguiente lote
                    procesarLotes(index + 1, batches, cod_cliente);
                },
                error: function() {
                    Swal.fire('Error', 'Se produjo un error al procesar el lote ' + (index + 1), 'error');
                }
            });
        }
        /**
         * Abre Modal para Clonar Precio Cliente
         */
        $('body').on('click', '.modal_clonar', function(){
            $('#cargar_doc').val();
            $('#cliente_actual').val($(this).data('cod_cliente'));
            $('#clonarModal').modal('show');
        });
        /**
         * Proceso de Guardado de Clonación
         */
         $('body').on('click', '#clonar_save', function(){
            var cod_cliente         = $('#cliente_actual').val();
            var cod_cliente_clonado = $('#cliente_clonado').val();
            Swal.fire({
                title: 'Confirmar',
                text: '¿Estás seguro de clonar los precios del cliente seleccionado?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: "clientePrecioClonarSave.php",
                        data: {
                            cod_cliente: cod_cliente,
                            cod_cliente_clonado: cod_cliente_clonado
                        },
                        success: function(resp) {
                            if(resp.status){
                                Swal.fire(resp.message, '', 'success');
                            }else{
                                Swal.fire(resp.message, '', 'error');
                            }
                            $('#clonarModal').modal('hide');
                        },
                        error: function() {
                            Swal.fire('Error', 'Se produjo un error al procesar el lote ' + (index + 1), 'error');
                        }
                    });
                }
            });
        });
    </script>

</html>

<?php

?>
