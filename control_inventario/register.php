<meta charset="utf-8">
<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require_once("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$datosHorasInventario=obtenerValorConfiguracion($enlaceCon, 36);

if($datosHorasInventario!=""){
  $horaDia=date("H:i");
    $fechaActualDia=date("Y-m-d");
    $horasDiaPermitidas=explode(",",$datosHorasInventario);
    //sort($horasDiaPermitidas);
    $indiceEncontrado=-1;
    $indiceEncontradoSiguiente=-1;
    for ($i=0; $i < count($horasDiaPermitidas) ; $i++) { 

      $hp=explode("-",$horasDiaPermitidas[$i]);
      $horaDesde=strtotime($hp[0]);
      $horaHasta=strtotime($hp[1]);
      $horaActual = strtotime($horaDia);


      if($horaActual>=$horaDesde && $horaActual<=$horaHasta){
          $indiceEncontrado=$i;
          break;
      }else if($horaHasta>$horaActual&&$indiceEncontradoSiguiente==-1){
        $indiceEncontradoSiguiente=$i;
      }

      /*if((int)$horasDiaPermitidas[$i]==(int)$horaDia){
        $indiceEncontrado=$i;
        break;
      }else if((int)$horasDiaPermitidas[$i]>(int)$horaDia&&$indiceEncontradoSiguiente==-1){
        $indiceEncontradoSiguiente=$i;
      }*/
     }  
}else{
  $indiceEncontrado=1000000;
}
//$indiceEncontrado=array_search($horaDia,$horasDiaPermitidas,false);


if($indiceEncontrado>=0){
?>
<script>
	function cambiarSubLinea(){
  var categoria=$("#rpt_categoria").val();
  var parametros={"categoria":categoria};
     $.ajax({
        type: "GET",
        dataType: 'html',
        url: "../ajaxCambiarComboLinea.php",
        data: parametros,   
        success:  function (resp) { 
        	//alert(resp);
          $("#rpt_subcategoria").html(resp);
          $(".selectpicker").selectpicker("refresh");
        }
    });
 }
 function monstrarLoad(){
 	if($("#cantidad_productos").val()>2000){
 		alert("Solo puede agregar 2000 productos como máximo!");
 		return false;
 	}else{
 	  $("#boton_envio").attr("disabled",true);
     $("#boton_envio").val("Enviando...",true);	
     return true;
 	}    
 }

 function getSelectValues(select) {
  var result = [];
  var options = select && select.options;
  var opt;
  var ii=0;
  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result[ii]=opt.value;
      ii++;
    }
  }
  return result;
}

 function calcularCantidadProductos(){
 	var lineas = getSelectValues(document.getElementById('rpt_subcategoria'));
   var parametros={"lineas":lineas};
     $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxContarProductosLineas.php",
        data: parametros,   
        success:  function (resp) { 
        	//alert(resp);
          $("#cantidad_productos").val(resp);
        }
    });
 }
</script>
<?php
$fecha_rptinidefault=date("Y")."-".date("m")."-01";
$hora_rptinidefault=date("H:i");
$fecha_rptdefault=date("Y-m-d");
echo "<form action='$urlSave' method='post' onsubmit='return monstrarLoad();'>";

echo "<h1>Registrar $moduleNameSingular</h1>";

echo "<center><table class='table table-sm' width='60%'>";

echo "<tr><td align='left' class='bg-info text-white'>Nombre</td>";
echo "<td align='left' colspan='3'>
	<input type='text' class='form-control' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td></tr>";
echo "<tr><td align='left' class='bg-primary text-white'>Productos Max(2000)</td>";
echo "<td align='left' colspan='3'>
	<input type='number' readonly class='form-control' name='cantidad_productos' id='cantidad_productos' value='0' max='2000'>
</td></tr>";
echo "<tr><td align='left' class='bg-info text-white'>A Fecha</td>";
echo "<td align='left'>
	<INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin'>
</td>";
echo "</tr>";
echo "<tr><td align='left' class='bg-info text-white'>Proveedor</td>";
echo "<td align='left'>
	<select name='rpt_categoria'  id='rpt_categoria' class='selectpicker form-control' data-style='btn btn-primary' onchange='cambiarSubLinea()' data-live-search='true' required>
	<option value='' disabled selected>--Seleccione--</option>";
	$sql="select cod_proveedor, nombre_proveedor from proveedores where cod_proveedor>0 and estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_cat=$dat[0];
		$nombre_cat=$dat[1];
		echo "<option value='$codigo_cat'>$nombre_cat</option>";
	}
	echo "</select>
</td>";
echo "</tr>";
echo "<tr><td align='left' class='bg-info text-white'>Línea</td>";
echo "<td align='left'>
	<select name='rpt_subcategoria[]' id='rpt_subcategoria' class='selectpicker form-control' multiple data-style='btn btn-primary' data-actions-box='true' data-live-search='true' onchange='calcularCantidadProductos()' required>";
	echo "</select>
</td>";
echo "</tr>";
echo "<tr><td align='left' class='bg-info text-white'>Observación</td>";
echo "<td align='left' colspan='3'>
	<input type='text' class='form-control' name='glosa_inventario' size='40'>
</td></tr>";

echo "</table></center>";

echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar' id='boton_envio'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
";

echo "</form>";
?>
<?php
mysqli_close($enlaceCon); 
}else{
  //
  ?>
  <br>
    <center>
      <div class="col-sm-8">
        <p class="text-muted">Por motivos de estabilidad en el Sistema COMERCIAL, el <b>registro de control de inventario</b> se habilitar&aacute; en los siguientes horarios</p>
        <table class="table table-sm col-sm-5 table-primary">
          <tr><th>Horas habilitadas:</th><td><?=implode(" Hrs,  ",$horasDiaPermitidas)." Hrs."?></td></tr>
        </table>
      </div>
    <br>
    <button class="btn btn-default" onclick="window.location.href='list.php'">Volver al listado</button>
    </center>
     <div class="row d-none">
        <div id="content" class="col-lg-12">
            <h5 class="text-muted">El control de Inventario se habilitar&aacute; en:</h5>
            <p><span id="new_date1"></span></p>
            <div id="countdown1"></div>
        </div>        
    </div>
<style type="text/css">
  .countdown-container {
    padding: 20px;
    background-color: #1d2a76;
    margin-bottom: 20px;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
}
 
.countdown-container .number {
    font-size: 32px;
    text-align: center;
    padding-top: 20px;
    color: #FFFFFF;
    background-color: #00ac99;
    height: 90px;
}
 
.countdown-container .concept {
    font-size: 28px;
    text-align: center;
    color: #00ac99;
}
</style>

<script type="text/javascript">
const getTime = dateTo => {
    let now = new Date(),
        time = (new Date(dateTo) - now + 1000) / 1000,
        seconds = ('0' + Math.floor(time % 60)).slice(-2),
        minutes = ('0' + Math.floor(time / 60 % 60)).slice(-2),
        hours = ('0' + Math.floor(time / 3600 % 24)).slice(-2),
        days = Math.floor(time / (3600 * 24));

    return {
        seconds,
        minutes,
        hours,
        days,
        time
    }
};

const countdown = (dateTo, element) => {
    const item = document.getElementById(element);

    const timerUpdate = setInterval( () => {
        let currenTime = getTime(dateTo);
        item.innerHTML = `
            <div class="row">
                <div class="col-lg-2">
                    <div class="countdown-container">
                        <div class="number">
                            ${currenTime.hours}
                        </div>
                        <div class="concept">
                            Horas
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="countdown-container">
                        <div class="number">
                            ${currenTime.minutes}
                        </div>
                        <div class="concept">
                            Minutos
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="countdown-container">
                        <div class="number">
                            ${currenTime.seconds}
                        </div>
                        <div class="concept">
                            Segundos
                        </div>
                    </div>
                </div>
            </div>`;

        if (currenTime.time <= 1) {
            clearInterval(timerUpdate);
            location.reload();
        }

    }, 1000);
};

</script>
  <?php 
  if(isset($horasDiaPermitidas[$indiceEncontradoSiguiente])){
      $hp=explode("-",$horasDiaPermitidas[$indiceEncontradoSiguiente]);
    ?>
    <script type="text/javascript">
      var fechaProxima="<?=$fechaActualDia?>  <?=$hp[0]?>";
      console.log("Fecha:"+fechaProxima+"");
      document.getElementById('new_date1').innerHTML = fechaProxima;
      countdown(fechaProxima, 'countdown1');
    </script>
    <?php
    
  } 
}