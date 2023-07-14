<?php

$indexGerencia=1;
require "conexionmysqli.php";
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenes.inc");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$cod_cliente = $_GET['cod_cliente'];

$rpt_territorio=$_COOKIE['global_agencia'];
$rpt_almacen=$_COOKIE['global_almacen'];
 
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
?>

<html>
    <head>
		<title>Cliente Precio</title>
        <link  rel="icon"   href="imagenes/card.png" type="image/png" />
        <link href="assets/style.css" rel="stylesheet" />
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<script type="text/javascript" src="functionsGeneral.js"></script>

        <style type="text/css">
        	body{
              zoom: 86%;
              line-height: 0;
            }
            img.bw {
	            filter: grayscale(0);
            }

            img.bw.grey {
            	filter: brightness(0.8) invert(0.4);
            	transition-property: filter;
            	transition-duration: 1s;	
            } 
            .btn-info{
            	background:#006db3 !important;
            }
            .btn-info:hover{
            	background:#e6992b !important;
            }
            .btn-warning{
            	background:#e6992b !important;
            }
            .btn-warning:hover{
            	background:#1d2a76 !important;
            }


            .check_box:not(:checked),
			.check_box:checked {
			position : absolute;
			left     : -9999px;
			}

			.check_box:not(:checked) + label,
			.check_box:checked + label {
			position     : relative;
			padding-left : 30px;
			cursor       : pointer;
			}

			.check_box:not(:checked) + label:before,
			.check_box:checked + label:before {
			content    : '';
			position   : absolute;
			left       : 0px;
			top        : 0px;
			width      : 20px;
			height     : 20px;
			border     : 1px solid #aaa;
			background : #f8f8f8;
			}

			.check_box:not(:checked) + label:after,
			.check_box:checked + label:after {
			font-family             : 'Material Icons';
			content                 : 'check';
			text-rendering          : optimizeLegibility;
			font-feature-settings   : "liga" 1;
			font-style              : normal;
			text-transform          : none;
			line-height             : 22px;
			font-size               : 21px;
			width                   : 22px;
			height                  : 22px;
			text-align              : center;
			position                : absolute;
			top                     : 0px;
			left                    : 0px;
			display                 : inline-block;
			overflow                : hidden;
			-webkit-font-smoothing  : antialiased;
			-moz-osx-font-smoothing : grayscale;
			color                   : #09ad7e;
			transition              : all .2s;
			}

			.check_box:not(:checked) + label:after {
			opacity   : 0;
			transform : scale(0);
			}

			.check_box:checked + label:after {
			opacity   : 1;
			transform : scale(1);
			}

			.check_box:disabled:not(:checked) + label:before,
			.check_box:disabled:checked + label:before {
			&, &:hover {
				border-color     : #bbb !important;
				background-color : #ddd;
			}
			}

			.check_box:disabled:checked + label:after {
			color : #999;
			}

			.check_box:disabled + label {
			color : #aaa;
			}

			.check_box:checked:focus + label:before,
			.check_box:not(:checked):focus + label:before {
			border : 1px dotted #09ad7e;
			}

			label:hover:before {
			border : 1px solid #09ad7e !important;
			}
				td a:focus {
					color: #febd00 !important;
					/*font-size: 20px !important;*/
					background:#1d2a76 !important;
					}
					td a:hover {
					color: #febd00 !important;
					/*font-size: 20px !important;*/
					background:#1d2a76 !important;
					}       



			.sidenav {
			height: 100%;
			width: 0;
			position: fixed;
			z-index: 1;
			top: 0;
			left: 0;
			background-color: #006db3;
			overflow-x: hidden;
			transition: 0.1s;
			padding-top: 60px;
			color: #fff;
			}

			.sidenav a {
			padding: 8px 8px 8px 32px;
			text-decoration: none;
			font-size: 25px;
			color: #818181;
			display: block;
			transition: 0.3s;
			}

			.sidenav a:hover {
			color: #f1f1f1;
			}

			.sidenav .closebtn {
			position: absolute;
			top: 0;
			right: 25px;
			font-size: 36px;
			margin-left: 50px;
			}

			@media screen and (max-height: 450px) {
			.sidenav {padding-top: 15px;}
			.sidenav a {font-size: 18px;}
			}
        </style>
<script type='text/javascript' language='javascript'>
function funcionInicio(){
	//document.getElementById('nitCliente').focus();
}

</script>


	<?php
		$nombreClienteX=nombreCliente($enlaceCon,$cod_cliente);
		// Obtener el CODIGO del registro antes de eliminarlo

		$query = "SELECT cp.observaciones, CONCAT(c.nombre_cliente, ' ', c.paterno) as cliente
				FROM clientes_precios cp
				LEFT JOIN clientes c ON c.cod_cliente = cp.cod_cliente
				WHERE cp.cod_cliente = '".$cod_cliente."'";

		//echo $query;
		$result = mysqli_query($enlaceCon, $query);
		if (!$result) {
			echo "Error al obtener el Observación del registro: " . mysqli_error($enlaceCon);
			exit;
		}
		$row = mysqli_fetch_assoc($result);
		$observacion = empty($row['observaciones'])?'':$row['observaciones'];
	?>

<br><br><br>
<center>
	<h1>Vista Previa de Precios por Cliente <br> Cliente: <?= $nombreClienteX; ?></h1>
</center>

<table class="texto" align="center" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td>
				<th align="left" width="30%">Observación:</th> 
				<td bgcolor="#ffffff" width="70%"><?=$observacion;?></td>
			</td>
		</tr>
	</tbody>
</table>


<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0" border="0">
		<tr align="center">
			<td width="38%">Producto</td>
			<td width="8%">Precio </td>
			<td width="15%">Desc. %</td>
			<td width="15%">Desc. Monto</td>
			<td width="8%">Precio Cliente</td>
		</tr>
	</table>
	<?php
	$cantidad_total = 0;
	// Obtener el detalle de un registro específico
	$codigo_registro = 1; // Código del registro que deseas obtener el detalle		
	$query = "SELECT cpd.*, m.codigo_material, m.descripcion_material, (select concat(p.nombre_proveedor,'-',pl.nombre_linea_proveedor)as nombre_proveedor
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) as detalle_proveedor
				FROM clientes_precios cp
				LEFT JOIN clientes_preciosdetalle cpd ON cpd.cod_clienteprecio = cp.codigo 
				LEFT JOIN material_apoyo m ON m.codigo_material = cpd.cod_producto 
				WHERE cp.cod_cliente = '".$cod_cliente."'
				ORDER BY cpd.codigo ASC";
	$result = mysqli_query($enlaceCon, $query);

	if (!$result) {
		echo "Error al ejecutar la consulta: " . mysqli_error($enlaceCon);
		exit;
	}

	// Verificar si se encontraron registros
	if (mysqli_num_rows($result) > 0) {
		// Recorrer los registros
		while ($row = mysqli_fetch_assoc($result)) {
			$cantidad_total++;
			if(!empty($row['cod_producto'])){
	?>

	<table border="0" align="center" width="100%" class="texto" id="data1">
		<tbody>
			<tr bgcolor="#FFFFFF" class="lista_registro">

				<td width="38%" align="center">
					<?=$row['descripcion_material'];?> - <?=$row['detalle_proveedor'];?> (<?=$row['codigo_material'];?>)
				</td>

				<td align="center" width="8%">
					<?=$row['precio_base'];?>
				</td>

				<td align="center" width="15%">
					<?=$row['porcentaje_aplicado'];?>
				</td>
				<td align="center" width="15%">
					<?=$row['precio_aplicado'];?>
				</td>
				<td align="center" width="8%">
					<?=$row['precio_producto'];?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
			}
		}
	}
	?>
</fieldset>

<center>
	<table border="0" class="texto">
		<tbody>
			<tr>
				<td>
					<input class="boton2" type="button" value="Volver" onclick="javascript:listaClientes();">
				</td>
			</tr>
		</tbody>
	</table>
</center>

<!-- Volver a lista de clientes -->
<script>
	function listaClientes(){
		location.href="programas/clientes/inicioClientes.php";
	}
</script>

</body>
</html>
