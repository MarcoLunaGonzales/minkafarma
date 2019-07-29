<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
                function enviar_nav(cod_ciudad)
                {       location.href='registro_funcionarios.php?cod_ciudad='+cod_ciudad;
                }
                function eliminar_nav(f, cod_ciudad)
                {
                        var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j==0)
                        {       alert('Debe seleccionar al menos un funcionario para proceder a su eliminación.');
                        }
                        else
                        {
                                if(confirm('Esta seguro de eliminar los datos ya que con ello se perdera toda la informacion historica del funcionario.'))
                                {
                                        location.href='eliminar_funcionario.php?datos='+datos+'&cod_ciudad='+cod_ciudad;
                                }
                                else
                                {
                                        return(false);
                                }
                        }
                }
				function editar_nav(f, cod_ciudad)
                {
                        var i;
                        var j=0;
                        var j_contacto;
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       j_contacto=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j>1)
                        {       alert('Debe seleccionar solamente un funcionario para editar sus datos.');
                        }
                        else
                        {
                                if(j==0)
                                {
                                        alert('Debe seleccionar un funcionario para editar sus datos.');
                                }
                                else
                                {
                                        location.href='editar_funcionarios.php?j_funcionario='+j_contacto+'&cod_ciudad='+cod_ciudad;
                                }
                        }
                }
                function cambiar_vista(sel_vista, f)
                {
                        var modo_vista;
                        modo_vista=sel_vista.value;
                        location.href='navegador_funcionarios.php?cod_ciudad=$cod_ciudad&vista='+modo_vista+'';
                }
                </script>";
        require("conexion.inc");
		require("estilos_almacenes.inc");
		
		$cod_ciudad=$_GET['cod_ciudad'];
		
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_ciudad";
                $resp_cab=mysql_query($sql_cab);
                $dat_cab=mysql_fetch_array($resp_cab);
                $nombre_ciudad=$dat_cab[0];
        echo "<form method='post' action=''>";
        //esta parte saca el ciclo activo
        $sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, f.celular,f.email,
		ci.descripcion,f.estado
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' and f.estado='1' order by c.cargo,f.paterno";

		$resp=mysql_query($sql);
        echo "<h1>Registro de Funcionarios<br>Territorio $nombre_ciudad</h1>";
        
		echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Funcionarios Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";

        echo "<center><table class='texto'>";
		echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Cargo</th><th>Nombre</th>
				<th>E-mail</th><th>Celular</th><th>Alta en sistema</th>
				<th>Dar Alta</th><th>Restablecer Clave</th></tr>";
        $indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
    {
		$codigo=$dat[0];
		$cargo=$dat[1];
		$paterno=$dat[2];
		$materno=$dat[3];
		$nombre=$dat[4];
		$nombre_f="$paterno $materno $nombre";
		$fecha_nac=$dat[5];
		$direccion=$dat[6];
		$telf=$dat[7];
		$cel=$dat[8];
		$email=$dat[9];
		$ciudad=$dat[10];
		$estado=$dat[11];

		$sql_alta_sistema="select * from usuarios_sistema where codigo_funcionario='$codigo'";
		$resp_alta_sistema=mysql_query($sql_alta_sistema);
		$filas_alta=mysql_num_rows($resp_alta_sistema);
		if($estado==0)
		{	$alta_sistema="<img src='imagenes/no2.png' width='40'>";
				$dar_alta="-";
				$restablecer="-";
				$agenciasFuncionario="-";
		}
		if($estado==1)
		{	if($filas_alta==0)
				{
						$alta_sistema="<img src='imagenes/no.png' width='40'>";  
						$dar_alta="<a href='alta_funcionario_sistema.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>
						<img src='imagenes/go2.png' width='40'></a>";
				}
				else
				{
						$alta_sistema="<img src='imagenes/si.png' width='40'>";
						$dar_alta="-";
						$restablecer="<a href='restablecer_contrasena.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>
						<img src='imagenes/go2.png' width='40'></a>";
				}
		}

	   

		echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td>
			<td align='center'><input type='checkbox' name='cod_contacto' value='$codigo'></td>
				<td>&nbsp;$cargo</td><td>$nombre_f</td>
			<td align='left'>&nbsp;$email</td><td align='left'>&nbsp;$cel</td>
			<td align='center'>$alta_sistema</td>
			<td align='center'>$dar_alta</td>
			<td align='center'>$restablecer</td></tr>";
		$indice_tabla++;
	}
		
		echo "</table></center><br>";
		
        echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav($cod_ciudad)'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form, $cod_ciudad)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form, $cod_ciudad)'>
		</div>";
		
        echo "</form>";
?>

