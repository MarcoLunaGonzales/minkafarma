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
{   location.href='registrar_salidamuestras.php';
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
                {   //location.href='editar_salidamuestras.php?codigo_registro='+j_cod_registro+'&grupo_salida=1&valor_inicial=1';
                    funOk(j_cod_registro,function(){
                        location.href='editar_salidamuestras.php?codigo_registro='+j_cod_registro+'&grupo_salida=1&valor_inicial=1';
                    });
                }
            }
            else
            {   alert('Usted no esta autorizado(a) para modificar esta salida');
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
        {   if(f.fecha_sistema.value==fecha_registro)
            {
//                if(estado_preparado==1)
//                {   alert('No puede anular esta salida porque esta en proceso de preparacion.');
//                }
//                else
                {   //location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupo_salida=1';
                    funOk(j_cod_registro,function() {
                        location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupo_salida=1';
                    });
                }
            }
            else
            {   //alert('Usted no esta autorizado(a) para modificar esta salida');
                location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupo_salida=1';
            }

        }
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
    {   location.href='preparar_despacho.php?datos='+datos+'&tipo_material=1&grupo_salida=1';
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
    {   window.open('navegador_detallesalidamuestrasResumen.php?codigo_salida='+datos+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
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
    {   location.href='registrar_datosdespacho.php?datos='+datos+'&tipo_material=1&grupo_salida=1';
    }
}
function llamar_preparado(f, estado_preparado, codigo_salida)
{   if(estado_preparado==0)
    {   if(confirm('Desea Iniciar el preparado del despacho.'))
        {   //window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'notaremision','');
            window.open('preparar_despacho.php?datos='+codigo_salida+'&tipo_material=1&grupo_salida=1&cerrar=1','popup','');
        }
        else
        {   window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'','');
        }
    }
    if(estado_preparado==1)
    {   window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'popup','');
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

if ($global_tipoalmacen == 1) {
    require("estilos_almacenes_central.inc");
} else {
    require("estilos_almacenes.inc");
}
echo "<form method='get' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
//
$consulta = "
    SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino
    FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a
    WHERE s.cod_tiposalida=ts.cod_tiposalida AND c.cod_ciudad=s.territorio_destino AND a.cod_almacen=s.cod_almacen
    AND s.cod_almacen='$global_almacen' AND s.grupo_salida=1 ";
if($txtnroingreso!="")
   {$consulta = $consulta."AND s.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=s.fecha AND s.fecha<='$fecha2' ";
   }
$consulta = $consulta."ORDER BY s.nro_correlativo DESC limit 0, 50 ";
//
$resp = mysql_query($consulta);
echo "<center><table border='0' class='textotit'><tr><th>Salida de Muestras</th></tr></table></center><br>";
echo "<table border='1' class='textomini' cellspacing='0' width='90%'><tr><th>Leyenda:</th><th>Salidas Despachadas a otras agencias</th><td bgcolor='#bbbbbb' width='5%'></td><th>Salidas recepcionadas</th><td bgcolor='#33ccff' width='5%'></td><th>Salidas Anuladas</th><td bgcolor='#ff8080' width='5%'></td><th>Salidas en proceso de despacho</th><td bgcolor='#ffff99' width='5%'></td><th>Salidas locales</th><td bgcolor='#66ff99' width='5%'></td><th>Salidas pendientes</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
require('home_almacen.php');
echo "<center><table border='0' class='texto'>";
if ($global_usuario == 1062) {
    echo "
        <tr>
        <td><input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'></td>
        <td><input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form)'></td>
        <td><input type='button' value='Anular Salida' class='boton' onclick='anular_salida(this.form)'></td>
        </tr>";
}
if ($global_usuario == 1061) {
    echo "
        <tr>
        <td><input type='button' value='Preparar Despacho' class='boton' onclick='preparar_despacho(this.form)'></td>
        <td><input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form)'></td>
        <td><input type='button' value='Imprimir en Conjunto' class='boton' onclick='imprimirNotas(this.form)'></td>
        </tr>";
}
if ($global_usuario != 1061 and $global_usuario != 1062 and $global_usuario != 1120 and $global_usuario != 1129) {
    echo "
        <tr>
        <td><input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'></td>
        <td><input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form)'></td>
        <td><input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form)'></td>
        <td><input type='button' value='Anular Salida' class='boton' onclick='anular_salida(this.form)'></td>
        </tr>";
}
echo "</table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Numero Salida</th><th>Fecha/Hora<br>Registro Salida</th><th>Tipo de Salida</th><th>Territorio<br>Destino</th><th>Almacen Destino</th><th>Funcionario Destino</th><th>Observaciones</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_ciudad = $dat[4];
    $nombre_almacen = $dat[5];
    $obs_salida = $dat[6];
    $estado_almacen = $dat[7];
    $nro_correlativo = $dat[8];
    $salida_anulada = $dat[9];
    $cod_almacen_destino = $dat[10];
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
    if ($salida_anulada == 1) {
        $color_fondo = "#ff8080";
        $chk = "&nbsp;";
    }
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    $sql_funcionario = "
        SELECT f.paterno, f.materno, f.nombres
        FROM funcionarios f, salida_detalle_visitador sv
        WHERE sv.cod_salida_almacen='$codigo' AND f.codigo_funcionario=sv.codigo_funcionario";
    $resp_funcionario = mysql_query($sql_funcionario);
    $dat_funcionario = mysql_fetch_array($resp_funcionario);
    $nombre_funcionario = "$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr bgcolor='$color_fondo'>";
    echo "<td align='center'>$chk</td>";
    echo "<td align='center'>$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>&nbsp;$nombre_almacen</td>";
    echo "<td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";
    echo "<td><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles de la Salida Interna'>Nota Remision</a></td>";
    echo "<td><a target='_BLANK' href='navegador_detallesalidaenvio.php?codigo_salida=$codigo'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles de la Salida Interna'>Detalles de envio</a></td></tr>";
}
echo "</table></center><br>";
require('home_almacen.php');
echo "<center><table border='0' class='texto'>";
if ($global_usuario == 1062) {
    echo "
        <tr>
        <td><input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'></td>
        <td><input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form)'></td>
        <td><input type='button' value='Anular Salida' class='boton' onclick='anular_salida(this.form)'></td>
        </tr>
        </table></center>";
}
if ($global_usuario == 1061) {
    echo "
        <tr>
        <td><input type='button' value='Iniciar/Parar Despacho' class='boton' onclick='preparar_despacho(this.form)'></td>
        <td><input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form)'></td>
        </tr>";
}
if ($global_usuario != 1061 and $global_usuario != 1062 and $global_usuario != 1120 and $global_usuario != 1129) {
    echo "
        <tr>
        <td><input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav()'></td>
        <td><input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form)'></td>
        <td><input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form)'></td>
        <td><input type='button' value='Anular Salida' class='boton' onclick='anular_salida(this.form)'></td>
        </tr>";
}
echo "</table></center>";
echo "</form>";

?>
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
