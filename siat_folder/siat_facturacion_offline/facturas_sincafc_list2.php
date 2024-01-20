<script type="text/javascript">
 function activar_input_salida_almacen(index){
  var check=document.getElementById("factura_seleccionada"+index);
  if(check.checked){
    document.getElementById("factura_seleccionada_s"+index).value=1;
  }else{
    document.getElementById("factura_seleccionada_s"+index).value=0;
  }
}
function activar_input_salida_almacen_all(){
  var contador=document.getElementById("contador_items").value;
  var check=document.getElementById("factura_seleccionada_all");
  for (var index = 1; index <= contador; index++) {
    if(check.checked){
      // console.log("deseleccinado");
      document.getElementById("factura_seleccionada_s"+index).value=1;
      document.getElementById("factura_seleccionada"+index).checked=true;
    }else{
      // console.log("Seleccionado");
      document.getElementById("factura_seleccionada_s"+index).value=0;
      document.getElementById("factura_seleccionada"+index).checked=false; 
    }
  }
}

function verificacionEventos(cod_ciudad,stringfechas){
  $('.cargar-ajax').removeClass('d-none');
  $('#texto_ajax_titulo').html('Procesando datos..');
  var parametros={"cod_ciudad":cod_ciudad,"fecha":stringfechas,"sw":1};
  $.ajax({
        type: "GET",
        dataType: 'html',
        url: "consultaEvento.php",
        data: parametros,
        success:  function (resp) {
          if(resp.trim()=="1"){
            $('.cargar-ajax').addClass('d-none');
            Swal.fire({
              title: 'CORRECTO',
              html:'Proceso terminado correctamente.',
              type: 'success'
            }).then(function() {
                //location.href='facturas_sincafc_list.php?rpt_territorio='+cod_ciudad;
            });

          }else{
            $('.cargar-ajax').addClass('d-none');
            Swal.fire({
              title: 'EL PROCESO TUVO UN ERROR',
              html:'<b>Error de conexión SIAT</b>',
              type: 'error'
            }).then(function() {
                //location.href='facturas_sincafc_list.php?rpt_territorio='+cod_ciudad;
            });
          }
          
        }
    }); 
}
function envioOfflineEvento(cod_ciudad,stringfechas){

  var cod_motivo=document.getElementById('cod_motivo').value;

  $('.cargar-ajax').removeClass('d-none');
  $('#texto_ajax_titulo').html('Procesando datos..');
  var parametros={"cod_ciudad":cod_ciudad,"fecha":stringfechas,"cod_motivo":cod_motivo};
  $.ajax({
        type: "GET",
        dataType: 'html',
        url: "facturas_sincafc_save_evento.php",
        data: parametros,
        success:  function (resp) {
          if(resp.trim()!="0"){
            $('.cargar-ajax').addClass('d-none');
            Swal.fire({
              title: 'CORRECTO',
              html:'Proceso terminado correctamente.',
              type: 'success'
            }).then(function() {
                location.href='facturas_sincafc_list2.php?rpt_territorio='+cod_ciudad;
            });

          }else{
            $('.cargar-ajax').addClass('d-none');
            Swal.fire({
              title: 'EL PROCESO TUVO UN ERROR',
              html:'<b>Error de conexión SIAT</b>',
              type: 'error'
            }).then(function() {
                location.href='facturas_sincafc_list2.php?rpt_territorio='+cod_ciudad;
            });
          }
          
        }
    }); 
}

</script>

<?php //ESTADO FINALIZADO
require("../../conexionmysqli.inc");
if(isset($_GET['rpt_territorio'])){
  $rpt_territorio=$_GET['rpt_territorio'];
  $sqladd=" and a.cod_ciudad in ($rpt_territorio)"; 
}else{
  $rpt_territorio=0;
  if(isset($_COOKIE['globalIdEntidad'])){
    $entidad=$_COOKIE['globalIdEntidad'];
    $sqladd=" and a.cod_ciudad in (select cod_ciudad from ciudades where cod_entidad='$entidad')"; 
  }else{
    $sqladd=" ";   
  }
}

$add_check="checked";
$add_check_sw=1;

$fecha_sw=0;
$fecha="";
if(isset($_GET['fecha'])){
  $fecha=$_GET['fecha'];
  $fecha_sw=1;
  $sqladd.=" and s.fecha='$fecha'";
}

?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon">
            <div class="card-icon bg-blanco">
              <img class="" width="40" height="40" src="../../imagenes/icon_farma.png">
            </div>
            <h4 class="card-title text-center">FACTURAS EMISION OFFLINE <br></h4>            
            <div class="row">
            </div> 
          </div>
          <form class="" action="facturas_sincafc_save.php" method="POST" onsubmit="return valida(this)" enctype="multipart/form-data">
            <input type="hidden" name="rpt_territorio" value="<?=$rpt_territorio?>">
            <input type="hidden" name="fecha" value="<?=$fecha?>">
            <input type="hidden" name="fecha_sw" value="<?=$fecha_sw?>">
          <div class="card-body">
            <div class="row">
              <label class="col-sm-1 col-form-label">Marcar Todo </label>
              <div class="col-sm-1">
                <div class="form-group">
                  <input type="checkbox"  data-toggle="toggle" onchange="activar_input_salida_almacen_all()" id="factura_seleccionada_all" name="factura_seleccionada_all" <?=$add_check?>>
                </div>
              </div>
              <label class="col-sm-1 col-form-label">Motivo </label>
              <div class="col-sm-3">
                <div class="form-group">
                    <select id="cod_motivo" name="cod_motivo" class="selectpicker form-control " data-style="btn btn-primary" data-show-subtext="true" data-live-search="true" required="true">
                    <?php
                    $sql="select codigo,descripcion from  siat_tipos_evento where cod_estadoreferencial=1 and bandera=1";
                      $resp=mysqli_query($enlaceCon,$sql);
                      while($row=mysqli_fetch_array($resp)){ 
                     ?>
                      <option  value="<?=$row["codigo"];?>"><?=$row["descripcion"];?></option>
                    <?php } ?> 
                    </select>
                </div>
              </div>
              <?php 
              if($_COOKIE['global_usuario']==-1){
                ?><label class="col-sm-2 col-form-label">Obtener Nuevo CUFD</label>
                <div class="col-sm-1">
                  <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" id="nuevo_cufd" name="nuevo_cufd[]" value="1">
                          <span class="form-check-sign">
                            <span class="check"></span>
                          </span>
                        </label>
                      </div>
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">Obtener Nuevo CUF</label>
                <div class="col-sm-1">
                  <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" id="nuevo_cuf" name="nuevo_cuf[]" value="1">
                          <span class="form-check-sign">
                            <span class="check"></span>
                          </span>
                        </label>
                      </div>
                  </div>
                </div><?php
              }
              ?>
            </div>
            <div class="table-responsive">
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>
                  <tr>
                    <tr class='bg-info text-white'><th></th><th>&nbsp;</th><th>Sucursal</th><th>Nro. Factura</th><th>Fecha Emisión<br></th>
    				      <th>Cliente</th><th>Razon Social</th><th>NIT</th><th>Proceso</th><th>Monto</th></tr>
                  </tr>                                  
                </thead>
                <tbody>
                  <?php
                  $index=0;
                  $cod_tipoEmision=2;//tipo emision OFFLINE
                   $sql="SELECT s.cod_salida_almacenes,a.nombre_almacen as sucursal, s.fecha, s.hora_salida, s.nro_correlativo,  
    					  (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente)cliente, s.cod_tipo_doc, razon_social, nit,s.cod_tipopago,s.monto_final,s.siat_codigotipoemision,a.cod_ciudad
    					  FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen
    					  WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
    						and s.siat_codigotipoemision=$cod_tipoEmision and s.siat_codigoRecepcion is null $sqladd
    						order by s.fecha,a.nombre_almacen,s.nro_correlativo";
                  // echo $sql;
                  $resp=mysqli_query($enlaceCon,$sql);
                  $stringFechas="";
                  $fechaAux="";
                  while($row=mysqli_fetch_array($resp)){ 
                    // echo "***";
                    $cod_salida_almacenes=$row['cod_salida_almacenes'];
                    $sucursal=$row['sucursal'];
                    $fecha=$row['fecha'];
                    if($fechaAux<>$fecha){
                      $fechaAux=$fecha;
                      $stringFechas.=$fechaAux.",";
                    }
                    $hora_salida=$row['hora_salida'];
                    $nro_correlativo=$row['nro_correlativo'];
                    $cliente=$row['cliente'];
                    // $cod_tipo_doc=$row['cod_tipo_doc'];
                    $razon_social=$row['razon_social'];
                    $nit=$row['nit'];
                    $cod_tipopago=$row['cod_tipopago'];
                    $monto_final=$row['monto_final'];
                    $cod_tipoEmision=$row['siat_codigotipoemision'];
                    $cod_ciudad=$row['cod_ciudad'];
                      $index++;
                      ?>
                    <tr>
                      <td class="td-actions text-right">
                      <input type="hidden" id="factura_seleccionada_s<?=$index?>" name="factura_seleccionada_s<?=$index?>"  value="<?=$add_check_sw?>">
                      <input type="hidden" id="cod_salida_almacenes<?=$index?>" name="cod_salida_almacenes<?=$index?>"  value="<?=$cod_salida_almacenes?>">
                      <input type="checkbox"  data-toggle="toggle" title="Seleccionar" id="factura_seleccionada<?=$index?>" name="factura_seleccionada<?=$index?>" onchange="activar_input_salida_almacen(<?=$index?>)" <?=$add_check?>>
                      </td>
                      <td class="text-center small"><?=$index;?></td>
                      <td class="text-left small"><?=$sucursal;?> <?php if($_COOKIE['global_usuario']==-1){?> ( <?=$cod_ciudad?> )<?php }?></td>
                      <td class="text-center small"><?=$nro_correlativo;?></td>
                      <td class="text-left small"><?=$fecha;?> <?=$hora_salida;?></td>
                      <td class="text-left small"><?=$cliente;?></td>
                      <td class="text-center small"><?=$razon_social;?></td>
                      <td class="text-left small"><?=$nit;?></td>
                      <td class="text-right small"><?=$cod_salida_almacenes;?></td>
                      <td class="text-right small"><?=number_format($monto_final,1,'.',',');?></td>
                    </tr>
                    <?php
                  }
                  $stringFechas=trim($stringFechas,",");
                  ?>
                </tbody>
              </table>
              <input type="hidden" name="contador_items" id="contador_items" value="<?=$index?>">            
            </div>
          </div>
          <div class="card-footer fixed-bottom">
            <?php 
            if($rpt_territorio>0){//solo para sucursales ?>
              <a type="button" href="#" class="btn btn-default btn-sm" onclick="verificacionEventos('<?=$rpt_territorio?>','<?=$stringFechas?>');return false;"><span class="material-icons">person_search</span>PASO 1 (consulta Evento)</a>
              <a type="button" href="#" class="btn btn-warning btn-sm" onclick="envioOfflineEvento('<?=$rpt_territorio?>','<?=$stringFechas?>');return false;"><span class="material-icons">person_search</span>PASO 2 (Envío con Evento)</a>
              <button type="submit" class="btn btn-rose btn-sm">PASO 3 (Enviar Facturas)</button>
              <a type="button" href="facturas_sincafc_list_all.php?rpt_territorio=<?=$rpt_territorio?>" class="btn btn-info btn-sm" target="_blank">Paso 4 (Enviar Facturas 1X1)</a>
            <?php }else{?>
              <button type="submit" class="btn btn-rose btn-sm">Enviar Facturas</button>
            <?php }
            ?>
            

          </div>
          </form>

        </div>
      </div>
    </div>  
  </div>
</div>


<!-- small modal -->
<div class="modal fade modal-primary" id="modalAsignarNit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
     <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
          <i class="material-icons">person_search</i>
        </div>
        <h4 class="card-title text-rose font-weight-bold">Eventos Encontrados</h4>
        <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
          <i class="material-icons">close</i>
        </button>
      </div>
      <div class="card-body">
        <table class="table table-sm table-condensed table-bordered"><thead><tr><th class="bg-info">Razón Social</th><th class="bg-info">NIT</th><th class="bg-info">-</th></tr></thead>
        <tbody id="lista_nits"></tbody></table>
      </div>
    </div>  
  </div>
</div>

<!--    end small modal -->


<script type="text/javascript">
    function valida(f) {
        var ok = true;
            // Swal.fire("Informativo!","PROCESANDO..", "warning");
            $(".cargar-ajax").removeClass("d-none");
            $("#texto_ajax_titulo").html("Procesando datos..");
        return ok;
    }
</script>