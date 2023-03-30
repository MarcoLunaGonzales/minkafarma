<script type="text/javascript">
	function printDiv(nombreDiv) {
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;

     document.body.innerHTML = contenido;

     window.print();

     document.body.innerHTML = contenidoOriginal;
}
</script>

<style type="text/css">
	body {color:#000 }
	/*@media print {
      body {
        color:#C2C0C0 !important;
      }
    }*/
</style>
<?php
$estilosVenta=1;
require('conexionmysqli.inc');
// require('funcionesBar.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Modigo de Material
$cod_material = $_GET['cod_material'];

$sqlConf="SELECT ma.codigo_material, ma.descripcion_material, ROUND(p.precio, 2)
            FROM material_apoyo ma 
            LEFT JOIN precios p ON p.codigo_material = ma.codigo_material
            WHERE ma.codigo_material = '$cod_material'
            LIMIT 1";
$respConf=mysqli_query($enlaceCon,$sqlConf);

$nombre_producto = mysqli_result($respConf,0,1);
$code            = mysqli_result($respConf,0,0);
$precio          = mysqli_result($respConf,0,2);

?>
<body>

<table style="margin: auto; width: 80%;">
  <tr>
    <td style="max-width: 50mm; max-height: 30mm;">
        <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px;">
            <div style="font-weight: bold; padding-bottom:5px; font-size:11px;"><?=$nombre_producto;?></div>
            <div style="font-weight: bold; text-align: center;">
                <img src="barcode.php?text=<?=$code;?>&size=40&codetype=Code39&print=true"  style="max-width: 100%; max-height: 100%; display: inline-block;"/>
            </div>
            <div style="font-weight: bold;">P: <?=$precio;?></div>
        </div>
    </td>
    <td style="max-width: 50mm; max-height: 30mm;">
        <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px;">
            <div style="font-weight: bold; padding-bottom:5px; font-size:11px;"><?=$nombre_producto;?></div>
            <div style="font-weight: bold; text-align: center;">
                <img src="barcode.php?text=<?=$code;?>&size=40&codetype=Code39&print=true"  style="max-width: 100%; max-height: 100%; display: inline-block;"/>
            </div>
            <div style="font-weight: bold;">P: <?=$precio;?></div>
        </div>
    </td>
  </tr>
</table>

</div>
</body>


<script type="text/javascript">
 javascript:window.print();
</script>
