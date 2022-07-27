<?php 
require("../conexionmysqli.inc");
require('../function_formatofecha.php');
require("../funciones.php");
require("../funcion_nombres.php");


   
error_reporting(E_ALL);
ini_set('display_errors', '1');



//$correosCopia=obtenerCorreosListaCopia();
$correosCopia="";//jfernandez@farmciasbolivia.com.bolivia
$existePedidos=0;
$datos=$_GET["datos"];
$stringMensajeInter="";
$stringDetallePedido="";
$tituloMensajeEnvio="";
$idProveedor=0;

$consulta = "SELECT i.cod_salida_almacenes, i.fecha, i.hora_salida, i.razon_social, i.nro_correlativo, i.salida_anulada,(select p.nombre_cliente from clientes p where p.cod_cliente=i.cod_cliente) as cliente,i.cod_cliente,i.cod_chofer,i.siat_fechaemision,i.siat_cuf,i.monto_final FROM salida_almacenes i WHERE i.cod_almacen='$global_almacen' and i.salida_anulada!=1 and i.cod_salida_almacenes in ($datos) ";             
$consulta = $consulta."";
            //echo $consulta;
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
  $cod_salida_almacenes = $dat['cod_salida_almacenes'];
  // $fecha_salida = $dat[1];
  // $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
  $fecha_salida_mostrar=date("d-m-Y",strtotime($dat['siat_fechaemision']));

  $obs_salida = nombreVisitador($enlaceCon,$dat['cod_chofer']);
  $nro_correlativo = $dat[4];
  $proveedor=$dat['cliente'];
  $idProveedor=$dat['cod_cliente'];
  if($idProveedor==146){
    $proveedor="";
  }
  $cuf=$dat['siat_cuf'];
  $monto_final=number_format($dat['monto_final'],2,'.',',');
  $existePedidos++;

  //datos pedido
  $lineasPedido="";$hijos="";
  $stringDetallePedido.='
  <li class="d-flex" style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;font-size:12px;">
    <span class="material-icons text-white mr-2">double_arrow</span>    
    <span class="text text-white" >Codigo de Autorización: '.$cuf.'</span>
  </li>
  <li class="d-flex" style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;font-size:12px;">
    <span class="material-icons text-white mr-2">double_arrow</span>
    <span class="text text-white">Nro. Factura: '.$nro_correlativo.'</span>
  </li>
  <li class="d-flex" style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;font-size:12px;">
    <span class="material-icons text-white mr-2">double_arrow</span>
    <span class="text text-white">Cliente: '.$proveedor.'</span>
  </li>
  <li class="d-flex" style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;font-size:12px;">
    <span class="material-icons text-white mr-2">double_arrow</span>
    <span class="text text-white">Importe: '.$monto_final.'</span>
  </li><label>'.$lineasPedido.'</label>
  <label> Fecha Factura '.$fecha_salida_mostrar.', '.$obs_salida.'</label><div id="adjunto_excel'.$cod_salida_almacenes.'"></div><div id="adjunto_excel_csv'.$cod_salida_almacenes.'"></div><script type="text/javascript">cargarDocumentosExcel('.$cod_salida_almacenes.',"'.$hijos.'");cargarDocumentosCsv('.$cod_salida_almacenes.',"'.$hijos.'");</script>';
  $stringMensajeInter.="Nro. ".$nro_correlativo;           
}
$stringMensaje="Estimado Cliente ".$proveedor.":
 
Adjuntamos la factura ".$stringMensajeInter;


$stringMensaje.=".
 
Gracias por su Compra!";


$correosProveedor=obtenerCorreosListaCliente($idProveedor);
$tituloMensajeEnvio="FACTURA ".$stringMensajeInter;


//lista de historicos
echo "<script>var array_correos=[];</script>";
$i=0;
//$resp=obtenerCorreosListaHistorico($idProveedor);
while($detalle=mysqli_fetch_array($resp)){  
       $codigoX=$detalle[0];
       $correoX=strtolower($detalle[1]);
       ?>
            <script>
             var obtejoLista={
               label:'<?=$correoX?>',
               value:'<?=$codigoX?>'};
               array_correos[<?=$i?>]=obtejoLista;
            </script>
            <?php

      $i=$i+1;
}  


?>
<style type="text/css">
  .bootstrap-tagsinput input{
    width:250px !important;
    height: 30px; 
    background: #FFF;
    border-radius: 5px;
    background: #B3F9ED;
}
.bootstrap-tagsinput input:focus{
    background: #B3F9ED;
}

.bootstrap-tagsinput .tag{
    background: #1CDABB !important;
    color: #0D3B86 !important;
    text-transform: lowercase !important; 
    font-size: 14px;
}

</style>
<script type="text/javascript">
  function cargarDocumentosExcel(codigo,tipo){
    var url_excel="../descargarFacturaXml.php";
    var parametros={"codVenta":codigo,"email":1};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: url_excel,
        data: parametros,
        beforeSend:  function () {                                            
           $("#adjunto_excel"+codigo).html("<small class='text-info'>Cargando Adjunto XML...</small>");
        },success:  function (resp) {                     
           $("#adjunto_excel"+codigo).html("<small>"+resp+"</small>");
           $("#adjuntos_texto").val($("#adjuntos_texto").val()+""+resp+",");                       
           //alert(resp);               
        }
      }); 
  }

  function cargarDocumentosCsv(codigo,tipo){
    var url_excel="../descargarFacturaPDF.php";
    var parametros={"codigo_salida":codigo,"email":1};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: url_excel,
        data: parametros,
        beforeSend:  function () {                                            
           $("#adjunto_excel_csv"+codigo).html("<small class='text-success'>Cargando Adjunto PDF...</small>");
        },success:  function (resp) {                     
           $("#adjunto_excel_csv"+codigo).html("<small>"+resp+"</small>"); 
           $("#adjuntos_texto_csv").val($("#adjuntos_texto_csv").val()+""+resp+",");                                             
           //alert(resp);               
        }
      }); 
  }

</script>
<html lang="es">
  <head>
    <title>Enviar Correo</title>
    <link rel="shortcut icon" href="../imagenes/icon_farma.ico" type="image/x-icon">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="fonts/icomoon/style.css">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">
    <link href="../assets/autocomplete/awesomplete.css" rel="stylesheet" />
  </head>
  <body>
  

  <div class="content">
    
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap">
        <div class="col-md-8">
          <div class="form h-100">
            <h3>Enviar Factura - Clientes</h3>
            <?php 
            $existePedidos=1;
            if($existePedidos>0){
              ?>
            <form class="mb-5" method="post" id="contactForm" name="contactForm">
              <div class="row">
                <div class="col-md-12 form-group mb-1">
                  <label for="" class="col-form-label">Para</label>
                  <input type="text" class="form-control tagsinput" name="name" id="name" style="font-size: 15px;color:#900C3F;" data-role="tagsinput" value="<?=$correosProveedor?>" placeholder="cliente@example.com">
                  <input type="hidden" id="correo_autocompleteids">
                  <input type="hidden" id="adjuntos_texto" name="adjuntos_texto">
                  <input type="hidden" id="adjuntos_texto_csv" name="adjuntos_texto_csv">
                  <input type="hidden" name="titulo_pedido_email" value="<?=$tituloMensajeEnvio?>">
                  <input type="hidden" name="idproveedor" value="<?=$idProveedor?>">
                  <input type="hidden" name="cod_pedido_string" value="<?=$datos?>">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="" class="col-form-label">CC</label>
                  <input type="text" class="form-control tagsinput" name="email" id="email"  style="font-size: 15px;color:#900C3F;" data-role="tagsinput" placeholder="cc@example.com" value="<?=$correosCopia?>">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group mb-5">
                  <label for="message" class="col-form-label">Mensaje</label>
                  <textarea class="form-control" name="message" id="message" cols="30" rows="10"  style="font-size: 14px;color:#595A5D;background: #E5E6E8;" placeholder="Ingrese su Mensaje" readonly><?=$stringMensaje?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <input type="submit" value="ENVIAR CORREO" class="btn btn-primary rounded-0 py-2 px-4">
                  <a class="btn btn-success" href="../formatoFacturaOnLine.php?codVenta=<?=$datos?>">Imprimir Factura</a>
                  <span class="submitting"></span>
                </div>
              </div>
            </form>
              <?php
            }else{
              ?><p> No se encontró el pedido.</p><?php
            }?>

            <div id="form-message-warning">
            </div> 
            <div id="form-message-success">
              Tú correo fué enviado con éxito, gracias.
              <img src="../imagenes/logo.gif" >
              <a class="btn btn-success" href="../formatoFacturaOnLine.php?codVenta=<?=$datos?>">Imprimir Factura</a>
            </div>
            <p>Dpto. Sistemas ©TUADMIN_<?=date("Y")?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info h-100">
            <h3>Información Factura</h3>
            <ul class="list-unstyled">          
             <?=$stringDetallePedido?>
            </ul>   
            <!-- <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias, magnam!</p> -->
            <hr>  
            <!-- <p class="mb-1">N. <?=$nro_correlativo?> - <?=$proveedor?> </p><label> Fecha Pedido <?=$fecha_salida_mostrar?>, <?=$obs_salida?></label> -->
            <ul class="list-unstyled">
              <li class="d-flex">
                <span class="wrap-icon icon-envelope mr-3"></span>
                <!-- <span class="text">siscobofar@gmail.com</span> -->
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
    
    
<script src="../assets/autocomplete/awesomplete.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>
    

    <script type="text/javascript">
function myItemFunc(text, input){
  return Awesomplete.$.create("li", {
    innerHTML: createItem(text,input),
    "aria-selected": "false"
  });
}
function createItem(text, input){
  var img = document.createElement("img");
  img.style.height = '25px';
  img.src = '../imagenes/user.png';
  var html = img.outerHTML + " <small>" + text.label+"</small>";
  return html;
}


  function autocompletarConImagen(inp,inp_val,arr){
   var input = document.getElementById(inp);
       var input_value = document.getElementById(inp_val);
         new Awesomplete(input, {
          item: myItemFunc,
          minChars: 1,
          maxItems: 10,
          autoFirst:true,
          list: arr,
          tabSelect:true,
          replace: function(suggestion) {
              input_value.value = suggestion.value;
              this.input.value = suggestion.label;
              $("#"+inp).blur();
           }
          });
 }
     $(document).ready(function(){
      var i=0;
      $(".bootstrap-tagsinput input").each(function(){
            i++;
            $(this).attr("id","tag_inputcorreo"+i);
      });
      
      autocompletarConImagen("tag_inputcorreo1","correo_autocompleteids",array_correos);
      autocompletarConImagen("tag_inputcorreo2","correo_autocompleteids",array_correos);
       //$(".bootstrap-tagsinput input").attr("id","tag_inputcorreo");
     });
    </script>
  </body>
</html>