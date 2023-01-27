<?php

require("conexionmysqli.php");
require('function_formatofecha.php');

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
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introduzca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}
function enviar_nav(){   
	location.href='registrarOC.php';
}
function anular_ingreso(f){
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
        {   if(f.fecha_sistema.value==fecha_registro)
            {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                });
            }
            else
            {   alert('Usted no esta autorizado(a) para anular el ingreso.');
                return(false);
            }
        }
    }
}
        </script>
    </head>
    <body>


<?php


$txtnroingreso = $_GET["txtnroingreso"];
$fecha1 = $_GET["fecha1"];
$fecha2 = $_GET["fecha2"];

$txtnroingreso = str_replace("'", "''", $txtnroingreso);
$fecha1 = str_replace("'", "''", $fecha1);
$fecha2 = str_replace("'", "''", $fecha2);

require("estilos_almacenes.inc");

echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

$consulta = "select o.`nro_orden`, p.`nombre_proveedor`, 
	(select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=o.tipo_pago) as tipopago,
	o.`cod_estado`, o.`fecha_orden`, o.`observaciones`,
	(select e.nombre_estado from estados_oc e where e.cod_estado=o.cod_estado),
	o.fecha_vencimiento, monto_orden, monto_cancelado, o.orden_propia, o.nro_factura
	from `orden_compra` o, `proveedores` p where o.`cod_proveedor`=p.`cod_proveedor` 
	ORDER BY o.nro_orden DESC limit 0, 50 ";
	
$resp = mysqli_query($enlaceCon,$consulta);
echo "<h1>Ordenes de Compra</h1>";

echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th>
<th>OC Anuladas</th><td bgcolor='#ff8080' width='10%'></td>
<th>OC Canceladas</th><td bgcolor='#58FA58' width='10%'></td>
<th>OC Normales</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
require('home_almacen.php');

    echo "<div class='divBotones'><input type='button' value='Registrar OC' name='adicionar' class='boton' onclick='enviar_nav()'></td>
		<td><input type='button' value='Editar OC' class='boton' onclick='editar_ingreso(this.form)'></td>
		<td><input type='button' value='Anular OC' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
		</div>";

	echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. OC</th><th>Proveedor</th><th>Tipo de Pago</th><th>Fecha</th><th>Observaciones</th>
<th>Estado</th><th>Nro Documento</th><th>Monto OC Bs.</th><th>&nbsp;</th></tr>";
while ($dat = mysqli_fetch_array($resp)) {
    $nroOC = $dat[0];
    $nombreProveedor = $dat[1];
	$nombrePago=$dat[2];
	$codEstado=$dat[3];
    $fechaOC = $dat[4];
    $obsOC = $dat[5];
	$estadoOC=$dat[6];
	$fechaVencimiento=$dat[7];
	$montoOC=$dat[8];
	$montoOCDol=$montoOC/6.96;
	$montoCancelado=$dat[9];
	$ordenPropia=$dat[10];
	$nroDoc=$dat[11];
	
	$color_fondo = "";
        
	$saldo=$montoOC-$montoCancelado;
	if($saldo==0){
		$color_fondo = "#58fa58";
	}
    if ($codEstado == 2) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }else {
        $chkbox = "<input type='checkbox' name='codigo' value='$nroOC'>";
    }
    echo "<tr>
	<td align='center'>$chkbox</td><td align='center'>$nroOC</td><td align='center'>$nombreProveedor</td>
	<td align='center'>$nombrePago</td>
	<td align='center'>$fechaOC</td><td>&nbsp;$obsOC</td>
	<td align='center'>$estadoOC</td>
	<td>$nroDoc</td>
	<td>$montoOC</td>
	<td align='center' bgcolor='$color_fondo'>
	<a target='_BLANK' href='detalleOC.php?codigo_orden=$nroOC'>
	<img src='imagenes/detalles.png' border='0' title='Ver Detalles de la OC' width='40'></a></td></tr>";
}
echo "</table></center><br>";
require('home_almacen.php');
        echo "<div class='divBotones'><input type='button' value='Registrar OC' name='adicionar' class='boton' onclick='enviar_nav()'></td>
		<td><input type='button' value='Editar OC' class='boton' onclick='editar_ingreso(this.form)'></td>
		<td><input type='button' value='Anular OC' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
		</div>";

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
