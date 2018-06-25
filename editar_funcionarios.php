<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	echo "<script language='Javascript'>
		function validar(f)
		{
			if(f.materno.value=='')
			{	alert('El campo Apellido Materno esta vacio');
				f.materno.focus();
				return(false);
			}
			if(f.nombres.value=='')
			{	alert('El campo Nombres esta vacio');
				f.nombres.focus();
				return(false);
			}
			f.submit();
		}
	</script>";
	require("conexion.inc");
	if($usuario_rrhh!="")
	{	require("estilos_rrhh.php");
	}
	else
	{	require("estilos.inc");
	}
	//sacamos los datos
	$sql="select * from funcionarios where codigo_funcionario='$j_funcionario'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$cargo=$dat[1];
		$paterno=$dat[2];
		$materno=$dat[3];
		$nombres=$dat[4];
		$fecha_nac=$dat[5];
		$direccion=$dat[6];
		$telefono=$dat[7];
		$celular=$dat[8];
		$email=$dat[9];
		$agencia=$dat[10];
		$estado=$dat[11];
		$exafinicial="$fecha_nac[8]$fecha_nac[9]/$fecha_nac[5]$fecha_nac[6]/$fecha_nac[0]$fecha_nac[1]$fecha_nac[2]$fecha_nac[3]";
	}
	echo "<center><table border=0 cellspacing=0 class='textotit'><tr><th>Editar Datos de Funcionario</th></tr></table><br>";
	echo "<form action='guardar_modi_funcionario.php' method='post'>";
	echo "<table border=1 cellspacing=0 class='texto'><tr><th colspan=4>Editar Datos Personales</th></tr>";
	echo "<tr><th>Paterno (*)</th><th>Materno (*)</th><th>Nombres (*)</th><th>Fecha de Nacimiento</th></tr>";
	echo "<input type='hidden' value='$codigo' name='codigo'>";
	echo "<tr>";
	echo "<td align='center'><input type='text' name='paterno' class='texto' value='$paterno'></td>";
	echo "<td align='center'><input type='text' name='materno' class='texto' value='$materno'></td>";
	echo "<td align='center'><input type='text' name='nombres' class='texto' value='$nombres'></td>";
	echo "<td align='center'><INPUT  type='text' class='texto' value='$exafinicial' id='exafinicial' size='10' name='exafinicial'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR></td>";
	echo "</tr>";
	echo "<tr><th>Dirección</th><th>Telefono Domicilio (*)</th><th>Telefono Celular</th><th>Cargo</th></tr>";
	echo "<tr>";
	echo "<td align='center'><input type='text' name='direccion' class='texto' value='$direccion'></td>";
	echo "<td align='center'><input type='text' name='telefono' class='texto' value='$telefono'></td>";
	echo "<td align='center'><input type='text' name='celular' class='texto' value='$celular'></td>";
	echo "<td align='center'><select name='cargo' class='texto'>";
			$sql_cargo=mysql_query("select cod_cargo,cargo from cargos order by cargo asc");
			while($dat_cargo=mysql_fetch_array($sql_cargo))
			{	$cod_cargo=$dat_cargo[0];
				$cargodes=$dat_cargo[1];
				if($cargo==$cod_cargo)
				{	echo "<option value='$cod_cargo' selected>$cargodes</option>";
				}
				else
				{	echo "<option value='$cod_cargo'>$cargodes</option>";
				}
			}
	echo "</select></td>";
	echo "</tr>";
	echo "<tr><th>Correo Electrónico</th><th>Agencia (*)</th><th>Estado</th><th></th></tr>";
	echo "<tr>";
	echo "<td align='center'><input type='text' name='email' class='texto' value='$email'></td>";
	echo "<td align='center'><select name='agencia' class='texto'>";
			$sql_agencia=mysql_query("select cod_ciudad,descripcion from ciudades order by descripcion asc");
			while($dat_agencia=mysql_fetch_array($sql_agencia))
			{	$cod_ciudad=$dat_agencia[0];
				$descripcion=$dat_agencia[1];
				if($agencia==$cod_ciudad)
				{	echo "<option value='$cod_ciudad' selected>$descripcion</option>";
				}
				else
				{	echo "<option value='$cod_ciudad'>$descripcion</option>";
				}

			}
	echo "</select></td>";
	echo "<td align='center'><select name='estado' class='texto'>";
	if($estado==1)
	{
	 	echo "<option value='1' selected>Activo</option><option value='0'>Retirado</option></select>"; 
	}
	if($estado==0)
	{
	  echo "<option value='1'>Activo</option><option value='0' selected>Retirado</option></select>";
	}
	echo "</td><td></td>";
	echo "</tr>";
	echo "</table><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$agencia'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>";
	echo "</form>";
	echo "</center>";
	echo "<center><table class='texto'><tr><th>Los campos marcados con (*) deben ser llenados obligatoriamente.</th></tr></table></center>";
	echo "</div>";
	echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>