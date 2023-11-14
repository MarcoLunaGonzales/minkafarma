<?php

require("conexionmysqli.inc");
require('funciones.php');
require('function_formatofecha.php');
require("estilos_almacenes.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

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
{   var xmlhttp=false;
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

function ajaxBuscarVentas(f){
    var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, verBusqueda, global_almacen, clienteBusqueda,vendedorBusqueda,tipoVentaBusqueda;
    fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
    fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
    nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
    global_almacen=document.getElementById("global_almacen").value;
    vendedorBusqueda=document.getElementById("vendedorBusqueda").value;
    tipoVentaBusqueda=document.getElementById("tipoVentaBusqueda").value;

    location.href="navegadorVentas2.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&vendedorBusqueda="+vendedorBusqueda+"&tipoPagoBusqueda="+tipoVentaBusqueda;
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

function cambiarTipoPago(codigo){
    $("#codigo_salida_cambio").val(codigo);
    $("#modalCambioTipoPago").modal("show");   
}

        </script>
    </head>
    <body>
<?php

$global_almacen=$_COOKIE["global_almacen"];
$estado_preparado=0;

$nroCorrelativoBusqueda="";
$fechaIniBusqueda="";
$fechaFinBusqueda="";
$vendedorBusqueda="";
$tipoPagoBusqueda="";
$fecha_sistema="";
$estado_preparado="";
$view=1;

if(isset($_GET["nroCorrelativoBusqueda"])){
    $nroCorrelativoBusqueda = $_GET["nroCorrelativoBusqueda"];    
}
if(isset($_GET["fechaIniBusqueda"])){
    $fechaIniBusqueda = $_GET["fechaIniBusqueda"];
}
if(isset($_GET["fechaFinBusqueda"])){
    $fechaFinBusqueda = $_GET["fechaFinBusqueda"];
}
if(isset($_GET["vendedorBusqueda"])){
    $vendedorBusqueda = $_GET["vendedorBusqueda"];
}
if(isset($_GET["tipoPagoBusqueda"])){
    $tipoPagoBusqueda = $_GET["tipoPagoBusqueda"];
}

echo "<form method='post' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";


echo "<input type='hidden' name='codigo_salida_cambio' name='codigo_salida_cambio' value=''>";


echo "<h1>Listado de Cotizaciones</h1>";
        
echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Doc</th><th>Fecha/hora<br>Cotizacion</th><th>Vendedor</th><th>TipoPago</th>
    <th>Nombre Cotizacion</th><th>Observaciones</th><th>Imprimir</th><th>Imprimir 2</th><th>Facturar</th></tr>";
    
echo "<input type='hidden' name='global_almacen' value='$global_almacen' id='global_almacen'>";

$consulta = "
    SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
    (select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
    s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
    (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
    (select concat(f.paterno,' ',f.nombres) from funcionarios f where f.codigo_funcionario=s.cod_chofer)as vendedor,
    (select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago
    FROM cotizaciones s, tipos_salida ts 
    WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 ";

if($nroCorrelativoBusqueda!="")
{   $consulta = $consulta."AND s.nro_correlativo='$nroCorrelativoBusqueda' ";
}
if($vendedorBusqueda!="")
{   $consulta = $consulta."AND s.cod_chofer='$vendedorBusqueda' ";
}
if($tipoPagoBusqueda!="")
{   $consulta = $consulta."AND s.cod_tipopago='$tipoPagoBusqueda' ";
}
if($fechaIniBusqueda!="" && $fechaFinBusqueda!="")
{   $consulta = $consulta."AND '$fechaIniBusqueda'<=s.fecha AND s.fecha<='$fechaFinBusqueda' ";
}   
$consulta = $consulta."ORDER BY s.fecha desc, s.hora_salida desc limit 0, 100 ";

//echo $consulta;

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
    $nombreTipoDoc=nombreTipoDoc($enlaceCon,$codTipoDoc);
    $razonSocial=$dat[12];
    $nitCli=$dat[13];
    $vendedor=$dat[14];
    $tipoPago=$dat[15];

    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
    
    $sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
    $respEstadoColor=mysqli_query($enlaceCon,$sqlEstadoColor);
    $numFilasEstado=mysqli_num_rows($respEstadoColor);
    if($numFilasEstado>0){
        $color_fondo=mysqli_result($respEstadoColor,0,0);
    }else{
        $color_fondo="#ffffff";
    }
    $chk = "<input type='checkbox' name='codigo' value='$codigo'>";

    
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>COT-$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$vendedor</td>";
    echo "<td>$tipoPago</td>";
    echo "<td>&nbsp;$razonSocial</td><td>&nbsp;$obs_salida</td>";
    
    echo "<td  bgcolor='$color_fondo'><a href='formatoCotizacionOnLine.php?codigo=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a>
        </td>";
    echo "<td  bgcolor='$color_fondo'><a href='navegadorDetalleCotizacion.php?codigo=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a>
        </td>";
    echo "<td  bgcolor='$color_fondo'><a href='registrar_salidaventas.php?codigo=$codigo' target='_BLANK'><img src='imagenes/go.png' width='30' border='0' title='Generar Factura'></a>
        </td>";
    echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";    
echo "</form>";

?>
    </body>
</html>
