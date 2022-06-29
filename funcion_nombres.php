<?php
	
function saca_nombre_muestra($enlaceCon,$codigo)
{	

$sql="select descripcion from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}
function nombreProducto($enlaceCon,$codigo)
{	
	
	$sql="select concat(descripcion, ' ',presentacion) from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}

function nombreGestion($enlaceCon,$codigo)
{	
	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreLinea($enlaceCon,$codigo)
{	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreVisitador($enlaceCon,$codigo)
{	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreTerritorio($enlaceCon,$codigo)
{	$sql="select descripcion from ciudades where cod_ciudad='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreMedico($enlaceCon,$codigo)
{	
	
	$sql="select concat(ap_pat_med,' ', nom_med) from Clientes where cod_med='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreDia($enlaceCon,$codigo)
{	
	
	$sql="select dia_contacto from orden_dias where id='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}


function nombreRutero($enlaceCon,$codigo)
{	$sql="select nombre_rutero from rutero_maestro_cab where cod_rutero='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreZona($enlaceCon,$codigo)
{	
	
	$sql="select zona from zonas where cod_zona='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreCategoria($enlaceCon,$codigo, $link)
{	
	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreCliente($enlaceCon,$codigo)
{	
	
	$sql="select nombre_cliente from clientes where cod_cliente='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreProveedor($enlaceCon,$codigo){
 
	$sql="select nombre_proveedor from proveedores where cod_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	return($nombre);
}

function nombreLineaProveedor($enlaceCon,$codigo){
	$sql="select nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

?>