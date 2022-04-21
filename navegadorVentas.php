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

	
function ShowFacturar(codVenta,numCorrelativo){
	document.getElementById("cod_venta").value=codVenta;
	document.getElementById("nro_correlativo").value=numCorrelativo;
	
	document.getElementById('divRecuadroExt2').style.visibility='visible';
	document.getElementById('divProfileData2').style.visibility='visible';
	document.getElementById('divProfileDetail2').style.visibility='visible';
}

function HiddenFacturar(){
	document.getElementById('divRecuadroExt2').style.visibility='hidden';
	document.getElementById('divProfileData2').style.visibility='hidden';
	document.getElementById('divProfileDetail2').style.visibility='hidden';
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
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introdusca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function ajaxBuscarVentas(f){
	var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, verBusqueda, global_almacen, clienteBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
	verBusqueda=document.getElementById("verBusqueda").value;
	global_almacen=document.getElementById("global_almacen").value;
	clienteBusqueda=document.getElementById("clienteBusqueda").value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxSalidaVentas.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&verBusqueda="+verBusqueda+"&global_almacen="+global_almacen+"&clienteBusqueda="+clienteBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}

function enviar_nav()
{   location.href='registrar_salidaventas.php';
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
                {   
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                }
            }
            else
            {   funOk(j_cod_registro,function(){
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                    });
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
        {   funOk(j_cod_registro,function() {
                        location.href='anular_venta.php?codigo_registro='+j_cod_registro;
            });
        }
    }
}

function anular_salida2(f)
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
        {   if(confirm('Esta seguro de anular?')) {
                        location.href='anular_venta.php?codigo_registro='+j_cod_registro;
            };
        }
    }
}

function cambiarCancelado(f)
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
    {   alert('Debe seleccionar solamente un registro.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro.');
        }
        else
		{      
			funOk(j_cod_registro,function() {
				location.href='cambiarEstadoCancelado.php?codigo_registro='+j_cod_registro+'';
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
function cambiarNoCancelado(f)
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
        {   location.href='cambiarEstadoNoCancelado.php?codigo_registro='+j_cod_registro+'';
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

function convertirNR(codFactura){
	if(confirm('Esta seguro de Anular la Factura y Convertir en NR.')){
		location.href='convertirNRAnularFac.php?codigo_registro='+codFactura;
	}else{
		return(false);
	}
}
        </script>
    </head>
    <body>
<?php

require("conexion.inc");
require('function_formatofecha.php');

$txtnroingreso = $_GET["txtnroingreso"];
$fecha1 = $_GET["fecha1"];
$fecha2 = $_GET["fecha2"];

require("estilos_almacenes.inc");

//SACAMOS LA CONFIGURACION PARA LA ANULACION
$anulacionCodigo=1;
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
$respConf=mysql_query($sqlConf);
$anulacionCodigo=mysql_result($respConf,0,0);

echo "<form method='post' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

echo "<h1>Listado de Ventas</h1>";
echo "<table class='texto' cellspacing='0' width='90%'>
<tr><th>Leyenda:</th>
<th>Ventas Registradas</th><td bgcolor='#f9e79f' width='5%'></td>
<th>Ventas Entregadas</th><td bgcolor='#1abc9c' width='5%'></td>
<th>Ventas Anuladas</th><td bgcolor='#e74c3c' width='5%'></td>
<td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
//
echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Editar' class='boton' onclick='editar_salida(this.form)'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>";		
if($anulacionCodigo==1){
	echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'>";
}else{
	echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida2(this.form)'>";	
}
echo "</div>";
		
echo "<div id='divCuerpo'>";
echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Factura</th><th>Fecha/hora<br>Registro Salida</th><th>Tipo de Salida</th>
	<th>Cliente</th><th>Razon Social</th><th>NIT</th><th>Observaciones</th><th>&nbsp;</th><th>&nbsp;</th>";
if($global_admin_cargo==1){
    echo "<th>Cambio</th><th>Convertir</th>";
}
echo "</tr>";
	
echo "<input type='hidden' name='global_almacen' value='$global_almacen' id='global_almacen'>";

$consulta = "
	SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
	(select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
	s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
	(select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit
	FROM salida_almacenes s, tipos_salida ts 
	WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 ";

if($txtnroingreso!="")
   {$consulta = $consulta."AND s.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=s.fecha AND s.fecha<='$fecha2' ";
   }
$consulta = $consulta."ORDER BY s.fecha desc, s.nro_correlativo DESC limit 0, 100 ";

//
$resp = mysql_query($consulta);
	
	
while ($dat = mysql_fetch_array($resp)) {
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
	$razonSocial=$dat[12];
	$nitCli=$dat[13];
	
	$anio_salida=intval("$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]");
	$globalGestionActual=intval($_COOKIE["globalGestion"]);
	
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
	
	$sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
	$respEstadoColor=mysql_query($sqlEstadoColor);
	$numFilasEstado=mysql_num_rows($respEstadoColor);
	if($numFilasEstado>0){
		$color_fondo=mysql_result($respEstadoColor,0,0);
	}else{
		$color_fondo="#ffffff";
	}	
	$chk = "<input type='checkbox' name='codigo' value='$codigo'>";

	if ($anio_salida != $globalGestionActual) {
        $chk = "";
    }
	
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$nombre_tiposalida</td>";
    echo "<td>&nbsp;$nombreCliente</td><td>&nbsp;$razonSocial</td><td>&nbsp;$nitCli</td><td>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
    
	/*echo "<td bgcolor='$color_fondo'><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Detalle'></a></td>";
	*/
	if($codTipoDoc==1){
		echo "<td  bgcolor='$color_fondo'><a href='formatoFactura.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
		//echo "<td  bgcolor='$color_fondo'><a href='formatoFacturaExtendido.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Extendida'></a></td>";
	}
	else{
		echo "<td  bgcolor='$color_fondo'><a href='formatoNotaRemisionOficial.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}
	
	/*echo "<td  bgcolor='$color_fondo'><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Grande'></a></td>";*/
	if($codTipoDoc==2){
		echo "<td bgcolor='$color_fondo'>
		<a href='#' onClick='ShowFacturar($codigo,$nro_correlativo);'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Convertir en Factura'></a></td>";	
	 }elseif($codTipoDoc==1){
		echo "<td align='center'>
		<a href='#' onClick='convertirNR($codigo);'>
		<img src='imagenes/restaurar2.png' width='20' border='0' title='Convertir en NR y Anular Factura'></a>
		</td>";
	 }
	echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Editar' class='boton' onclick='editar_salida(this.form)'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>";	
if($anulacionCodigo==1){
	echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'>";
}else{
	echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida2(this.form)'>";	
}
    echo "</div>";
	
echo "</form>";

?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ventas</h2>
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
				<td>Cliente:</td>
				<td>
					<select name="clienteBusqueda" class="texto" id="clienteBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlClientes="select c.`cod_cliente`, c.`nombre_cliente` from clientes c order by 2";
						$respClientes=mysql_query($sqlClientes);
						while($datClientes=mysql_fetch_array($respClientes)){
							$codCliBusqueda=$datClientes[0];
							$nombreCliBusqueda=$datClientes[1];
					?>
							<option value="<?php echo $codCliBusqueda;?>"><?php echo $nombreCliBusqueda;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			

			<tr>
				<td>Ver:</td>
				<td>
				<select name='verBusqueda' id='verBusqueda' class='texto' >
					<option value='0'>Todo</option>
					<option value='1'>No Cancelados</option>
                    <option value='2'>Anulados</option>
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

<div id="divRecuadroExt2" style="background-color:#666; position:absolute; width:800px; height: 350px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>
<div id="divProfileData2" style="background-color:#FFF; width:750px; height:300px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail2" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Convertir a Factura</h2>
		<form name="form1" id="form1" action="convertNRToFactura.php" method="POST">
		<table align='center' class='texto'>
			<tr>
				<input type="hidden" name="cod_venta" id="cod_venta" value="0">
				<td>Nro.</td>
				<td>
				<input type='text' name='nro_correlativo' id="nro_correlativo" class='texto' disabled>
				</td>
			</tr>
			<tr>
				<td>Razon Social</td>
				<td>
				<input type='text' name='razon_social_convertir' id="razon_social_convertir" class='texto' required>
				</td>
			</tr>
			<tr>
				<td>NIT</td>
				<td>
				<input type='number' name='nit_convertir' id="nit_convertir" class='texto' required>
				</td>
			</tr>
		</table>	
		<center>
			<input type='submit' value='Convertir' class='boton' >
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenFacturar()">
			
		</center>
		</form>
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
