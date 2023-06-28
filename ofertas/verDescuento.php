<html>
<head>
  <meta charset="utf-8" />
  <title>Farmacias Bolivia</title>
    <link rel="shortcut icon" href="imagenes/icon_farma.ico" type="image/x-icon">
  <link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>
<?php
require_once '../conexionmysqli.inc';
require_once 'configModule.php';
require_once '../funciones.php';

$globalSucursal=$_COOKIE['global_agencia'];

$sql=mysqli_query($enlaceCon,"SELECT n.nombre, n.abreviatura,n.desde,n.hasta,e.nombre as estado,n.por_linea from $table n join estados_descuentos e on e.codigo=n.cod_estadodescuento where n.codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$abreviatura=$dat[1];
$desde=strftime('%Y-%m-%d',strtotime($dat[2]));
$hasta=strftime('%Y-%m-%d',strtotime($dat[3]));
$desdeFormato=strftime('%d/%m/%Y',strtotime($dat[2]));
$hastaFormato=strftime('%d/%m/%Y',strtotime($dat[3]));
$desde_hora=strftime('%H:%M',strtotime($dat[2]));
$hasta_hora=strftime('%H:%M',strtotime($dat[3]));
$estado=$dat['estado'];
$por_linea=$dat['por_linea'];
$tipoDescuentoDescripcion="Productos con Descuento";
if($por_linea==1){
  $tipoDescuentoDescripcion="Líneas con Descuento";
}
?>
<div id="logo_carga" class="logo-carga" style="display:none;"></div>
<div class="content">
	<div id="contListaGrupos" class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
			  <div class="card">
				<div class="card-header card-header-deafult card-header-text text-center card-header-primary">
					<div class="card-text">
					  <h4 class="card-title"><b>DETALLE DEL DESCUENTO</b></h4>
					</div>
				</div>
				<div class="card-body">
					<div class=""> 	
					<div class="row" id="">		
              <label class="col-sm-1 col-form-label" style="color:#000000; ">Nombre Desc.:</label>
<div class="col-sm-5">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$nombre?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div>  
<label class="col-sm-1 col-form-label" style="color:#000000; ">Descuento % :</label>
<div class="col-sm-1">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$abreviatura?>" style="background-color:#E3CEF6;text-align: left">
  </div>
</div>  
</div>
<div class="row">
<label class="col-sm-1 col-form-label" style="color:#000000; ">Del :</label>
<div class="col-sm-2">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$desdeFormato?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div>  
<label class="col-sm-1 col-form-label" style="color:#000000; ">H:M</label>
<div class="col-sm-1">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$desde_hora?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div> 

<label class="col-sm-1 col-form-label" style="color:#000000; ">Al :</label>
<div class="col-sm-2">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$hastaFormato?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div> 
<label class="col-sm-1 col-form-label" style="color:#000000; ">H:M</label>
<div class="col-sm-1">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$hasta_hora?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div> 
<label class="col-sm-1 col-form-label" style="color:#000000; ">Estado</label>
<div class="col-sm-1">
  <div class="form-group">
  	<input type="text" class="form-control" readonly="true" value="<?=$estado?>" style="background-color:#E3CEF6;text-align: left" >
  </div>
</div>
                    </div>
                    <br><br>
 <div class="col-sm-12 div-center"><center><h3>Días del Descuento</h3></center></div>
<?php 
$sql="select d.codigo,(SELECT cod_dia from tipos_precio_dias where cod_tipoprecio=$codigo_registro and cod_dia=d.codigo) from dias d where d.estado=1 order by 1";
$resp=mysqli_query($enlaceCon,$sql);
$index=0;
?><table class='table'><tr><?php
  while($dat=mysqli_fetch_array($resp))
  {
    $index++;    
    $dias=obtenerNombreDiaCompleto($dat[0]);
    $estilo="btn-default btn-sm";
    if($dat[1]>0){
         $estilo="btn-info btn-lg";
    }
    ?><th class=''><a class='btn <?=$estilo?> text-white'><i class='material-icons'>today</i><?=$dias?></a></th><?php  
  }
  ?></tr></table>
          <br>
          <hr>
          <div class="row">
            <div class="col-sm-3">
					<div class="col-sm-12 div-center"><center><h3>Sucursales Beneficiadas</h3></center></div>
					<div class="col-sm-12 div-center">	
						<table class="table table-bordered">
							<thead>
								<tr class="text-dark bg-plomo">
									<th class="text-right text-white" style="background:#741C89;">#</th>
                  <th class=" text-white" style="background:#741C89;">Sucursal</th>
									<!-- <th class=" text-white" style="background:#741C89;">Dirección</th> -->
								</tr>
							</thead>
							<tbody>
							<?php 
   $sql="select d.cod_ciudad,(SELECT cod_ciudad from tipos_precio_ciudad where cod_tipoprecio=$codigo_registro and cod_ciudad=d.cod_ciudad),d.direccion,d.descripcion from ciudades d where d.cod_estadoreferencial=1 order by descripcion";
   $resp=mysqli_query($enlaceCon,$sql);
   $index=0;
  while($dat=mysqli_fetch_array($resp))
  {      
    $ciudades=$dat['descripcion'];
    $checked="";
    $direccion=$dat['direccion'];
    if($dat[1]>0){
      $index++; 
      echo "<tr>
       <td>$index</td>
       <td ><b>$ciudades</b></td>       
      </tr>";  //width='50%' <td class='text-muted'><small>$direccion</small></td> 
    }
  }
              ?>
							</tbody>
						</table>
					</div>  

          </div>    
          <div class="col-sm-9">
          <div class="col-sm-12 div-center"><center><h3><?=$tipoDescuentoDescripcion?></h3></center></div>
          <div class="col-sm-12 div-center">  
            <table class="table table-bordered">
              <thead>
                <tr class="text-dark bg-plomo">
                  <th class="text-right text-white" style="background:#3498DB;">#</th>
                  <th class=" text-white" style="background:#3498DB;">Proveedor</th>
                  <th class=" text-white" style="background:#3498DB;">Líneas</th>
                  <?php 
                    if($por_linea!=1){
                      ?> <th class=" text-white" style="background:#3498DB;">Productos</th>
                         <th class=" text-white" style="background:#3498DB;">Precio Actual</th>
                         <th class=" text-white" style="background:#3498DB;">% Desc</th>
                         <th class=" text-white" style="background:#3498DB;">Precio Final</th><?php
                    }
                  ?>
                </tr>
              </thead>
              <tbody>
              <?php 
  if($por_linea==1){
    $sql="select t.cod_linea_proveedor,d.nombre_linea_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor) 
      from tipos_precio_lineas t join proveedores_lineas d 
      on d.cod_linea_proveedor=t.cod_linea_proveedor
      where t.cod_tipoprecio=$codigo_registro and d.estado=1 order by 2,3 ";
   $resp=mysqli_query($enlaceCon,$sql);
   $index=0;
   while($dat=mysqli_fetch_array($resp))
   {
    $index++;   
    $lineas=$dat[1];
    $proveedor=$dat[2]; 
      echo "<tr>
       <td>$index</td>
       <td>$proveedor</td>
       <td>$lineas</td>
      </tr>";   
   }
}else{
   $sql="select t.cod_material,d.descripcion_material,(SELECT nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor) as nombre_linea_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=(SELECT cod_proveedor from proveedores_lineas where cod_linea_proveedor=d.cod_linea_proveedor)) as proveedor,t.porcentaje_material 
      from tipos_precio_productos t join material_apoyo d 
      on d.codigo_material=t.cod_material
      where t.cod_tipoprecio=$codigo_registro and d.estado=1 order by 3,2 ";
   $resp=mysqli_query($enlaceCon,$sql);
   $index=0;
   while($dat=mysqli_fetch_array($resp))
   {
    $index++;   
    $lineas=$dat[2];
    $proveedor=$dat[3]; 
    $producto=$dat[1];
    $porcentDesc=$dat['porcentaje_material'];
    $precio=number_format(precioProductoSucursalCalculado($enlaceCon,$dat[0],$globalSucursal),2,'.','');
    $precioFin=number_format($precio*((100-$porcentDesc)/100),2,'.','');

      echo "<tr>
       <td><small>$index</small></td>
       <td><small>$proveedor</small></td>
       <td><small>$lineas</small></td>
       <td><small>($dat[0]) $producto</small></td>
       <td class='text-info'>$precio</td>
       <td class='text-success'><b>$porcentDesc%</b></td>
       <td class='text-info'>$precioFin</td>
      </tr>";   
   }
}
   
   
              ?>
              </tbody>
            </table>
          </div>  
          
          </div>    
          </div> 

          <div class="row col-sm-12">
         
				  	<div class="card-footer fixed-bottom col-sm-12">						
						<!--<a href="#" class="btn btn-danger">Volver</a>-->
				  	</div>
				 </div>
			    </div><!--div end card-->			
               </div>
            </div>
	</div>
</div>

</body>
</html>