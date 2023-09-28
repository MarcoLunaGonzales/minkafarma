<html>
<head>
	<meta charset="utf-8" />
	<title>MinkaSoftware</title> 
	    <link rel="shortcut icon" href="imagenes/icon_farma.ico" type="image/x-icon">
	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<style>  
	.boton-rojo
{
    text-decoration: none !important;
    padding: 10px !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    color: #ffffff !important;
    background-color: #E73024 !important;
    border-radius: 3px !important;
    border: 2px solid #E73024 !important;
}
.boton-rojo:hover{
    color: #000000 !important;
    background-color: #ffffff !important;
  }
</style>
     <link rel="stylesheet" href="dist/css/demo.css" />
     <link rel="stylesheet" href="dist/mmenu.css" />
	 <link rel="stylesheet" href="dist/demo.css" />
	<!--link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<link type="text/css" rel="stylesheet" href="menuLibs/dist/jquery.mmenu.css" />
    <link type="text/css" rel="stylesheet" href="stilos.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="menuLibs/dist/jquery.mmenu.js"></script>
	<script type="text/javascript">
		$(function() {
			$('nav#menu').mmenu();
		});
		
</script--> 

		
</head>
<body>
<?php
include("datosUsuario.php");
?>

<div id="page">

	<div class="header">
		<a href="#menu"><span></span></a>
		TuFarma - <?=$nombreEmpresa;?>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<?=$fechaSistemaSesion;?>][<?=$horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 15px; font-weight: bold; color: #ffff00;">
			[<?=$nombreUsuarioSesion;?>][<?=$nombreAlmacenSesion;?>]
		<div>
	</div>
	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame"  style="top:50px;" border="1"></iframe>	
	</div>
	
	
		<nav id="menu">
		<div id="panel-menu">
		
		<ul>
			<!--li><span>Datos Generales</span>
				<ul>
					<li><a href="navegador_material.php" target="contenedorPrincipal">Productos</a></li>
				</ul>
			</li!-->
			<li><span>Ingresos</span>
				<ul>
					<li><a href="navegador_ingresomateriales.php" target="_blank">Ingreso de Materiales</a></li>
					<li><a href="navegador_ingresotransito.php" target="contenedorPrincipal">Ingreso de Productos en Transito</a></li>
				</ul>	
			</li>
			<!--li><span>Salidas</span>
				<ul>
					<li><a href="navegador_salidamateriales.php" target="contenedorPrincipal">Listado de Traspasos & Salidas</a></li>
					<li><a href="navegadorVentas.php" target="contenedorPrincipal">Listado de Ventas</a></li>
				</ul>	
			</li>
			<li><a href="registrar_salidaventas.php" target="_blank">Vender / Facturar</a></li>
			<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal">Cierre de Caja</a></li>
			<li><a href="cambiarSucursalSesion.php" target="contenedorPrincipal">Cambiar Almacen</a></li>
			<li><a href="navegador_ajustarpreciostock.php" target="contenedorPrincipal">Consultar Precios **</a></li>

			<li><span>Marcados de Personal</span>
				<ul>
					<li><a href="registrar_marcado.php" target="contenedorPrincipal">Registro de Marcados</a></li>
				</ul>	
			</li-->
			<li><a href="navegadorVentaCaja.php" target="contenedorPrincipal">Listado de Ventas</a></li>
			<li><span>Reportes</span>
				<ul>
					<li><a href="rpt_op_inv_kardex.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
					<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
					<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
					<li><a href="rptOpVentasxItem.php" target="contenedorPrincipal">Ventas x Item</a></li>
				</ul>
			</li>
		</div>			
	</nav>
</div>
<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>
	</body>
</html>