<?php
require "../../conexionmysqli.inc";
require "../funciones_siat.php";

$fechaHoraActual=date('Y-m-d\TH:i:s.v', time());

?>
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
                  <h4 class="card-title">SINCRONIZACION DE LISTA PRODUCTOS SERVICIOS <a href="index.php" class="btn btn-danger float-right"><i class="material-icons">arrow_left</i>Volver</a></h4>                  
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-condensed small">
                      <thead class="fondo-boton">
                        <tr class="bg-primary text-white">
                          <th align="center">codigoActividad</th>
                          <th align="center">codigoProducto</th>
                          <th align="center">descripcionProducto</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql="SELECT codigo,codigoActividad,codigoProducto,descripcionProducto from siat_sincronizarlistaproductosservicios order by codigo;";
                        $resp=mysqli_query($enlaceCon,$sql);
                        while($dat=mysqli_fetch_array($resp)){
                          $codigo=$dat[0];
                          $codigoActividad=$dat[1];
                          $codigoProducto=$dat[2];
                          $descripcionProducto=$dat[3];
                          ?>
                          <tr>
                            <td class="text-left"><?=$codigoActividad?></td>
                            <td class="text-left"><?=$codigoProducto?></td>
                            <td class="text-left"><?=$descripcionProducto?></td>
                          </tr>  
                        <?php 
                      } ?>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <a href="index.php" class="btn btn-danger"><i class="material-icons">arrow_left</i>Volver</a>
                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

