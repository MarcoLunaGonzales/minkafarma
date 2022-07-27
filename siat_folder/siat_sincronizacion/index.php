<?php
require "../../conexionmysqli.inc";
require "../funciones_siat.php";

$fechaHoraActual=date('Y-m-d\TH:i:s.v', time());


$fechaHoraActualSiat=obtenerFechaHoraSiat();
?>
<script type="text/javascript">
  function sincronizarParametros(act){
   Swal.fire({
        title: '¿Esta seguro de sincronizar?',
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
              window.location.href='sincronizar_parametros.php?act='+act;                             
            }else{
              window.location.href='sincronizar_parametros.php';                           
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
                  <h4 class="card-title">Sincronización con Impuestos (SIAT)</h4>
                  <hr>
                  <h5 class="text-dark">Fecha-Hora Server <b class="text-muted">[<?=$fechaHoraActual?>]</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha-Hora SIAT <b class="text-danger">[<?=$fechaHoraActualSiat?>]</b>  <a href="#" onclick="sincronizarParametros('');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a></h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-condensed small">
                      <thead class="fondo-boton">
                        <tr class="bg-primary text-white">
                          <th align="center">Detalle</th>
                          <th align="center">Ult. Actualización</th>
                          <th width="10%" align="center">Opción</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                            <td class="text-left">SINCRONIZACION DE ACTIVIDADES</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarActividades')?></td>
                            <td>
                             <a href="list_sincronizarActividades.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarActividades');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE FECHA Y HORA</td>
                            <td>
                             <a href="list_sincronizarFechaHora.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>   -->

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA ACTIVIDADES DOCUMENTO SECTOR</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaActividadesDocumentoSector')?></td>
                            <td>
                             <a href="list_sincronizarListaActividadesDocumentoSector.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaActividadesDocumentoSector');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LEYENDAS FACTURA</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaLeyendasFactura')?></td>
                            <td>
                             <a href="list_sincronizarListaLeyendasFactura.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaLeyendasFactura');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA MENSAJES SERVICIOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaMensajesServicios')?></td>
                            <td>
                             <a href="list_sincronizarListaMensajesServicios.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaMensajesServicios');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>   

                          <tr>
                            <td class="text-left">SINCRONIZACION DE LISTA PRODUCTOS SERVICIOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarListaProductosServicios')?></td>
                            <td>
                             <a href="list_sincronizarListaProductosServicios.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarListaProductosServicios');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA DE EVENTOS SIGNIFICATIVOS</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaEventosSignificativos')?></td>
                            <td>
                             <a href="list_sincronizarParametricaEventosSignificativos.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaEventosSignificativos');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA MOTIVO ANULACIÓN</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaMotivoAnulacion')?></td>
                            <td>
                             <a href="list_sincronizarParametricaMotivoAnulacion.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaMotivoAnulacion');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA PAIS ORIGEN</td>
                            <td>
                             <a href="list_sincronizarParametricaPaisOrigen.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>  --> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO DOCUMENTO IDENTIDAD</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoDocumentoIdentidad')?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoDocumentoIdentidad.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoDocumentoIdentidad');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO DOCUMENTO SECTOR</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoDocumentoSector')?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoDocumentoSector.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoDocumentoSector');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO EMISION</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoEmision')?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoEmision.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoEmision');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                          <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO HABITACION</td>
                            <td>
                             <a href="list_sincronizarParametricaTipoHabitacion.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>  -->

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO METODO PAGO</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoMetodoPago')?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoMetodoPago.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoMetodoPago');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr> 

                          <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO MONEDA</td>
                            <td class="text-left"><?=ultimaHoraActualizacion('sincronizarParametricaTipoMoneda')?></td>
                            <td>
                             <a href="list_sincronizarParametricaTipoMoneda.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a><a href="#" onclick="sincronizarParametros('sincronizarParametricaTipoMoneda');return false;" class="btn btn-primary btn-sm btn-fab float-right" title="SINCRONIZAR PARAMETROS"><i class="material-icons">sync_alt</i></a>
                            </td>
                          </tr>  

                           <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPO PUNTO VENTA</td>
                            <td>
                             <a href="list_sincronizarParametricaTipoPuntoVenta.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>  --> 

                           <!-- <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA TIPOS FACTURA</td>
                            <td>
                             <a href="list_sincronizarParametricaTiposFactura.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>  --> 

                          <!--  <tr>
                            <td class="text-left">SINCRONIZACION DE PARAMETRICA UNIDAD MEDIDA</td>
                            <td>
                             <a href="list_sincronizarParametricaUnidadMedida.php" class=" btn btn-sm btn-fab btn-warning"><i class="material-icons">login</i></a>
                            </td>
                          </tr>  --> 


                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <a href="#" onclick="sincronizarParametros('');return false;" class="btn btn-primary"><i class="material-icons">sync_alt</i> Sincronizar con Impuestos</a>
                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

