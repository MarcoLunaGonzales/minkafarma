<html>
    <head>
        <title>INVENTARIO</title>
        <link rel="shortcut icon" href="imagenes/icon_farma.ico" type="image/x-icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
<?php
	require_once("../conexionmysqli.inc");
	require_once("../estilos2.inc");
	require_once("configModule.php");
	require_once("../funciones.php");
	require_once("../funcion_nombres.php");
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
  function marcarFila(fila){
  	$(".filas").css("background","#fff");
  	$(".filas").css("color","#000");
    $("#fila"+fila).css("background","#9FE5E4");
    $("#fila"+fila).css("color","#626262");
  }
  function restarFila(fila){
  	var cantidad=$("#cantidad"+fila).val();
  	var cantidad_registrada=$("#cantidad_registrada"+fila).val();
  	$("#diferencia"+fila).html(parseInt(cantidad)-parseInt(cantidad_registrada));
  }
  function guardarFilaInventario(fila){
   var codigo=fila;
   var cantidad=$("#cantidad_registrada"+fila).val();
   var observacion=$("#observacion_registrada"+fila).val();
   var parametros={"codigo":codigo,"cantidad_registrada":cantidad,"observacion":observacion};
   $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajax_guardar_inventario_detalle.php",
        data: parametros,
        beforeSend: function () {
          iniciarCargaAjax("Registrando Inventario...");
        },
        success:  function (resp) {
        	var r=parseInt(resp.split("#####")[1]);
        	detectarCargaAjax();
        	if(r==1){
        		actualizarRegistrado(fila);
        	}           
        }
    });
  }
  function actualizarRegistrado(fila){
    var cantidad=parseInt($("#cantidad"+fila).val());
    var cantidad_registrada=parseInt($("#cantidad_registrada"+fila).val());
    if(cantidad!=cantidad_registrada){
       $("#estado_glosa"+fila).html("<i class='material-icons text-warning'>report_problem</i>");
    }else{
       $("#estado_glosa"+fila).html("<i class='material-icons text-success'>check_circle</i>");
    }
    if(!$("#boton_refresh"+fila).hasClass("d-none")){
    	$("#boton_refresh"+fila).addClass("d-none");	 
    }
  }
  function filtrarExistenciasInventario(){
  	var filtro = $("#filtro_existencias").val();
  	var cod_inventario = $("#cod_inventario").val();
  	var b_admin = $("#b_admin").val();
  	window.location.href="listDetalle.php?c="+cod_inventario+"&b="+b_admin+"&f="+filtro;
  }
  function actualizarSaldoFilaInventario(fila){
     Swal.fire({
        title: '¿Esta seguro de actualizar el Saldo?',
        text: "Se cambiará la fecha de saldo en la fila",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            actualizarSaldoFilaInventarioAjax(fila);                
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
  }
  function actualizarSaldoFilaInventarioAjax(fila){
      var codigo=fila;
      var fecha=$("#fecha"+fila).val();
      var parametros={"codigo":codigo,"fecha":fecha};
      $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajax_guardar_saldo_inventario_detalle.php",
        data: parametros,
        beforeSend: function () {
          iniciarCargaAjax("Actualizando Saldo...");
        },
        success:  function (resp) {
        	detectarCargaAjax();
        	$("#cantidad_registrada"+fila).val(resp);
        	$("#cantidad_stock"+fila).html(resp)           
        }
     });
  }
</script>
	<?php
	$cod_ciudad=$_COOKIE['global_agencia'];
	$cod_inventario=$_GET['c'];
	$b_admin=$_GET['b'];
	$sqlConf="select nombre from $table where codigo=$cod_inventario";
	//echo $sqlConf;
    $respConf=mysqli_query($enlaceCon,$sqlConf);
    $nombreTxt=mysqli_result($respConf,0,0);
    $stringFiltro=" and s.cantidad>0 ";$sel0="";$sel1="selected";$sel2="";
    if(isset($_GET["f"])){
    	switch ($_GET["f"]) {
    		case '0':$stringFiltro=" and s.cantidad=0 ";$sel0="selected";$sel1="";$sel2=""; break;
    		case '1':$stringFiltro=" and s.cantidad>0 ";$sel1="selected";$sel0="";$sel2=""; break;
    		case '2':$stringFiltro="";$sel2="selected";$sel1="";$sel0=""; break;
    	}    	
    }
	echo "<form method='post' action=''>";
	$sql="SELECT s.codigo,s.cod_material,m.descripcion_material,s.cantidad,s.cantidad_registrada,s.observacion,s.revisado,s.fecha_saldo,(SELECT nombre_linea_proveedor FROM proveedores_lineas where cod_linea_proveedor=m.cod_linea_proveedor)linea from $tableDetalle s join material_apoyo m on m.codigo_material=s.cod_material where s.cod_inventariosucursal=$cod_inventario $stringFiltro order by m.descripcion_material";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>$moduleNamePlural '$nombreTxt'</h1>";
	?>
      <center><label class='text-muted'>Filtrar:</label> <select class='selectpicker' data-style='btn btn-info' id='filtro_existencias' onchange="filtrarExistenciasInventario()">
      	     <option value='0' <?=$sel0?>>SIN EXISTENCIA</option>
      	     <option value='1' <?=$sel1?>>CON EXISTENCIAS</option>
      	     <option value='2' <?=$sel2?>>TODOS</option>
      </select></center>
      <br>
      <input type="hidden" id="cod_inventario" value="<?=$cod_inventario?>">
      <input type="hidden" id="b_admin" value="<?=$b_admin?>">
	<?php
	echo "<center><table class='table table-sm table-condensed table-bordered'>";
	echo "<tr class='bg-info text-white'>
	<th width='1%'>&nbsp;</th>
	<th>Codigo</th>	
	<th width='20%'>Producto</th>
	<th width='10%'>Linea</th>
	<th width='10%'>Fecha Saldo</th>
	<th width='3%'>Existencia</th>
	<th width='3%'>Recuento<br>Físico</th>
	<th style='background:#999999 !important' colspan='2'>Diferencia</th>
	<th style='background:#999999 !important'>Observacion</th>
	<th>Opciones</th>	
	</tr>";

	$sqlConf="select estado_inventario from $table where codigo=$cod_inventario";
    $respConf=mysqli_query($enlaceCon,$sqlConf);
    $estadoInv=mysqli_result($respConf,0,0);


	$index=0;
	$readonly='readonly';	
	$disabled='d-none';	
	$admin=$_GET['b'];
	if($admin==1&&$estadoInv==4){
		$readonly='';
		$disabled='';	
	}
	while($dat=mysqli_fetch_array($resp))
	{
		$index++;
		$codigo=$dat[0];
		$cod_material=$dat[1];
		$nombre=$dat[2];
		$cantidad=$dat[3];
		$cantidad_registrada=$dat[4];
		$observacion=$dat[5];
		$fechaSaldo=explode(" ",$dat['fecha_saldo'])[0];
		$estiloCheck="btn btn-warning";
		$estado_glosa="<i class='material-icons text-muted'>pending</i>";
		$botonRefresh="<button class='btn btn-danger btn-sm btn-fab $disabled' id='boton_refresh$codigo'><i class='material-icons' title='Actualizar Saldo' onclick='actualizarSaldoFilaInventario($codigo);return false;'>refresh</i></button>";
		if($dat[6]==1){
			$botonRefresh="";
		    $dif=$dat[3]-$dat[4];		
			$estiloCheck="btn btn-success";
			$estado_glosa="<i class='material-icons text-warning'>report_problem</i>";
			if($dif==0){
			  $estado_glosa="<i class='material-icons text-success'>check_circle</i>";
		    }
		}else{
			$cantidad_registrada=$dat[3];
			$dif=0;
		}
        $lineaProv="<small class='text-muted'>".$dat['linea']."</small>";
		echo "<tr id='fila$codigo' class='filas'>
		<td>$index</td>
		<td style='text-align:left; '>$cod_material</td>
		<td align='left' style='text-align:left; '><small>$nombre</small></td>
		<td align='left' style='text-align:left; '>$lineaProv</td>
		<td align='left' style='text-align:left; '><input type='date' value='$fechaSaldo' id='fecha$codigo' class='form-control' min='$fechaSaldo' style='text-align:left; '></td>
		<td align='right' style='text-align:right' id='cantidad_stock$codigo'>$cantidad</td>
		<td><input type='hidden' value='$cantidad' id='cantidad$codigo'><input type='number' class='texto' value='$cantidad_registrada' onfocus='marcarFila($codigo)' style='text-align:right;width:100px !important;' id='cantidad_registrada$codigo' onchange='restarFila($codigo)' onkeyup='restarFila($codigo)' onkeydown='restarFila($codigo)' $readonly></td>
		<td id='diferencia$codigo'>$dif</td>
		<td id='estado_glosa$codigo'>$estado_glosa</td>
		<td><input class='texto' value='$observacion' onfocus='marcarFila($codigo)' style='text-align:left;width:100% !important;' id='observacion_registrada$codigo' placeholder='Ingrese la observación...' $readonly></td>
		<td>$botonRefresh<button class='btn btn-info btn-sm btn-fab $disabled'><i class='material-icons' title='Guardar Inventario' onclick='guardarFilaInventario($codigo);return false;'>save</i></button></td>
		</tr>";
	}
	echo "</table></center><br>";
	
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
</body>    
</html>
<?php
mysqli_close($enlaceCon);
?>