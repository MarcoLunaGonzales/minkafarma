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
                // Realizar el cambio de nombres de propiedades
                var nuevoJSON = jsonData.map(function(item) {
                        return {
                            cod_producto: item.Codigo,
                            precio_producto: item['PRECIO A CARGAR']
                        };
                    });
                // callback(nuevoJSON);
                var primeros5Registros = nuevoJSON.slice(0, 5); // Obtener los primeros 5 registros
                callback(primeros5Registros);
            };
            reader.readAsArrayBuffer(file);
        }
        /**
         * Cargar Documento Precio Cliente
         */
        $('body').on('click', '#cargar_save', function() {
            let cod_cliente = $('#cargar_cod_cliente').val();
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
            obtenerJSONDesdeExcel(file, function(jsonData) {
                datos = jsonData;

                Swal.fire({
                    title: '¿Deseas continuar?',
                    text: 'Se reemplazaran los datos de Precio cliente',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: "clientePrecioArchivoSave.php",
                        data: {
                            items: datos,
                            cod_cliente: cod_cliente
                        },
                        success: function(resp) {
                            $('#cargarModal').modal('hide');
                            Swal.fire({
                                title: 'Correcto!',
                                text: 'Archivo cargado correctamente.',
                                type: 'success'
                            });
                        }
                        });
                    }
                });
            });
        });


    </script>

</html>

<?php

?>
