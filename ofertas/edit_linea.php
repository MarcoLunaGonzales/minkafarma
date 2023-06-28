<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");
/*$sql="select d.cod_linea_proveedor,(SELECT cod_linea_proveedor from tipos_precio_lineas where cod_tipoprecio=$codigo_registro and cod_linea_proveedor=d.cod_linea_proveedor),(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor from proveedores_lineas d where d.estado=1 order by 3,4 ";*/

$sql="(SELECT s.cod_linea_proveedor,d.cod_linea_proveedor,d.cod_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor
from tipos_precio_lineas s join proveedores_lineas d on d.cod_linea_proveedor=s.cod_linea_proveedor where s.cod_tipoprecio=$codigo_registro and d.estado=1)
   UNION (SELECT d.cod_linea_proveedor,0 as cod_linea_proveedor,d.cod_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor
from proveedores_lineas d where d.estado=1 and d.cod_linea_proveedor not in (SELECT s.cod_linea_proveedor from tipos_precio_lineas s join proveedores_lineas d on d.cod_linea_proveedor=s.cod_linea_proveedor where s.cod_tipoprecio=$codigo_registro and d.estado=1)) order by 4,5";

$resp=mysqli_query($enlaceCon,$sql);
?>
<script type="text/javascript">
  function seleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=1
   }
  function deseleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=0
   } 
</script>
<?php
//echo $sql;
echo "<form action='$urlSaveEditLinea' method='post' name='f1'>";

echo "<h1>Modificar Líneas del Precio</h1>";
echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
<div class='float-right'><a href='#' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modalBuscarLinea'>&nbsp;<i class='material-icons'>search</i></a>  
  </div>
</div>";
echo "<input type='hidden' value='$codigo_registro' name='tipo' id='tipo'>";

	echo "<center><table class='table table-sm table-bordered' id='tabla_lineas'>";
	echo "<tr class='bg-info text-white font-weight-bold'>
	<th width='10%'><div class='btn-group'><a href='#' class='btn btn-sm btn-warning' onClick='seleccionar_todo()'>T</a><a href='#' onClick='deseleccionar_todo()' class='btn btn-sm btn-default'>N</a></div></th>
	<th>Proveedor</th>
	<th>Línea</th>
	<th>Estado</th>
	</tr>";
	$index=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$index++;		 
		$lineas=$dat[4];
		$proveedor=$dat[3];
		$estado="";
		$checked="";
		if($dat[1]>0){
         $estiloTexto="text-success font-weight-bold";
         $checked="checked";
         $estado="REGISTRADO";
		}
		echo "<tr>
		<td><input type='checkbox' name='codigo[]' value='$dat[0]' $checked></td>
		<td>$proveedor</td>
		<td>$lineas</td>
		<td>$estado</td>
		</tr>";
	}
	echo "</table></center><br>";

echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";

echo "</form>";
$cadComboProv="";
$consult="select t.`cod_proveedor`, t.`nombre_proveedor` from proveedores t order by 2";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["cod_proveedor"];
    $nomProv = $reg1["nombre_proveedor"];
    $cadComboProv.="<option value='$codTipo'>$nomProv</option>";
   }
?>

<!-- modal devolver solicitud -->
<div class="modal fade" id="modalBuscarLinea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#732590; !important;color:#fff;">
        <h4 class="modal-title">Buscar Linea</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
      </div>
      <div class="modal-body">  
      <div class="row">
          <label class="col-sm-1 col-form-label" style="color:#7e7e7e"><span id=""><small >Proveedores</small></span></label>
          <div class="col-sm-11">
            <div class="form-group" >              
                <select class="selectpicker form-control form-control-sm" data-live-search="true" title="-- Elija una proveedor --" name="buscar_proveedor[]" id="buscar_proveedor" multiple data-actions-box="true" data-style="select-with-transition" data-actions-box="true" data-size="10">
                    <?php echo $cadComboProv;?>
                </select>
            </div>
          </div>
        </div>      
        <div class="row">
          <label class="col-sm-1 col-form-label" style="color:#7e7e7e"><span id=""><small>Nombre<br>Linea</small></span></label>
          <div class="col-sm-11">
            <div class="form-group" >              
                <input type="text" onkeyup="if(event.keyCode==13){buscarLineaLista('ajax_buscar_linea.php');}" class="form-control" name="buscar_nombre" id="buscar_nombre" style="background-color:#e2d2e0">
            </div>
          </div>
        </div> 
             
      </div>
      <br>  
      <div class="modal-footer">
        <a href="#" class="btn btn-success btn btn-sm" style="background:#732590 !important;" onclick="buscarLineaLista('ajax_buscar_linea.php')"><i class="material-icons">search</i> BUSCAR LINEA</a>
      </div>
    </div>
  </div>
</div>
<!-- modal reenviar solicitud devuelto -->