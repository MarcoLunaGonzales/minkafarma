<?php
require "../funciones_siat.php";
if(isset($_GET['act'])){
  $cod_entidad=$_GET['cod_entidad'];
  sincronizarParametrosSiat($_GET['act'],$cod_entidad);  
}else{
  $cod_entidad=$_GET['cod_entidad'];
  sincronizarParametrosSiat("",$cod_entidad);  
}

require "../../conexionmysqli.inc";
?>
<div class="content">
  <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <form id="form1" class="form-horizontal" action="configSave.php" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">check</i>
                  </div>
                  <h4 class="card-title">PARAMETROS SINCRONIZADOS <a href="index.php" class="btn btn-danger float-right"><i class="material-icons">arrow_left</i>Volver</a></h4>                  
                </div>
                <div class="card-body">

                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

