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
{	//require("conexionmysqli.php");
	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreLinea($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreVisitador($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreTerritorio($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select descripcion from ciudades where cod_ciudad='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreMedico($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select concat(ap_pat_med,' ', nom_med) from Clientes where cod_med='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreDia($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select dia_contacto from orden_dias where id='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}


function nombreRutero($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select nombre_rutero from rutero_maestro_cab where cod_rutero='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreZona($enlaceCon,$codigo)
{	//require("conexionmysqli.php");
	$sql="select zona from zonas where cod_zona='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}
/*function nombreCategoria($codigo, $link)
{	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";
	$resp=mysql_query($sql, $link);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}*/

function nombreCategoria($enlaceCon,$codigo)
{	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreCliente($enlaceCon,$codigo)
{	$sql="select nombre_cliente from clientes where cod_cliente='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreProveedor($enlaceCon,$codigo){
	$sql="select nombre_proveedor from proveedores where cod_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreAlmacen($enlaceCon,$codigo){
	$sql="select nombre_almacen from almacenes where cod_almacen='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		$dat=mysqli_fetch_array($resp);
		$nombre=$dat[0];
		//$nombre=mysql_result($resp,0,0);		
	}else{
		$nombre="-";
	}
	return($nombre);
}

function nombreGrupo($enlaceCon,$codigo){
	$sql="select nombre from grupos where codigo in ($codigo)";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	$nombre=substr($nombre,0,100);
	$nombre=$nombre."...";
	return($nombre);
}

/*function obtenerNombreMaestro($tabla, $codigo){
	$sql="select nombre from $tabla where codigo='$codigo'";
	$resp=mysql_query($sql);
	$nombre="";
	while($dat=mysql_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	return($nombre);
}*/
function obtenerNombreMaestro($enlaceCon, $tabla,$codigo){
	$sql="select nombre from $tabla where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	return($nombre);
}

function nombreFuncionarioReal($enlaceCon,$codigo)
{	
	$sql="select concat(nombres,' ',paterno,' ',materno) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	
	// $nombre=mysqli_result($resp,0,0);
	mysqli_close($enlaceCon);
	return($nombre);
}

?>