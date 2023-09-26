<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="../lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="../lib/js/xlibPrototipo-v0.1.js"></script>
        <link href="../stilos.css" rel='stylesheet' type='text/css'>
        <script type='text/javascript' language='javascript'>


function funOk(codReg,funOkConfirm)
{   $.get("../programas/ingresos/frmConfirmarCodigoIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("../programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
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
{   location.href='registrarObligacion.php';
}

function anular_pago(f)
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
        {   funOk(j_cod_registro,function(){
                    location.href='anularPagoObligacion.php?codigo_registro='+j_cod_registro;
                });
        }
    }
}
        </script>
    </head>
    <body>
<form method='post' name='form1' action=''>
    <h1>Registro de Obligaciones </h1>
    <table border='1' cellspacing='0' class='textomini'>
        <tr>
            <th>Leyenda:</th>
            <th>Obligación Anulada</th>
            <td bgcolor='#ff8080' width='10%'></td>
            <th>Obligación Normal</th>
            <td bgcolor='' width='10%'>&nbsp;</td>
        </tr>
    </table><br>
    
    <div class='divBotones'>
        <input type='button' value='Registrar Pago' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Anular Pago' name='adicionar' class='boton2' onclick='anular_pago(this.form)'>
    </div>

    <br>
    <center>
        <table class='texto'>
            <tr>
                <th>#</th>
                <th>Nro. Pago</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Observaciones</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            // Aquí comienza el código PHP
            require('../conexionmysqli2.inc');
            require('../function_formatofecha.php');
            require('../home_almacen.php');
            require('../funciones.php');

            $consulta = "SELECT pp.`cod_pago`,
            pp.`fecha`,
            pp.`monto_pago`,
            pp.`observaciones`,
            pp.`cod_proveedor`,
            pp.`cod_estado`,
            pp.`cod_gestion`,
            pp.`nro_pago`,
            (SELECT pr.`nombre_proveedor` FROM proveedores pr WHERE pp.`cod_proveedor` = pr.`cod_proveedor`) AS nombre_proveedor,
            (SELECT g.`nombre_gestion` FROM gestiones g WHERE g.`cod_gestion`=pp.`cod_gestion`) AS nombre_gestion,
            pp.cod_estado
            FROM `pagos_proveedor_cab` pp
            ORDER BY pp.`cod_pago` DESC LIMIT 0, 100";

            $resp = mysqli_query($enlaceCon, $consulta);

            while ($dat = mysqli_fetch_array($resp)) {
                $codPago = $dat[0];
                $fechaPago = $dat[1];
                $observaciones = $dat[3];
                $montoPago = $dat[2];

                $montoPago = redondear2($montoPago);

                $nombreProveedor = $dat['nombre_proveedor'];
                $nroPago = $dat[7];
                $nombreGestion = $dat['nombre_gestion'];
                $codEstado = $dat[10];
                if ($codEstado == 2) {
                    $color_fondo = "style='background-color: #ff8080;'";
                    $estilo_texto = "text-decoration:line-through; color:red";
                    $chkbox = "";
                } else {
                    $color_fondo = "";
                    $estilo_texto = "";
                    $chkbox = "<input type='checkbox' name='codigo' value='$codPago'>";
                }
            ?>
            <tr style='<?=$estilo_texto;?>'>
                <td align='center'><?=$chkbox;?></td>
                <td align='center'><?=$nroPago;?></td>
                <td align='center'><?=$nombreProveedor;?></td>
                <td align='center'><?=$fechaPago;?></td>
                <td align='center'><?=$montoPago;?></td>
                <td>&nbsp;<?=$observaciones;?></td>
                <td <?=$color_fondo;?>><a href='notaObligacion.php?codPago=<?=$codPago;?>' target='_blank'><img src='../imagenes/icon_detail.png' alt='Detalle' width='30' heigth='30'></a></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </center>
    <br>
    <div class='divBotones'>
        <input type='button' value='Registrar Pago' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Anular Pago' name='adicionar' class='boton2' onclick='anular_pago(this.form)'>
    </div>
</form>

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
