<?php
	require_once("../conexionmysqli.inc");
	require_once("../estilos2.inc");
	require_once("configModule.php");
	require_once("../funciones.php");
?>
<script language='Javascript'>
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
			{	alert('Debe seleccionar solamente un registro para aprobar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para aprobar.');
				}
				else
				{
					$("#modal_numero").html(" - Descuento : "+$("#nombre"+j_cod_registro).val());
                    $("#j_cod_registro").val(j_cod_registro);
                    $("#modal_aprobar").modal("show");
				}
			}           
		}

		function abrir_nav(f)
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
			{	alert('Debe seleccionar un registro para abrir la Oferta.');
			}
			else
			{
				if(confirm('Esta seguro de abrir la Oferta.'))
				{
					location.href='<?=$urlAbrirOferta?>?datos='+datos+'&admin=1';
				}
				else
				{
					return(false);
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
					$("#modal_numero_anular").html(" - Descuento : "+$("#nombre"+j_cod_registro).val());
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

	$fechaActual=date("Y-m-d H:i:s");
	echo "<form method='post' action=''>";
	$sql="select e.codigo, e.nombre, e.abreviatura, e.estado,e.desde,e.hasta,e.cod_estadodescuento,e.observacion_descuento,(SELECT nombre from estados_descuentos where codigo=e.cod_estadodescuento) as nombre_estado,por_linea from $table e where e.estado!=2 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Autorización de Ofertas</h1>";
	
	echo "<div class=''>
	<input type='button' value='Aprobar' name='Aprobar' class='btn btn-success' onclick='enviar_nav(this.form)'>
	<input type='button' value='Abrir Oferta' name='Anular' class='btn btn-danger' onclick='abrir_nav(this.form)'>
	<input type='button' value='Anular' name='Anular' class='btn btn-danger' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='btn btn-default' onclick='eliminar_nav(this.form)'>
	</div>";
	
	
	echo "<center><table class='table table-sm table-bordered'>";
	echo "<tr class='bg-principal text-white'>
	<th colspan='3'></th>
	<th colspan='2' align='center'>Periodo del Descuento</th>
	<th colspan='5'></th>
	</tr>";
	echo "<tr class='bg-principal text-white'>
	<th>&nbsp;</th>
	<th>Nombre</th>
	<th>Descuento</th>
	<th>Desde</th>
	<th>Hasta</th>
	<th style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>today</i> Días</th>
	<th style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>business</i> Sucursales</th>
	<th style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>people_alt</i> Líneas</th>
	<th width='15%' style='background:#999999 !important'><i class='material-icons' style='font-size:14px'>watch</i> Productos</th>
	<th>Estado</th>
	</tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		
		if($dat[4]==""){
			$desde="";
		}else{
			$desde=strftime('%d/%m/%Y %H:%M',strtotime($dat[4]));
		}
		if($dat[5]==""){
			$hasta="";
		}else{
			$hasta=strftime('%d/%m/%Y %H:%M',strtotime($dat[5]));
		}
		
		$dias=obtenerNombreDesDiasRegistrados($codigo);
		$ciudades=obtenerNombreDesCiudadesRegistrados($codigo);
		$tamanioGlosa=50; 
        if(strlen($ciudades)>$tamanioGlosa){
           $ciudades=substr($ciudades, 0, $tamanioGlosa)."...";
        }
		if($dat['por_linea']==2){
			//echo "Todos los Medicamentos";
      $lineas="Todos los Medicamentos";
      $productos="";
		}
		elseif ($dat['por_linea']==3){
			//echo "Todos los Medicamentos";
      $lineas="Todos los Productos Market";
      $productos="";
		}else{
			$productos=obtenerNombreDesProdRegistrados($codigo);
		    $tamanioGlosa=50; 
            if(strlen($productos)>$tamanioGlosa){
               $productos=substr($productos, 0, $tamanioGlosa)."...";
            }
            $lineas="";
		}
		$por_linea=$dat['por_linea'];
        
        $estado_descripcion=$dat['nombre_estado'];
        $observacion_descuento=$dat['observacion_descuento'];
        $est_estado="";
        $icon="vpn_lock";
        $inputcheck="<input type='checkbox' name='codigo' value='$codigo'>";
        switch ($dat['cod_estadodescuento']) {
        	case 1: $est_estado="style='background:#3498DB;color:#fff;'"; break;
        	case 2: $est_estado="style='background:#C0392B;color:#fff;'";$inputcheck=""; break;
        	case 3: $est_estado="style='background:#2AC012;color:#fff;'";$icon="cloud_done"; break;
        	case 4: $est_estado="style='background:#F7DC6F;color:#636563;'"; break;
        	default: $est_estado=""; break;
        }        
        $estado="<div class='btn-group'>
        	<a title='Ver Descuento' href='verDescuento.php?codigo_registro=$codigo' target='_blank' class='btn btn-default btn-sm btn-fab'>
        		<i class='material-icons'>preview</i>
      		</a>
  				<a href='#' class='btn btn-default btn-sm' $est_estado>
  					<i class='material-icons'>$icon</i> ".$dat['nombre_estado']."</a>
					<a title='EP' href='$urlEditProducto?codigo_registro=$codigo' target='_blank' class='btn btn-default btn-sm btn-fab'>
        		<i class='material-icons'>edit</i>
      		</a>
					</div><br><small class='text-muted font-weight-bold'>$observacion_descuento</small>

					<input type='hidden' id='nombre$codigo' value='$nombre'>";
        //estado
		echo "<tr>
		<td>$inputcheck</td>
		<td>$nombre</td>
		<td>$abreviatura</td>
		<td>$desde</td>
		<td>$hasta</td>
		<td>$dias</td>
		<td>$ciudades</td>
		<td><span style='color:red'><b>$lineas</b></span></td>
		<td>$productos</td>
		<td>$estado</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class=''>
	<input type='button' value='Aprobar' name='Aprobar' class='btn btn-success' onclick='enviar_nav(this.form)'>
	<input type='button' value='Abrir Oferta' name='Anular' class='btn btn-danger' onclick='abrir_nav(this.form)'>
	<input type='button' value='Anular' name='Anular' class='btn btn-danger' onclick='editar_nav(this.form)'>
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
                    <h4>Aprobar Salida <b id="modal_numero"></b></h4>      
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
                        <button class="btn btn-success" onclick="aprobarSalidaTraspaso()">APROBAR</button>
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
               <div class="card-header card-header-primary card-header-text">
                  <div class="card-text">
                    <h4>Anular Salida <b id="modal_numero_anular"></b></h4>      
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