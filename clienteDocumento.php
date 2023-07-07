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
		<title>Cliente Documentos</title>
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

<?php
	$nombreClienteX=nombreCliente($enlaceCon,$cod_cliente);
?>
<center>
	<h4 class="title">CARGA DE DOCUMENTOS <br> Cliente: <?= $nombreClienteX; ?></h4>
</center>

<table class="texto" align="center" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td>
				<input type="hidden" id="cod_cliente" value="<?= $cod_cliente; ?>">
				<th align="left">Nombre Documento:</th> 
				<td bgcolor="#ffffff">
					<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introducir el nombre del archivo">
				</td>
				<th align="left">Documento:</th> 
				<td bgcolor="#ffffff">
					<input type="file" class="form-control" id="archivo" name="archivo">
				</td>
				<td bgcolor="#ffffff">
					<button type="button" class="btn btn-primary" id="save_form">Guardar</button>
				</td>
			</td>
		</tr>
	</tbody>
</table>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="table" width="100%" id="data0" border="0">
		<thead>
			<tr align="center">
				<td width="10%" class="text-center">COD</td>
				<td width="60%" class="text-left">Archivo</td>
				<td width="10%" class="text-center">Fecha</td>
				<td width="30%" class="text-center">Acciones</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$cantidad_total = 0;
			$query = "SELECT cd.cod_documento, cd.cod_cliente, cd.nombre, cd.archivo, cd.fecha
						FROM clientes_documentos cd
						WHERE cd.cod_estado = 1
						AND cd.cod_cliente = '$cod_cliente'";
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
					if(!empty($row['cod_documento'])){
			?>
				<tr bgcolor="#FFFFFF" class="lista_registro">
					<td class="text-center"><?=$cantidad_total?></td>
					<td class="text-left"><?=$row['nombre']?></td>
					<td class="text-center"><?=$row['fecha']?></td>
					<td class="text-center">
						<a href="assets/cliente_documento/<?=$row['archivo']?>" download="<?=$row['nombre']?>" class="btn btn-info btn-sm">Descargar</a>
						<button class="btn btn-danger btn-sm del_form" data-cod_documento="<?=$row['cod_documento']?>">Eliminar</button>
					</td>
				</tr>
			<?php
					}
				}
			}
			?>
		</tbody>
	</table>
</fieldset>

</body>
<script>
	$('#save_form').on('click', function(){
		let nombre = $('#nombre').val();
		let file   = $('#archivo')[0].files[0];
		if (nombre == '' && file == undefined) {
			Swal.fire({
				title: 'Ops!',
				text: 'Debe ingresar un nombre y archivo para el registro',
				icon: 'warning',
				confirmButtonText: 'Aceptar'
			});
			return true;
		}
		var formData = new FormData();
		formData.append('cod_cliente', $('#cod_cliente').val());
		formData.append('nombre', nombre);
		formData.append('file', file);
		// Realizar la solicitud AJAX
		$.ajax({
			url: 'clienteDocumentoSave.php',
			method: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response) {
				if(response.status){
					Swal.fire('Éxito!', response.message, 'success');
					setTimeout(function() {
						location.reload();
					}, 3000);
				}else{
					Swal.fire('Ops!', response.message, 'error');
					setTimeout(function() {
						location.reload();
					}, 3000);
				}
			},
			error: function() {
				console.log('Error al enviar la imagen');
			}
		});
	});
	/**
	 * Eliminar Archivo
	 */
	$('.del_form').on('click', function(){
		let cod_documento = $(this).data('cod_documento');
		Swal.fire({
			title: '¿Estás seguro de eliminar?',
			text: 'Esta acción no se puede deshacer',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí',
			cancelButtonText: 'No'
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append('cod_documento', cod_documento);
				$.ajax({
					url: 'clienteDocumentoDelete.php',
					method: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					dataType: 'json',
					success: function(response) {
						if(response.status){
							Swal.fire('¡Confirmado!', 'La acción ha sido realizada.', 'success');
							setTimeout(function() {
								location.reload();
							}, 3000);
						}else{
							Swal.fire('Ops!', response.message, 'error');
							setTimeout(function() {
								location.reload();
							}, 3000);
						}
					},
					error: function() {
						console.log('Error al enviar la imagen');
					}
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal.fire('Cancelado', 'La acción ha sido cancelada.', 'error');
			}
		});
	});
</script>
</html>