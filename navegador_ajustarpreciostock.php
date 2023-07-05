<script language='JavaScript'>
    function ajustarPrecios(f){ 
      f.action="ajustarPreciosItem.php";
      f.submit();
      return(true);
    }
    function ajustarStocks(f){ 
      f.action="ajustarStocksItem.php";
      f.submit();
      return(true);
    }

</script>
<?php
require_once 'conexionmysqli.inc';

/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/

$globalTipo=$_COOKIE['global_tipo_almacen'];
$global_agencia=$_COOKIE['global_agencia'];
$globalAdmin=$_COOKIE['global_admin_cargo'];

$tituloForm="";
if($globalAdmin==1){
  $tituloForm="Ajustar Precio / Stock";
}else{
  $tituloForm="Consulta Precios";
}

?>

              <form id="form1" class="form-horizontal" action="" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <CENTER><h4 class="card-title"><b><?=$tituloForm;?></b></h4></CENTER>
                </div>
                
                <div class="card-body">
                  <div class="">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Linea</th><th>Producto</th><th>Codigo de Barras</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td align="center">                            
                               <select name="cod_proveedor" id="cod_proveedor" class="selectpicker" data-style="btn btn-info" data-show-subtext="true" data-live-search="true" required>

                              <option value="0">--SELECCIONE EL PROVEEDOR--</option>
                              <?php
                               $sql="SELECT p.cod_proveedor,p.nombre_proveedor from proveedores p where p.cod_proveedor>0 order by 2;";
                               $resp=mysqli_query($enlaceCon,$sql);
                               while($dat=mysqli_fetch_array($resp)){
                                 $codigo=$dat[0];
                                 $nombre=$dat[1];
                                   echo "<option value='$codigo'>$nombre</option>";
                               }
                                ?>
                            </select>
                          </td>
                          <td>
                            <div class="row">
                              <div class="col-sm-7"><input type='text' placeholder='Nombre del Producto' name='nombre_producto' id='nombre_producto' class="textogranderojo" size="40"></div>          
                            </div>
                          </td>
                          <td>
                            <div class="row">
                              <div class="col-sm-7"><input type='text' placeholder='Codigo de Barras' name='codigo_barras' id='codigo_barras' class="textogranderojo" size="40"></div>          
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-body">
                    <button type="button" class="btn btn-danger" onclick="ajustarPrecios(this.form)">Ver Precios</button>
                    <?php
                    if($globalAdmin==1){
                    ?>
                    <button type="button" class="btn btn-info" onclick="ajustarStocks(this.form)">Ver Stock</button><?php
                    }
                    ?>  
              </div>
               </form>
<?php

?>

