<?php

require('../conexionmysqli.php');
require('../function_formatofecha.php');
require('../home_almacen.php');
require('../funciones.php');

?>


<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="../lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="../lib/js/xlibPrototipo-v0.1.js"></script>
        <link href="../stilos.css" rel='stylesheet' type='text/css'>
    </head>
    <body>
<form method='post' name='form1' action=''>
<?php


echo "<h1>Manuales TuFarma</h1>";

	echo "<br><center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nombre</th><th>URL</th></tr>";
	
	$consulta = "select codigo, nombre, url, estado from manuales_sistema where estado=1";
	$resp = mysqli_query($enlaceCon, $consulta);

    $indice=1;
	while ($dat = mysqli_fetch_array($resp)) {
		$codigo = $dat[0];
		$nombre = $dat[1];
		$url = $dat[2];
		$estado = $dat[3];
		
		echo "<tr>
		<td align='left'>$indice</td>
		<td align='left'>$nombre</td>
		<td align='center'>
            <a href='$url' target='_blank'><img src='../imagenes/libro.png' alt='Detalle' width='30' heigth='30'></a></td>
		</tr>";
        $indice++;
	}
	echo "</table></center><br>";
	echo "</form>";

?>
    </body>
</html>
