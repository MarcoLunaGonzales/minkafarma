<?php
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
?>

<script>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver, rpt_cliente;
	rpt_territorio=f.rpt_territorio.value;
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	rpt_cliente=f.rpt_cliente.value;
	window.open('rptCobranzas.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_cliente='+rpt_cliente,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>

<?php

$fecha_rptdefault=date("d/m/Y");
?>

<table align='center' class='textotit'>
    <tr>
        <th>Reporte de Cobros</th>
    </tr>
</table><br>
<form method='post' action=''>
    <table class='texto' border='1' align='center' cellSpacing='0' width='50%'>
        <tr>
            <th align='left'>Territorio</th>
            <td>
                <select name='rpt_territorio' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
                    <option value=''></option>
                    <?php
                    $sql = "select cod_ciudad, descripcion from ciudades order by descripcion";
                    $resp = mysqli_query($enlaceCon, $sql);
                    while ($dat = mysqli_fetch_array($resp)) {
                        $codigo_ciudad = $dat[0];
                        $nombre_ciudad = $dat[1];
                        echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th align='left'>Proveedor</th>
            <td>
                <select name='rpt_proveedor' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
                    <option value='0'>Todos</option>
                    <?php
                    $sql = "select cod_cliente, concat(nombre_cliente,' ',paterno) from clientes order by 2";
                    $resp = mysqli_query($enlaceCon, $sql);
                    while ($dat = mysqli_fetch_array($resp)) {
                        $codigo = $dat[0];
                        $nombre = $dat[1];
                        echo "<option value='$codigo'>$nombre</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th align='left'>Fecha inicio:</th>
            <td bgcolor='#ffffff'>
                <input type='date' class='texto' value='<?php echo $fecha_rptdefault; ?>' id='exafinicial' size='10' name='exafinicial'>
            </td>
        </tr>
        <tr>
            <th align='left'>Fecha final:</th>
            <td bgcolor='#ffffff'>
                <input type='date' class='texto' value='<?php echo $fecha_rptdefault; ?>' id='exaffinal' size='10' name='exaffinal'>
            </td>
        </tr>
    </table><br>
    <center>
        <input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
    </center><br>
</form>
</div>
