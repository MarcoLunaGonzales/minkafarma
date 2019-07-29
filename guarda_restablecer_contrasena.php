<?php
require("conexion.inc");
// require("estilos_administracion.inc");

function validar_clave($clave,&$error_clave){
	if(strlen($clave) < 6){
		$error_clave = "La clave debe tener al menos 8 caracteres";
		return false;
	}
	if (!preg_match('`[a-z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra minuscula";
		return false;
	}
	if (!preg_match('`[A-Z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra mayuscula";
		return false;
	}
	if (!preg_match('`[0-9]`',$clave)){
		$error_clave = "La clave debe tener al menos un caracter numerico";
		return false;
	}
	if (!preg_match('`[-_$@...]`',$clave)){
		$error_clave = "La clave debe tener al menos un caracter especial";
		return false;
	}
	$error_clave = "";
	return true;
}

$date = date('Y-m-d');
$nuevafecha = strtotime ( '+6 month' , strtotime ( $date ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );


$error_encontrado="";
if (validar_clave($contrasena, $error_encontrado)){
	
	$txtUpd="UPDATE usuarios_sistema set contrasena='$contrasena'
	where codigo_funcionario='$codigo_funcionario'";
	$sql_update=mysql_query($txtUpd);
	
	echo "<script language='Javascript'>
	alert('Los datos fueron modificados correctamente.');
	location.href='navegador_funcionarios.php?cod_ciudad=$cod_territorio';
	</script>";
} else{
	echo "<script language='Javascript'>
	alert('".$error_encontrado."');
	history.go(-1);
	</script>";
}
?>