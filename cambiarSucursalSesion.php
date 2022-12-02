<?php


require_once 'conexionmysqli.inc';

/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/

$globalTipo=$_COOKIE['global_tipo_almacen'];
$global_agencia=$_COOKIE['global_agencia'];
?>

              <form id="form1" class="form-horizontal" action="saveSucursalSesion.php" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <CENTER><h4 class="card-title"><b>Cambiar Sucursal</b></h4></CENTER>
                </div>
                
                <div class="card-body">
                  <div class="">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Sucursales</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td align="center">                            
                               <select name="cod_ciudad" id="cod_ciudad" class="selectpicker" data-style="btn btn-info" data-show-subtext="true" data-live-search="true" required>

                              <option disabled selected value="">--SELECCIONE UNA SUCURSAL--</option>
                              <?php
                               $sql="select cod_ciudad,descripcion from ciudades order by 1";
                               $resp=mysqli_query($enlaceCon,$sql);
                               while($dat=mysqli_fetch_array($resp)){
                                 $codigo=$dat[0];
                                 $nombre=$dat[1];
                                 if($codigo==$global_agencia){
                                   echo "<option value='$codigo' selected>$nombre</option>";
                                 }else{
                                   echo "<option value='$codigo'>$nombre</option>";
                                 }
                               }

                              
                                ?>
                            </select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-body">
                    <button type="submit" class="btn btn-info">Guardar</button>                   
              </div>
               </form>
<?php

?>

