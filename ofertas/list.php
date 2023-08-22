<?php
	require_once("../conexionmysqli.inc");
	require_once("../estilos2.inc");
	require_once("configModule.php");
	require_once("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');



echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='$urlRegister';
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
					location.href='$urlDelete?datos='+datos+'';
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
					location.href='$urlEdit?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	?>
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
					if(parseInt($("#por_linea"+j_cod_registro).val())==1){
                      location.href='<?=$urlEditLinea?>?codigo_registro='+j_cod_registro+'';
					}else{
					  alert('El descuento seleccionado es de nivel Productos.');
					}
				}
			}
		}
		function editar_productos(f)
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
			{	alert('Debe seleccionar solamente un registro para editar los productos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar los productos.');
				}
				else
				{
					if(parseInt($("#por_linea"+j_cod_registro).val())==1){  
                      alert('El descuento seleccionado es de nivel Líneas.');
					}else{
					  location.href='<?=$urlEditProducto?>?codigo_registro='+j_cod_registro+'';
					} 
					
				}
			}
		}

		function clonarOfertaGenerada(codigo,nombre){
			Swal.fire({
			  title: '¿Clonar Oferta '+nombre+'?',
			  html:'Se creará una nueva oferta con las mismas caracteristicas de la seleccionada.',			  
			  showCancelButton: true,
			  confirmButtonText: 'Si',
			  confirmButtonColor: '#5C079B',
			  cancelButtonText: 'No',			  
			}).then((result) => {	
			 if (result.value) {
            clonarOfertaGeneradaAjax(codigo);                              
          } else if (result.dismiss === Swal.DismissReason.cancel) {
          	return false;
            //Swal.fire('Cancelada','El proceso de clonación no se ejecutó','info');
          }		  
			})	
		}
		function clonarOfertaGeneradaAjax(codigo){
			var parametros={"codigo":codigo}; 
		    $.ajax({
		    url: "ajax_oferta_clonar.php",
		    dataType: "html",
		    data: parametros,
		    type: "GET",
		    beforeSend:function(){
		    	$("#datos_body_load").html($("#cargando_datos").html());
		    },		    
		    success: function(resp){
		    	//alert(resp)
		      $("#datos_body_load").html("");
		      location.reload();
		    }
		   });

		}
</script>
<div id="datos_body_load"></div>
<div id="cargando_datos" class="d-none">
<div  style="z-index: 9999;position: fixed;width: 100%;top:0;background: rgba(255, 255, 255, 1);height: 100vh;color:#505050;padding: 50px;">
  <center>    
  <img src="../imagenes/clone.gif" width="280px">
  <br><br>  
  <p style='font-size: 30px;'><b>CLONANDO OFERTA...</b></p>
  <p style='font-size: 15px;'>Espere por favor...</p>
  <img src='../imagenes/farmacias_bolivia_loop.gif' width='300px' heigth='100px' style='position:fixed;left:40px;bottom:20px;z-index: 9998;'>
  </center>
</div>
</div>
	<?php
	echo "<form method='post' action='' onsubmit='return false;'>";
	$sql="SELECT e.codigo, e.nombre, e.abreviatura, e.estado,e.desde,e.hasta,e.por_linea,e.cod_estadodescuento,(SELECT nombre from estados_descuentos where codigo=e.cod_estadodescuento) as nombre_estado,e.observacion_descuento, e.oferta_stock_limitado from $table e where e.estado=1 order by 2";
	
	//echo $sql;
	

	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Lista de $moduleNamePlural</h1>";
	
	echo "<div class=''>
	<input type='button' value='Adicionar' name='adicionar' class='btn btn-primary' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='btn btn-warning' onclick='editar_nav(this.form)'>
	<button title='Modificar Días' name='Dias' class='btn btn-default' onclick='editar_dias(this.form)'><i class='material-icons'>today</i>&nbsp;</button>
	<button title='Modificar Sucursales' name='Ciudades' class='btn btn-default' onclick='editar_ciudades(this.form)'><i class='material-icons'>business</i>&nbsp;</button>
	<button title='Modificar Lineas' name='Lineas' class='btn btn-default' onclick='editar_lineas(this.form)'><i class='material-icons'>people_alt</i>&nbsp;</button>
	<button title='Modificar Productos' name='Productos' class='btn btn-default' onclick='editar_productos(this.form)'><i class='material-icons'>watch</i>&nbsp;</button>
	<input type='button' value='Eliminar' name='eliminar' class='btn btn-danger' onclick='eliminar_nav(this.form)'>
	</div>";
	
	
	echo "<center><table class='table table-sm table-bordered'>";
	echo "<tr class='bg-principal text-white'>
	<th colspan='3'></th>
	<th colspan='3' align='center'>Periodo del Descuento</th>
	<th colspan='5'></th>
	</tr>";
	echo "<tr class='bg-principal text-white'>
	<th>&nbsp;</th>
	<th>Nombre</th>
	<th>Descuento<br>Base %</th>
	<th>Oferta <br> Stock Limitado</th>
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
		//echo "entro al while 1";
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

		//echo "entro al while 2";
		
		$dias=obtenerNombreDesDiasRegistrados($codigo);
		$ciudades=obtenerNombreDesCiudadesRegistrados($codigo);

		$tamanioGlosa=50; 
     	if(strlen($ciudades)>$tamanioGlosa){
        	$ciudades=substr($ciudades, 0, $tamanioGlosa)."...";
     	}
		if($dat['por_linea']==1){
			//echo "dentro del if";
			$lineas=obtenerNombreDesLineasRegistrados($codigo);
			echo "lineas: ".$lineas;
		   $tamanioGlosa=50; 
            if(strlen($lineas)>$tamanioGlosa){
               $lineas=substr($lineas, 0, $tamanioGlosa)."...";
            }
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
		$inputcheck="<input type='checkbox' name='codigo' value='$codigo'><input type='hidden' id='por_linea$codigo' value='$por_linea'>";

		$clonarOption="";
		if($dat["cod_estadodescuento"]==3||$dat["cod_estadodescuento"]==2){
          $inputcheck="";
          if($dat["cod_estadodescuento"]==3){
					$clonarOption="<a title='Clonar Oferta' href='#' target='_blank' class='btn btn-danger btn-sm btn-fab' style='background:#5C079B' onclick='clonarOfertaGenerada($codigo,\"$nombre\");return false;'><i class='material-icons'>content_copy</i></a>";
          }
		}
		$est_estado="";
		$estado_descripcion=$dat['nombre_estado'];
		$observacion_descuento=$dat['observacion_descuento'];
		$ofertaStockLimitado=$dat['oferta_stock_limitado'];
		$txtOfertaStockLimitado="NO";
		if($ofertaStockLimitado==1){
			$txtOfertaStockLimitado="SI";
		}

        switch ($dat['cod_estadodescuento']) {
        	case 1: $est_estado="style='background:#3498DB;color:#fff;'"; break;
        	case 2: $est_estado="style='background:#C0392B;color:#fff;'";$inputcheck=""; break;
        	case 3: $est_estado="style='background:#2AC012;color:#fff;'";$icon="cloud_done"; break;
        	case 4: $est_estado="style='background:#F7DC6F;color:#636563;'"; break;
        	default: $est_estado=""; break;
        } 
        $estado="<div class='btn-group'>$clonarOption<a title='Ver Descuento' href='verDescuento.php?codigo_registro=$codigo' target='_blank' class='btn btn-default btn-sm btn-fab'><i class='material-icons'>preview</i></a><a href='#' class='btn btn-default btn-sm' $est_estado> <i class='material-icons'>$icon</i> ".$dat['nombre_estado']."</a></div><small class='text-muted font-weight-bold'>$observacion_descuento</small><input type='hidden' id='nombre$codigo' value='$nombre'>";
		
		echo "<tr>
		<td>$inputcheck</td>
		<td>$nombre</td>
		<td>$abreviatura</td>
		<td><span style='color:red'><b>$txtOfertaStockLimitado</b></span></td>
		<td>$desde</td>
		<td>$hasta</td>
		<td>$dias</td>
		<td>$ciudades</td>
		<td>$lineas</td>
		<td>$productos</td>
		<td>$estado</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class=''>
	<input type='button' value='Adicionar' name='adicionar' class='btn btn-primary' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='btn btn-warning' onclick='editar_nav(this.form)'>
	<button title='Modificar Días' name='Dias' class='btn btn-default' onclick='editar_dias(this.form)'><i class='material-icons'>today</i>&nbsp;</button>
	<button title='Modificar Sucursales' name='Ciudades' class='btn btn-default' onclick='editar_ciudades(this.form)'><i class='material-icons'>business</i>&nbsp;</button>
	<button title='Modificar Lineas' name='Lineas' class='btn btn-default' onclick='editar_lineas(this.form)'><i class='material-icons'>people_alt</i>&nbsp;</button>
	<button title='Modificar Productos' name='Productos' class='btn btn-default' onclick='editar_productos(this.form)'><i class='material-icons'>watch</i>&nbsp;</button>
	<input type='button' value='Eliminar' name='eliminar' class='btn btn-danger' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>
