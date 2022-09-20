<?php
require("conexionmysqli.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require("funciones.php");

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
		
function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
		
function funOk(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmConfirmarCodigoIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introducir el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}
function ajaxBuscarIngresos(f){
	var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	notaIngreso=document.getElementById("notaIngreso").value;
	global_almacen=document.getElementById("global_almacen").value;
	provBusqueda=document.getElementById("provBusqueda").value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNavIngresos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&notaIngreso="+notaIngreso+"&global_almacen="+global_almacen+"&provBusqueda="+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function enviar_nav()
{   location.href='registrar_ingresomateriales.php';
}
function editar_ingreso(f)
{   var i;
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
        {      //location.href='editar_ingresomateriales.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                funOk(j_cod_registro,function(){
                    location.href='editar_ingreso.php?codIngreso='+j_cod_registro+'';
                });
        }
    }
}
function anular_ingreso(f)
{   var i;
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
        {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    location.href='anular_ingreso.php?codigo_registro='+j_cod_registro+'';
                });
        }
    }
}

function anular_ingreso2(f)
{   var i;
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
        else{
			if(confirm('Esta seguro de anular?')){
                    location.href='anular_ingreso.php?codigo_registro='+j_cod_registro+'';
			};
        }
    }
}

        </script>
    </head>
    <body>

<?php
 
echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

//SACAMOS LA CONFIGURACION PARA LA ANULACION
$anulacionCodigo=1;
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf = mysqli_fetch_array($respConf);
$anulacionCodigo = $datConf[0];
//$anulacionCodigo=mysql_result($respConf,0,0);


$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso
    AND i.cod_almacen='$global_almacen'";
   $consulta = $consulta."ORDER BY i.nro_correlativo DESC limit 0, 50 ";
//echo "MAT:$sql";
$resp = mysqli_query($enlaceCon,$consulta);
echo "<h1>Ingreso de Materiales</h1>";

echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>Ingresos con movimiento</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos sin movimiento</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

//<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'>";
if($anulacionCodigo==1){
	echo "<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>";	
}else{
	echo "<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso2(this.form)'>";
}
echo"<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";

echo "<div id='divCuerpo'>";
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Numero Ingreso</th><th>Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th><th>&nbsp;</th></tr>";
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	
	$anio_ingreso=intval("$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]");
	$globalGestionActual=intval($_COOKIE["globalGestion"]);

	//echo "anioingreso: ".$anio_ingreso." globalGestion: ".$globalGestionActual."-";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];


    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
                where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento != 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 && $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
	//if ($anio_ingreso != $globalGestionActual) {
    if ($anio_ingreso != 2022 ) {
        $chkbox = "";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso $anio_ingreso $globalGestionActual</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td><td align='center'>
	<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/icon_detail.png' border='0' width='30' heigth='30' alt='Ver Detalles del Ingreso'></a></td></tr>";
}
echo "</table></center><br>";
echo "</div>";

//<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'>";
if($anulacionCodigo==1){
	echo "<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>";	
}else{
	echo "<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso2(this.form)'>";
}

echo "<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ingresos</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nota de Ingreso</td>
				<td>
				<input type='text' name='notaIngreso' id="notaIngreso" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Proveedor:</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlProv="select cod_proveedor, nombre_proveedor from proveedores order by 2";
						$respProv=mysqli_query($enlaceCon,$sqlProv);
						while($datProv=mysqli_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarIngresos(this.form)">
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenBuscar();">
			
		</center>
	</div>
</div>

        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
    </body>
</html>
