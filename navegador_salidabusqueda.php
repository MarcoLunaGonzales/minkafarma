<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <!--<link href="lib/css/textos.css" rel="stylesheet" type="text/css"/>-->
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
function desplegarSalidas(grpIng) {
    var tnroing=$("#txtnrosalida").val();
    var fecha1=$("#fecha1").val();
    var fecha2=$("#fecha2").val();
    if(tnroing!="" || (fecha1!="" && fecha2!="") ) {
        if(grpIng==1) {
            location.href="navegador_salidamuestras.php?txtnrosalida="+tnroing+"&fecha1="+fecha1+"&fecha2="+fecha2;
        } else {
            location.href="navegador_salidamateriales.php?txtnrosalida="+tnroing+"&fecha1="+fecha1+"&fecha2="+fecha2;;
        }
    } else {
        alert("Ingrese el numero de salida, y/o el periodo entre fechas.");
    }
}
        </script>
    </head>
    <body>
<?php

require("conexion.inc");
if ($global_tipoalmacen == 1) {
    require("estilos_almacenes_central.inc");
} else {
    require("estilos_almacenes.inc");
}
if ($grupo_salida == 1) {
    echo "<form method='post' action='navegador_salidamuestras.php'>";
    echo "<center><table border='0' class='textotit'><tr><th>Buscar Salida de Muestras</th></tr></table></center><br>";
} else {
    echo "<form method='post' action='navegador_salidamateriales.php'>";
    echo "<center><table border='0' class='textotit'><tr><th>Buscar Salida de Material de Apoyo</th></tr></table></center><br>";
}
echo "<br><center>";
echo "<table border='1' class='texto' cellspacing='0'>";
echo "<tr><td align='center' colspan='2'>Busqueda Salidas</td></tr>";
echo "<tr><td>Nro Salida</td><td><input id='txtnrosalida' type='text' value=''></td></tr>";
echo "<tr><td>De:</td><td><input id='fecha1' type='text' value=''></td></tr>";
echo "<tr><td>A:</td><td><input id='fecha2' type='text' value=''></td></tr>";
echo "</table>";

require("home_regional1.inc");
echo "<input type='button' onClick='javascript:desplegarSalidas($grupo_salida)' value='Buscar' class='boton'></center>";

echo "</form>";

?>
        <script type='text/javascript' language='javascript'>
$.datepicker.setDefaults({showOn: 'both', buttonImageOnly: true, buttonImage: 'lib/iconos/calendario.png'});
$("#fecha1").datepicker({dateFormat: 'yy-mm-dd'});
$("#fecha2").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
        <div id="pnldlggeneral"></div>
    </body>
</html>
