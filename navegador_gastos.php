<html>

<script>
function enviar_nav()
{   location.href='registrar_gasto.php';
}

function anular_gasto(f)
{   
	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {	location.href='anular_gasto.php?codigo_registro='+j_cod_registro;
        }
    }
}
        </script>
    </head>
    <body>
<form method='post' name='form1' action=''>
<?php

require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require('home_almacen.php');
require('funciones.php');

$globalCiudad=$_COOKIE["global_agencia"];

$fechaHoy=date("d/m/Y");

echo "<h1>Registro de Gastos</h1>";
echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Gastos Anulados</th><td bgcolor='#ff8080' width='10%'></td>
</tr></table><br>";

    echo "<div class='divBotones'>
	<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'></td>
	<td><input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'></div>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nro. Gasto</th><th>Fecha</th><th>Tipo</th>
		<th>Descripcion</th><th>Monto [Bs]</th><th>&nbsp;</th></tr>";
	
	$consulta = "select g.cod_gasto, g.descripcion_gasto, 
		(select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, 
		DATE_FORMAT(g.fecha_gasto, '%d/%m/%Y'), monto, estado from gastos g where cod_ciudad='$globalCiudad' order by g.fecha_gasto desc";
		
	$resp = mysqli_query($enlaceCon,$consulta);

	while ($dat = mysqli_fetch_array($resp)) {
		$codGasto = $dat[0];
		$descripcionGasto= $dat[1];
		$tipoGasto=$dat[2];
		$fechaGasto = $dat[3];
		$montoGasto = $dat[4];
		$codEstado=$dat[5];
		
		$montoGasto=redondear2($montoGasto);
		
		if ($codEstado == 2) {
			$color_fondo = "#ff8080";
			$chkbox = "";
		}else {
			$color_fondo = "";
			if($fechaGasto==$fechaHoy){
				$chkbox = "<input type='checkbox' name='codigo' value='$codGasto'>";
			}else{
				$chkbox = "<input type='checkbox' name='codigo' value='$codGasto'>";
			}
		}
		
		echo "<tr>
		<td align='center'>$chkbox</td>
		<td align='center'>$codGasto</td>
		<td align='center'>$fechaGasto</td>
		<td align='center'>$tipoGasto</td>
		<td align='center'>$descripcionGasto</td>
		<td align='center'>$montoGasto</td>
		<td align='center' bgcolor='$color_fondo'>&nbsp;</td>
		</tr>";
	}
	echo "</table></center>";
	
   echo "<div class='divBotones'>
	<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'></td>
	<td><input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'>
	</div>";
	
	echo "</form>";
?>
    </body>
</html>
