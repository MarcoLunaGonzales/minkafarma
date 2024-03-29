<?php
require "../../conexionmysqli.php";
require "../funciones_siat.php";

$fechaHoraActual=date('Y-m-d\TH:i:s.v', time());

$nroAbiertos=obtenerCantidadPuntosVenta(1);
$nroCerrados=obtenerCantidadPuntosVenta(0);

$fechaActual=date("Y-m-d");
$anioActual=date("Y");
?>
<script type="text/javascript">
  function generarCUIS(ciudad){
   Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
   window.location.href='generar_cuis.php?cod_ciudad='+ciudad;
}
function generarCUFD(ciudad){
   Swal.fire({
        title: '¿Esta seguro de generar Nuevo CUFD?',
        text: "Se procederá con la generación de un Nuevo Cufd",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Si, Generar',
        cancelButtonText: 'No',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
            window.location.href='generar_cufd.php?cod_ciudad='+ciudad;                           
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
}

function sincronizarCufdCuis(){
   Swal.fire({
        title: '¿Esta seguro de generar los CUFD?',
        text: "Se procederá con la generación de Cufd para cada sucursal",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Si, Generar',
        cancelButtonText: 'No',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
            window.location.href='generar_cuis_cufd_masivo.php';                           
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
                  <h4 class="card-title">CUIS y CUFD - Sucursales(SIAT)</h4>
                  <hr>
                  <h5 class="text-dark">Sucursales Abiertas <b class="text-muted">[<?=$nroAbiertos?>]</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sucursales Cerradas <b class="text-danger">[<?=$nroCerrados?>]</b> <a href="#" onclick="sincronizarCufdCuis();return false;" class="btn btn-warning btn-sm btn-fab float-right" title="ACTUALIZAR CUIS Y CUFD"><i class="material-icons">compare_arrows</i></a></h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-condensed small">
                      <thead class="fondo-boton">
                        <tr class="bg-primary text-white">
                          <!-- <th align="center">Codigo</th> -->
                          <th align="center">Sucursal</th>                          
                          <th align="center">Codigo Impuestos</th>
                          <th align="center">Codigo Punto Venta</th>
                          <th align="center" width="25%">CUIS</th>
                          <th align="center" width="25%">CUFD</th>
                          <!-- <th align="center">Estado</th> -->
                          <th width="20%" align="center">Opción</th>
                        </tr>
                      </thead>
                      <tbody>
                           <?php
                        $sql="SELECT c.cod_ciudad,c.nombre_ciudad,c.direccion,c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta,(SELECT cuis FROM siat_cuis where cod_gestion='$anioActual' and cod_ciudad=c.cod_ciudad and estado=1 limit 1)cuis,(SELECT cufd FROM siat_cufd where fecha='$fechaActual' and cod_ciudad=c.cod_ciudad and estado=1 limit 1)cufd  from ciudades c where c.cod_impuestos>=0 having codigoPuntoVenta>0 order by c.cod_ciudad;";
                        //echo $sql;
                        $resp=mysqli_query($enlaceCon,$sql);
                        while($dat=mysqli_fetch_array($resp)){
                          $codigo=$dat[0];
                          $descripcion=$dat[1];
                          $direccion=$dat[2];
                          $cod_impuestos=$dat[3];
                          $codigoPuntoVenta=$dat[4];
                          $cuis=$dat[5];
                          $cufd=$dat[6];

                          // if($codigoPuntoVenta>0){
                          //   $estadoList="<a href='#' class='btn btn-sm btn-success'>Sucursal Abierta!</a>";
                          //   $botonPuntoVenta='<a href="#" onclick="cerrarPuntoVenta('.$codigo.');return false;" class=" btn btn-sm btn-default" title="OBTENER CUIS"><i class="material-icons">compare_arrows</i> CUIS</a>';
                          // }else{
                          //   $estadoList="<a href='#' class='btn btn-sm btn-danger'>Sucursal Cerrada!</a>";
                          //   $botonPuntoVenta='<ahref="#" onclick="abrirPuntoVenta('.$codigo.');return false;" class=" btn btn-sm btn-warning" title="ABRIR PUNTO VENTA"><i class="material-icons">meeting_room</i> ABRIR</a>';
                          // }

                          if($cuis==""){
                            $cuis="No registrado en la base local";
                          }  

                          if($cufd==""){
                            $cufd="No registrado en la base local";
                          }  

                          $botonPuntoVenta='<div class="btn-group"><a href="#" onclick="generarCUIS('.$codigo.');return false;" class=" btn btn-sm btn-default" title="OBTENER CUIS"><i class="material-icons">compare_arrows</i> CUIS</a><a href="#" onclick="generarCUFD('.$codigo.');return false;" class=" btn btn-sm btn-info" title="OBTENER CUFD"><i class="material-icons">compare_arrows</i> CUFD</a>
                            <a href="../../siat_folder/siat_facturacion_offline/facturas_sincafc_list2.php?rpt_territorio='.$codigo.'" target="_blank" class="btn btn-sm btn-rose" style="background: #007568;"">Facturas OFFLINE</a>
                          </div>';


                          
                          ?>
                          <tr>
                            <!-- <td class="text-left"><?=$codigo?></td> -->
                            <!-- <td class="text-left"><?=$descripcion?></td> -->
                            <td class="text-left"><?=$descripcion?></td>
                            <td class="text-left"><?=$cod_impuestos?></td>                            
                            <td class="text-left"><?=$codigoPuntoVenta?></td>
                            <td class="text-left"><?=$cuis?></td>
                            <td class="text-left"><?=$cufd?></td>
                            <!-- <td class="text-left"><?=$estadoList?></td> -->
                            <td>
                             <?=$botonPuntoVenta?>
                            </td>
                          </tr>  
                        <?php 
                      } ?>

                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <a href="#" onclick="sincronizarCufdCuis();return false;" class="btn btn-warning"><i class="material-icons">compare_arrows</i> Actualizar Cuis y Cufd</a>
                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

