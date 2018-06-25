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
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introdusca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}
function enviar_nav()
{   location.href='registrar_ingresomuestras.php';
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
    {   alert('Debe seleccionar solamente un registro para editarlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para editarlo.');
        }
        else
        {   if(f.fecha_sistema.value==fecha_registro)
            {   //location.href='editar_ingresomuestras.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                funOk(j_cod_registro,function(){
                    location.href='editar_ingresomuestras.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                });
            }
            else
            {   alert('Usted no esta autorizado(a) para modificar el ingreso.');
            }
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
        {   if(f.fecha_sistema.value==fecha_registro)
            {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                });
            }
            else
            {   alert('Usted no esta autorizado(a) para anular el ingreso.');
            }
        }
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

$txtnroingreso = str_replace("'", "''", $txtnroingreso);
$fecha1 = str_replace("'", "''", $fecha1);
$fecha2 = str_replace("'", "''", $fecha2);

if ($global_tipoalmacen == 1) {
    require("estilos_almacenes_central.inc");
} else {
    require("estilos_almacenes.inc");
}
echo "<form method='get' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso
    AND i.cod_almacen='$global_almacen'
    AND i.grupo_ingreso=1 ";
if($txtnroingreso!="")
   {$consulta = $consulta."AND i.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=i.fecha AND i.fecha<='$fecha2' ";
   }
$consulta = $consulta."ORDER BY i.nro_correlativo DESC limit 0, 50 ";
//echo "MUE:$sql";
$resp = mysql_query($consulta);
echo "<center><table border='0' class='textotit'><tr><th>Ingreso de Muestras</th></tr></table></center><br>";
echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>Ingresos con movimiento</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos sin movimiento</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
require('home_almacen.php');
if ($global_usuario != 1062 and $global_usuario != 1120 and $global_usuario != 1129) {
    echo "<center><table border='0' class='texto'>";
    echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'></td><td><input type='button' value='Anular Ingreso' name='adicionar' class='boton' onclick='anular_ingreso(this.form)'></td></tr></table></center>";
}
echo "<br><center><table border='1' class='texto' cellspacing='0' width='90%'>";
echo "<tr><th>&nbsp;</th><th>Numero Ingreso</th><th>Nota de Entrega</th><th>Fecha/Hora</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $bandera = 0;
    $sql_verifica_movimiento = "SELECT s.cod_salida_almacenes FROM salida_almacenes s, salida_detalle_ingreso sdi
                WHERE s.cod_salida_almacenes=sdi.cod_salida_almacen AND s.salida_anulada=0 AND sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysql_query($sql_verifica_movimiento);
    $num_filas_movimiento = mysql_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento != 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox&nbsp;</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td align='center'><a target='_BLANK' href='navegador_detalleingresomuestras.php?codigo_ingreso=$codigo'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles del Ingreso'> Detalles</a></td></tr>";
}
echo "</table></center><br>";
require('home_almacen.php');
if ($global_usuario != 1062 and $global_usuario != 1120 and $global_usuario != 1129) {
    echo "<center><table border='0' class='texto'>";
    echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form,fecha_ingreso$nro_correlativo)'></td><td><input type='button' value='Anular Ingreso' name='adicionar' class='boton' onclick='anular_ingreso(this.form)'></td></tr></table></center>";
}
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
