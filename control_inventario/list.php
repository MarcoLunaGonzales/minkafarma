<?php
	require_once("../conexionmysqli.inc");
	require_once("../estilos2.inc");
	require_once("configModule.php");
	require_once("../funciones.php");
	require_once("../funcion_nombres.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


 ?>
<script language='Javascript'>
	function registrar_nav()
		{	location.href='<?=$urlRegister?>';
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar.');
				}
				else
				{
					location.href='<?=$urlEdit?>?codigo_registro='+j_cod_registro+'';
				}
			}
		}
function enviar_nav(f){	
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para iniciar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para iniciar.');
				}
				else
				{
					$("#modal_numero").html(" - Inventario : "+$("#nombre"+j_cod_registro).val());
                    $("#j_cod_registro").val(j_cod_registro);
                    $("#modal_aprobar").modal("show");
				}
			}           
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro para eliminar.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='<?=$urlDelete?>?datos='+datos+'&admin=1';
				}
				else
				{
					return(false);
				}
			}
		}

		function finalizar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para finalizar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para finalizar.');
				}
				else
				{
					$("#modal_numero_finalizar").html(" - Inventario : "+$("#nombre"+j_cod_registro).val());
                    $("#j_cod_registro_finalizar").val(j_cod_registro);
                    $("#modal_finalizar").modal("show");
				}
			} 
		}
		function anular_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para anular.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anular.');
				}
				else
				{
					$("#modal_numero_anular").html(" - Inventario : "+$("#nombre"+j_cod_registro).val());
                    $("#j_cod_registro_anular").val(j_cod_registro);
                    $("#modal_anular").modal("show");
				}
			} 
		}
		</script>

<script type="text/javascript">
	function editar_dias(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar los días.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar los días.');
				}
				else
				{
					location.href='<?=$urlEditDia?>?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		function editar_ciudades(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar las sucursales.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar las sucursales.');
				}
				else
				{
					location.href='<?=$urlEditCiudad?>?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		function editar_lineas(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar las líneas.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar las líneas.');
				}
				else
				{
					location.href='<?=$urlEditLinea?>?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		function aprobarSalidaTraspaso(){
           var j_cod_registro = $("#j_cod_registro").val();
           //if($("#modal_observacion").val()==""){
           //  Swal.fire("Informativo!","Debe registrar la glosa para ANULAR", "warning");
           //}else{
              var obs =$("#modal_observacion").val().replace(/['"]+/g, '');  
              location.href='cambiar_estado.php?codigo_registro='+j_cod_registro+'&estado=4&obs='+obs;
           //} 
         }
         function finalizarSalidaTraspaso(){
           var j_cod_registro = $("#j_cod_registro_finalizar").val();
           //if($("#modal_observacion").val()==""){
           //  Swal.fire("Informativo!","Debe registrar la glosa para ANULAR", "warning");
           //}else{
              var obs =$("#modal_observacion_finalizar").val().replace(/['"]+/g, '');  
              location.href='cambiar_estado.php?codigo_registro='+j_cod_registro+'&estado=3&obs='+obs;
           //} 
         }
         function anularSalidaTraspaso(){
           var j_cod_registro = $("#j_cod_registro_anular").val();
           if($("#modal_observacion_anular").val()==""){
             Swal.fire("Informativo!","Debe registrar la observación para ANULAR", "warning");
           }else{
              var obs =$("#modal_observacion_anular").val().replace(/['"]+/g, '');  
              location.href='cambiar_estado.php?codigo_registro='+j_cod_registro+'&estado=2&obs='+obs;
           } 
         }
</script>
	<?php
	$cod_ciudad=$_COOKIE['global_agencia'];
	$usuario=$_COOKIE['global_usuario'];
	echo "<form method='post' action=''>";

	$admin=$_COOKIE['global_admin_cargo'];
	if($admin==1){
		$sql="select e.codigo, e.nombre,e.estado_inventario,e.fecha_iniciorevision,e.fecha_finrevision,e.glosa_inventario,(SELECT nombre from estados_inventario where codigo=e.estado_inventario) as nombre_estado,e.cod_funcionario,e.cod_ciudad,e.fecha_reporte from $table e where e.cod_estadoreferencial=1 and cod_ciudad=$cod_ciudad order by e.fecha_reporte desc,e.nombre limit 0,100";
	}else{
		$sql="select e.codigo, e.nombre,e.estado_inventario,e.fecha_iniciorevision,e.fecha_finrevision,e.glosa_inventario,(SELECT nombre from estados_inventario where codigo=e.estado_inventario) as nombre_estado,e.cod_funcionario,e.cod_ciudad,e.fecha_reporte from $table e where e.cod_estadoreferencial=1 and cod_ciudad=$cod_ciudad AND cod_funcionario='$usuario' order by e.fecha_reporte desc,e.nombre limit 0,100";	
	}
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>$moduleNamePlural</h1>";
	
	echo "<div class=''>
	<input type='button' value='Adicionar' name='adicionar' class='btn btn-primary' onclick='registrar_nav()'>
	<input type='button' value='Editar' name='Editar' class='btn btn-warning' onclick='editar_nav(this.form)'>
	<input type='button' value='Iniciar Revisión' name='Aprobar' class='btn btn-success' onclick='enviar_nav(this.form)'>
	<input type='button' value='Finalizar Revisión' name='Anular' class='btn btn-success' onclick='finalizar_nav(this.form)'>
	<input type='button' value='Anular Revisión' name='Anular' class='btn btn-danger' onclick='anular_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='btn btn-default' onclick='eliminar_nav(this.form)'>
	</div>";
	
	
	echo "<center><table class='table table-sm table-bordered'>";
	echo "<tr class='bg-principal text-white'>
	<th>&nbsp;</th>
	<th width='20%'>Nombre</th>
	<th>Sucursal</th>
	<th>Proveedor</th>
	<th>Responsable</th>
	<th>Fecha</th>
	<th style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>today</i> F. Inicio Revisión</th>
	<th style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>today</i> F. Fin Revisión</th>
	<th>Estado</th>
	</tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProveedor=obtenerProveedorLineaInventario($codigo);
		$nombre=$dat[1];
		
		if($dat['fecha_iniciorevision']==""){
			$desde="";
		}else{
			$desde=strftime('%d/%m/%Y %H:%M',strtotime($dat["fecha_iniciorevision"]));
		}
		if($dat['fecha_finrevision']==""){
			$hasta="";
		}else{
			$hasta=strftime('%d/%m/%Y %H:%M',strtotime($dat["fecha_finrevision"]));
		}

		if($dat['fecha_reporte']==""){
			$fecha="";
		}else{
			$fecha=strftime('%d/%m/%Y',strtotime($dat["fecha_reporte"]));
		}


		$ciudad=nombreTerritorio($enlaceCon, $dat["cod_ciudad"]);
        $responsable=nombreVisitador($enlaceCon, $dat["cod_funcionario"]);
        $estado_descripcion=$dat['nombre_estado'];
        $observacion_descuento=$dat['glosa_inventario'];
        $est_estado="";
        $icon="delete_forever";
        $enlaceDetalles="<a href='$urlListDetalle?c=$codigo&b=0' target='_blank' class='btn btn-sm btn-default'><i class='material-icons'>assignment</i>&nbsp;</a>";
        $inputcheck="<input type='checkbox' name='codigo' value='$codigo'>";
        $enrevisionporcentaje="";
        switch ($dat['estado_inventario']) {
        	case 1: $est_estado="style='background:#3498DB;color:#fff;'";$icon=""; break;
        	case 2: $est_estado="style='background:#C0392B;color:#fff;'";$inputcheck="";$enlaceDetalles=""; break;
        	case 3: $est_estado="style='background:#2AC012;color:#fff;'";$inputcheck="";$icon="assignment_turned_in"; break;
        	case 4: 
        	//$enrevisionporcentaje=porcentajeAvanceInventario($codigo);
        	$est_estado="style='background:#F7DC6F;color:#636563;'";
        	$icon="pending";
        	$enlaceDetalles="<a href='$urlListDetalle?c=$codigo&b=1' target='_blank' class='btn btn-sm btn-default'><i class='material-icons'>assignment</i>&nbsp;</a>"; 
        	break;
        	default: $est_estado=""; break;
        }        
        $estado="<div class='btn-group'><a href='#' class='btn btn-default btn-sm' $est_estado> <i class='material-icons'>$icon</i> ".$dat['nombre_estado']."</a>$enlaceDetalles</div><br><small class='text-muted font-weight-bold'>$observacion_descuento</small><input type='hidden' id='nombre$codigo' value='$nombre'>";
        //estado
        $nombre.=" ".$enrevisionporcentaje."";
		echo "<tr>
		<td>$inputcheck</td>
		<td>$nombre</td>
		<td>$ciudad</td>
		<td>$nombreProveedor</td>
		<td><small>$responsable</small></td>
		<td>$fecha</td>
		<td>$desde</td>
		<td>$hasta</td>
		<td>$estado</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class=''>
	<input type='button' value='Adicionar' name='adicionar' class='btn btn-primary' onclick='registrar_nav()'>
	<input type='button' value='Editar' name='Editar' class='btn btn-warning' onclick='editar_nav(this.form)'>
	<input type='button' value='Iniciar Revisión' name='Aprobar' class='btn btn-success' onclick='enviar_nav(this.form)'>
	<input type='button' value='Finalizar Revisión' name='Anular' class='btn btn-success' onclick='finalizar_nav(this.form)'>
	<input type='button' value='Anular Revisión' name='Anular' class='btn btn-danger' onclick='anular_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='btn btn-default' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>

        <!-- small modal -->
<div class="modal fade modal-primary" id="modal_aprobar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
               <div class="card-header card-header-success card-header-text">
                  <div class="card-text">
                    <h4>Iniciar <b id="modal_numero"></b></h4>      
                  </div>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="j_cod_registro" id="j_cod_registro" value="0">
                      <div class="row">
                          <label class="col-sm-2 col-form-label">Observación</label>
                           <div class="col-sm-10">                     
                             <div class="form-group">
                               <textarea class="form-control" id="modal_observacion" name="modal_observacion"></textarea>
                             </div>
                           </div>        
                      </div>
                      <br><br>
                      <div class="float-right">
                        <button class="btn btn-success" onclick="aprobarSalidaTraspaso()">Iniciar Revisión</button>
                        <button class="btn btn-default"  data-dismiss="modal">CANCELAR</button>
                      </div> 
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->

        <!-- small modal -->
<div class="modal fade modal-primary" id="modal_finalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
               <div class="card-header card-header-success card-header-text">
                  <div class="card-text">
                    <h4>Finalizar <b id="modal_numero_finalizar"></b></h4>      
                  </div>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="j_cod_registro_finalizar" id="j_cod_registro_finalizar" value="0">
                      <div class="row">
                          <label class="col-sm-2 col-form-label">Observación (*)</label>
                           <div class="col-sm-10">                     
                             <div class="form-group">
                               <textarea class="form-control" id="modal_observacion_finalizar" name="modal_observacion_finalizar"></textarea>
                             </div>
                           </div>        
                      </div>
                      <br><br>
                      <div class="float-right">
                        <button class="btn btn-success" onclick="finalizarSalidaTraspaso()">Finalizar Revisión</button>
                        <button class="btn btn-default"  data-dismiss="modal">CANCELAR</button>
                      </div> 
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->

        <!-- small modal -->
<div class="modal fade modal-primary" id="modal_anular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
               <div class="card-header card-header-danger card-header-text">
                  <div class="card-text">
                    <h4>Anular <b id="modal_numero_anular"></b></h4>      
                  </div>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="j_cod_registro_anular" id="j_cod_registro_anular" value="0">
                      <div class="row">
                          <label class="col-sm-2 col-form-label">Observación (*)</label>
                           <div class="col-sm-10">                     
                             <div class="form-group">
                               <textarea class="form-control" id="modal_observacion_anular" name="modal_observacion_anular"></textarea>
                             </div>
                           </div>        
                      </div>
                      <br><br>
                      <div class="float-right">
                        <button class="btn btn-danger" onclick="anularSalidaTraspaso()">ANULAR</button>
                        <button class="btn btn-default"  data-dismiss="modal">CANCELAR</button>
                      </div> 
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->