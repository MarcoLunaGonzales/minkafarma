<?php //ESTADO FINALIZADO

require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");

?>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon">
            <div class="card-icon bg-blanco">
              <img class="" width="40" height="40" src="../assets/img/favicon.png">
            </div>
            <h4 class="card-title text-center">Facturas OFF line</h4>            
            <div class="row">
               
            </div> 
          </div>
          <form class="" action="save.php" method="POST">
            <input type="hidden" name="p" id="p" value="<?=$p?>">
          <div class="card-body">
            <div class="table-responsive">
              <input type="hidden" name="glosa_ingreso" id="glosa_ingreso" value="<?=$glosa?>">
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>                              
                  <tr>
                    <tr class='bg-info text-white'><th>&nbsp;</th><th>Nro. Factura</th><th>Fecha Emisión<br></th><th>Monto</th>
    				<th>Cliente</th><th>Razon Social</th><th>NIT</th><th>Proceso</th></tr>
                  </tr>                                  
                </thead>
                <tbody>
                  <?php
                  $index=0;
                   $sql="SELECT s.cod_salida_almacenes,(select nombre_almacen from almacenes where cod_almacen=s.cod_almacen)sucursal, s.fecha, s.hora_salida, s.nro_correlativo,  
					  (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente)cliente, s.cod_tipo_doc, razon_social, nit,s.cod_tipopago,s.monto_final,s.cod_tipoEmision
					  FROM salida_almacenes s
					  WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
						and cod_tipoEmision=2 and codigoRecepcionFactura is null
						order by 2,nro_correlativo";

                  //echo $sql;
                  $resp=mysqli_query($dbh,$sql);
                  while($row=mysqli_fetch_array($resp)){ 
                    $cod_salida_almacenes=$row['cod_salida_almacenes'];
                    $sucursal=$row['sucursal'];
                    $fecha=$row['fecha'];
                    $hora_salida=$row['hora_salida'];

                    $nro_correlativo=$row['nro_correlativo'];
                    $cliente=$row['cliente'];
                    $cod_tipo_doc=$row['cod_tipo_doc'];
                    $razon_social=$row['razon_social'];
                    $nit=$row['nit'];
                    $cod_tipopago=$row['cod_tipopago'];
                    $monto_final=$row['monto_final'];
                    $cod_tipoEmision=$row['cod_tipoEmision'];
                    
                    
                    if($sw_contabilizacion==0){ 
                      $index++;
                      ?>
                    <tr>
                      <td class="text-center small"><?=$index;?></td>
                      <td class="text-center small"><?=$nro_correlativo;?></td>
                      <td class="text-left small"><?=$datos_ingreso_origen;?></td>
                      <td class="text-left small"><?=$nombre_proveedor;?></td>
                      <td class="text-left small"><?=$nro_factura_proveedor;?></td>
                      <td class="text-center small"><?=$FECHA1;?></td>
                      <td class="text-left small"><?=$nit_factura_proveedor;?></td>
                      <td class="text-right small"><?=$aut_factura_proveedor;?></td>
                      <td class="text-left small"><?=$con_factura_proveedor;?></td>
                      
                      
                      <td class="td-actions text-right">
                      <input type="hidden" id="ingresos_activado_s<?=$index?>" name="ingresos_activado_s<?=$index?>"  value="0">
                      <input type="hidden" id="dcto_ingreso_s<?=$index?>" name="dcto_ingreso_s<?=$index?>"  value="<?=$cod_ingreso_almacen?>">
                        <input type="checkbox"  data-toggle="toggle" title="Seleccionar" id="ingresos_seleccionados<?=$index?>" name="ingresos_seleccionados<?=$index?>" onchange="activar_input_ingresos_almacen(<?=$index?>)">
                      </td>
                    </tr>
                    <?php   
                    }
                  }?>
                </tbody>
              </table>
              <input type="hidden" name="contador_items" id="contador_items" value="<?=$index?>">            
            </div>
          </div>
          <div class="card-footer fixed-bottom">
              <button type="submit" class="btn btn-rose">Guardar seleccionados</button>
              <!-- <button type="button" class="btn btn-info" >Histórico</button> -->
              <a class="btn btn-info" target="blank" onClick="historico_ingresos_almacen_nuevo('<?=$fechaDesde?>','<?=$fechahasta?>','<?=$id_proveedor?>')">Histórico</a>
          </div>
          </form>

        </div>
      </div>
    </div>  
  </div>
</div>


<!-- modal editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Ingreso Almacén</h4>
      </div>
      <div class="modal-body">        
        <input type="hidden" name="fecha_desde_edit" id="fecha_desde_edit" > 
        <input type="hidden" name="fecha_hasta_edit" id="fecha_hasta_edit" > 
        <input type="hidden" name="dcto_edit" id="dcto_edit">

        <div class="row">
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Ingreso</small></label>
          <div class="col-sm-2">
            <div class="form-group">
              <input type="text" class="form-control" name="ningreso_edit" id="ningreso_edit" style="background-color:#e2d2e0" readonly="true">              
            </div>
          </div>
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Nro.<br>Factura.</small></label>
          <div class="col-sm-1">
            <div class="form-group" >
              <input type="text" class="form-control" name="factura_edit" id="factura_edit" style="background-color:white">              
            </div>
          </div>
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Fecha</small></label>
          <div class="col-sm-2">
            <div class="form-group" >              
              <input type="date" class="form-control" name="fecha_edit" id="fecha_edit" style="background-color:white">
            </div>
          </div>
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Monto</small></label>
          <div class="col-sm-2">
            <div class="form-group" >              
              <input type="text" class="form-control" name="monto_edit" id="monto_edit" readonly="true" style="background-color:#e2d2e0">
            </div>
          </div>
        </div>           

        <div class="row">
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Nit</small></label>
          <div class="col-sm-2">
            <div class="form-group" >
              <input type="text" class="form-control" name="nit_edit" id="nit_edit" style="background-color:white">              
            </div>
          </div>
          <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Autorización</small></label>
          <div class="col-sm-3">
            <div class="form-group" >              
              <input type="text" class="form-control" name="autoriza_edit" id="autoriza_edit" style="background-color:white">
            </div>
          </div>
           <label class="col-sm-1 col-form-label text-dark font-weight-bold"><small>Codigo<br>Control</small></label>
          <div class="col-sm-3">
            <div class="form-group" >
              <input type="text" class="form-control" name="codigocontrol_edit" id="codigocontrol_edit" style="background-color:white">              
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="guardar_edit_ingreso_alm" name="guardar_edit_ingreso_alm" data-dismiss="modal">Guardar Cambios</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancelar </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#guardar_edit_ingreso_alm').click(function(){
      
      var dcto_edit=document.getElementById("dcto_edit").value;
      var factura_edit=$('#factura_edit').val();
      fecha_desde="";
      fecha_hasta="";
      var fecha_edit=$('#fecha_edit').val();
      var monto_edit="";
      var nit_edit=$('#nit_edit').val();
      var autoriza_edit=$('#autoriza_edit').val();
      var codigocontrol_edit=$('#codigocontrol_edit').val();

      if(autoriza_edit==null || autoriza_edit==0 || autoriza_edit=='' || autoriza_edit==' '){
        Swal.fire("Informativo!", "Por favor introduzca la observación.", "warning");
       }else{        
        guardar_edit_ingreso_alm(dcto_edit,factura_edit,fecha_edit,monto_edit,nit_edit,autoriza_edit,codigocontrol_edit,fecha_desde,fecha_hasta);
       }      
    });    
  });
</script>