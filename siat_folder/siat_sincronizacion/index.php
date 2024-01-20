<?php
require "../../conexionmysqli.inc";
require "../funciones_siat.php";
$globalEntidad=$_COOKIE['globalIdEntidad'];

if($globalEntidad=="" || $globalEntidad==0){
  $globalEntidad=1;
}

$fechaHoraActual=date('Y-m-d\TH:i:s.v', time());
$fechaHoraActualSiat=obtenerFechaHoraSiat();
?>
<script type="text/javascript">
  function sincronizarParametros(act,globalEntidad){
   Swal.fire({
        title: '¿Estás seguro de sincronizar?',
        text: "Se procederá con la sincronización de parametros",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Sincronizar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
            if(act!=""){
              window.location.href='sincronizar_parametros.php?act='+act+'&cod_entidad='+globalEntidad;                             
            }else{
              window.location.href='sincronizar_parametros.php?cod_entidad='+globalEntidad;                           
            }
            
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
}

</script>
<div class="content">
  <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <form id="form1" class="form-horizontal" action="configSave.php" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">receipt</i>
                  </div>
                  <h4 class="card-title"><b>Sincronización con Impuestos (SIAT)</b></h4>
                  <hr>
                  <h5 class="text-dark">Fecha-Hora Server <b class="text-muted">[<?=$fechaHoraActual?>]</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha-Hora SIAT <b class="text-danger">[<?=$fechaHoraActualSiat?>]</b>  <a href="#" onclick="sincronizarParametros('',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a></h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-condensed small">
                      <thead class="fondo-boton">
                        <tr class='bg-dark text-white'>
                          <th align="center">Detalle</th>
                          <th align="center">Ult. Actualización</th>
                          <th width="10%" align="center">Opción</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                            <td class="text-left">SINCRONIZACION DE ACTIVIDADES</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarActividades',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarActividades.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarActividades',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE FECHA Y HORA</td>
                            <td>
                             <a href="list_sincronizarFechaHora.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>   -->

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA ACTIVIDADES DOCUMENTO SECTOR</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaActividadesDocumentoSector',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarListaActividadesDocumentoSector.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaActividadesDocumentoSector',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LEYENDAS FACTURA</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaLeyendasFactura',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarListaLeyendasFactura.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaLeyendasFactura',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA MENSAJES SERVICIOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaMensajesServicios',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarListaMensajesServicios.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaMensajesServicios',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>   

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA PRODUCTOS SERVICIOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaProductosServicios',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarListaProductosServicios.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaProductosServicios',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA DE EVENTOS SIGNIFICATIVOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaEventosSignificativos',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaEventosSignificativos.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaEventosSignificativos',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA MOTIVO ANULACIÓN</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaMotivoAnulacion',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaMotivoAnulacion.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaMotivoAnulacion',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA PAIS ORIGEN</td>
                            <td>
                             <a href="list_sincronizarParametricaPaisOrigen.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>  --> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO DOCUMENTO IDENTIDAD</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoDocumentoIdentidad',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoDocumentoIdentidad.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoDocumentoIdentidad',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO DOCUMENTO SECTOR</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoDocumentoSector',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoDocumentoSector.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoDocumentoSector',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO EMISION</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoEmision',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoEmision.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoEmision',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO HABITACION</td>
                            <td>
                             <a href="list_sincronizarParametricaTipoHabitacion.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>  -->

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO METODO PAGO</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoMetodoPago',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoMetodoPago.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoMetodoPago',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO MONEDA</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoMoneda',$globalEntidad)?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoMoneda.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoMoneda',<?=$globalEntidad?>);return false;" class="btn btn-rose btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                           <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO PUNTO VENTA</td>
                            <td>
                             <a href="list_sincronizarParametricaTipoPuntoVenta.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>  --> 

                           <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPOS FACTURA</td>
                            <td>
                             <a href="list_sincronizarParametricaTiposFactura.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>  --> 

                          <!--  <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA UNIDAD MEDIDA</td>
                            <td>
                             <a href="list_sincronizarParametricaUnidadMedida.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">list</i></a>
                            </td>
                          </tr>  --> 


                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <a href="#" onclick="sincronizarParametros('',<?=$globalEntidad?>);return false;" class="btn btn-rose"><i class="material-icons">sync_alt</i> Sincronizar Todo</a>
                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

