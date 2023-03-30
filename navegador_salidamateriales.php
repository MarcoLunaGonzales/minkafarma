<?php 
require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

?>
<html>
    <head>
        <title>Busqueda</title>
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

function ajaxBuscarVentas(f){
	var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, verBusqueda, global_almacen;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
	verBusqueda=document.getElementById("verBusqueda").value;
	global_almacen=document.getElementById("global_almacen").value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxSalidaTraspasos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&verBusqueda="+verBusqueda+"&global_almacen="+global_almacen,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}
		
function funOk(codReg,funOkConfirm)
{   $.get("programas/salidas/frmConfirmarCodigoSalida.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/salidas/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introduzca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}
function enviar_nav()
{   location.href='registrar_salidamateriales1.php';
}
function editar_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
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
        {   if(f.fecha_sistema.value==fecha_registro)
            {
//                if(estado_preparado==1)
//                {   alert('No puede modificar esta salida porque esta en proceso de preparacion');
//                }
//                else
                {   //location.href='editar_salidamateriales.php?codigo_registro='+j_cod_registro+'&grupo_salida=2&valor_inicial=1';
                    funOk(j_cod_registro,function(){
                        location.href='editar_salidamateriales.php?codigo_registro='+j_cod_registro+'&grupo_salida=2&valor_inicial=1';
                    });
                }
            }
            else
            {   alert('Usted no esta autorizado para modificar esta salida');
            }
        }
    }
}
function anular_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
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
        {   
			funOk(j_cod_registro,function() {
				location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupo_salida=2';
			});
        }
    }
}

function cambiarNoEntregado(f)
{   var i;
    var j=0;
    var j_cod_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente una Salida.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar una Salida.');
        }
        else
        {   location.href='cambiarEstadoNoEntregado.php?codigo_registro='+j_cod_registro+'';
        }
    }
}
function imprimirNotas(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para imprimir la Nota.');
    }
    else
    {   window.open('navegador_detallesalidamaterialResumen.php?codigo_salida='+datos+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
    }
}
function preparar_despacho(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para proceder a su preparado.');
    }
    else
    {   location.href='preparar_despacho.php?datos='+datos+'&tipo_material=1&grupo_salida=2';
    }
}
function enviar_datosdespacho(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para proceder al registro del despacho.');
    }
    else
    {   location.href='registrar_datosdespacho.php?datos='+datos+'&tipo_material=1&grupo_salida=2';
    }
}
function llamar_preparado(f, estado_preparado, codigo_salida)
{   window.open('navegador_detallesalidamateriales.php?codigo_salida='+codigo_salida,'popup','');
}
        </script>
    </head>
    <body>
<?php


$txtnroingreso="";
$fecha1="";
$fecha2="";
if(!isset($_GET['txtnroingreso'])){
    $txtnroingreso = $_GET["txtnroingreso"];
}
if(!isset($_GET["fecha1"])){
    $fecha1 = $_GET["fecha1"];    
}
if(!isset($_GET["fecha2"])){
    $fecha2 = $_GET["fecha2"];
}
$global_almacen=$_COOKIE['global_almacen'];

echo "<form method='post' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

//

echo "<h1>Listado de Salidas</h1>";
echo "<table border='1' class='textomini' cellspacing='0' width='90%'><tr><th>Leyenda:</th>
<th>Salidas Despachadas a otras agencias</th><td bgcolor='#bbbbbb' width='5%'></td>
<th>Salidas recepcionadas</th><td bgcolor='#33ccff' width='5%'></td>
<th>Salidas Anuladas</th><td bgcolor='#ff8080' width='5%'></td>
<th>Salidas locales</th><td bgcolor='#66ff99' width='5%'></td>
<td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
//
echo "<div class='divBotones'>
<input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
		<input type='button' value='Anular Salida' class='boton2' onclick='anular_salida(this.form)'>
</div>";

echo "<div id='divCuerpo'>";
echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Numero Salida</th><th>Fecha/hora<br>Registro Salida</th><th>Tipo de Salida</th>
	<th>Almacen Destino</th><th>Personal</th><th>Observaciones</th><th>&nbsp;</th></tr>";
	
	
//
$consulta = "
	SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
	(select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
	s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
	(select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc,
    (select concat(f.paterno, ' ',f.nombres) from funcionarios f where f.codigo_funcionario=s.cod_chofer)as personal
	FROM salida_almacenes s, tipos_salida ts 
	WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida<>1001 ";

if($txtnroingreso!="")
   {$consulta = $consulta."AND s.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=s.fecha AND s.fecha<='$fecha2' ";
   }
$consulta = $consulta."ORDER BY s.fecha desc, s.nro_correlativo DESC limit 0, 50 ";

$resp = mysqli_query($enlaceCon,$consulta);

while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
	$nombreCliente=$dat[10];
	$codTipoDoc=$dat[11];
    $nombrePersonalSalida=$dat[12];
	
	$anio_salida=intval("$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]");
	$globalGestionActual=intval($_COOKIE["globalGestion"]);
	
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
    $estado_preparado = 0;
    if ($estado_almacen == 0) {
        $color_fondo = "";
        $chk = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    //salida despachada
    if ($estado_almacen == 1) {
        $color_fondo = "#bbbbbb";
        $chk = "&nbsp;";
    }
    //salida recepcionada
    if ($estado_almacen == 2) {
        $color_fondo = "#33ccff";
        $chk = "&nbsp;";
    }
    //salida en proceso de despacho
    if ($estado_almacen == 3) {
        $color_fondo = "#ffff99";
        $chk = "<input type='checkbox' name='codigo' value='$codigo'>";
        $estado_preparado = 1;
    }
    if ($cod_almacen_destino == $global_almacen) {
        $color_fondo = "#66ff99";
        $chk = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
	if ($estado_almacen == 3) {
        $color_fondo = "#ffff99";
        $chk = "<input type='checkbox' name='codigo' value='$codigo'>";
        $estado_preparado = 1;
    }
    if ($salida_anulada == 1) {
        $color_fondo = "#ff8080";
        $chk = "&nbsp;";
    }
	
    /*if ($anio_salida != $globalGestionActual) {
        $chk = "";
    }*/
	
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr bgcolor='$color_fondo'>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$nombre_tiposalida</td><td>&nbsp;$nombre_almacen</td>";
    echo "<td>$nombrePersonalSalida</td><td>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
    echo "<td><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
		<img src='imagenes/detalles.png' border='0' title='Detalle' width='40'></a></td>";
	/*if($codTipoDoc==1){
		echo "<td><a href='formatoFactura.php?codVenta=$codigo' target='_BLANK'>Ver F.P.</a></td>";
	}else{
		echo "<td><a href='formatoNotaRemision.php?codVenta=$codigo' target='_BLANK'>Ver F.P.</a></td>";
	}
	echo "<td><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'>Imp. Formato</a></td>";*/

	echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'>
<input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
		<input type='button' value='Anular Salida' class='boton2' onclick='anular_salida(this.form)'>
</div>";


echo "</form>";

?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Salidas</h2>
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
				<td>Nro. de Documento</td>
				<td>
				<input type='text' name='nroCorrelativoBusqueda' id="nroCorrelativoBusqueda" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Ver:</td>
				<td>
				<select name='verBusqueda' id='verBusqueda' class='texto' >
					<option value='0'>Todo</option>
					<option value='1'>No Cancelados</option>
				</select>
				</td>
			</tr>			
		</table>	
		<center>
			<input type='button' value='Buscar' onClick="ajaxBuscarVentas(this.form)">
			<input type='button' value='Cancelar' onClick="HiddenBuscar()">
			
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
