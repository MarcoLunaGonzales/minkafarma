<script language='Javascript'>
	function guardar(f){	
		if(f.nombreLinea.value==""){
			alert("El nombre no puede estar vacio.");
			return(false);
		}
		f.submit();
	}
</script>
<?php
require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

require("../../funcion_nombres.php");



echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codLinea=$_GET["codigo_registro"];
$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($enlaceCon,$codProveedor);

$sqlLineas="select cod_linea_proveedor, nombre_linea_proveedor, abreviatura_linea_proveedor, contacto1, contacto2, cod_proveedor, estado, 
cod_procedencia, margen_precio from proveedores_lineas where cod_linea_proveedor='$codLinea' and cod_proveedor='$codProveedor'";
$respLineas=mysqli_query($enlaceCon,$sqlLineas);
while($datLineas=mysqli_fetch_array($respLineas)){
	$nombreLineaX=$datLineas[1];
	$abreviaturaLineaX=$datLineas[2];
	$contacto1X=$datLineas[3];
	$contacto2X=$datLineas[4];
	$codProcedenciaX=$datLineas[7];
	$margenPrecioX=$datLineas[8];
}

?>
<form action="guardaEditarLineaDistribuidor.php" method="post">

<input type="hidden" name="codProveedor" id="codProveedor" value="<?php echo $codProveedor?>">
<input type="hidden" name="codLinea" id="codLinea" value="<?php echo $codLinea?>">

<center>
    <br/>
    <h1>Editar Linea<br>Distribuidor - <?php echo $nombreProveedor;?></h1>
    <table class="texto">
        <tr>
            <th>Nombre</th>
            <th>Abreviatura</th>
        </tr>
        <tr>
            <td><input type="text" id="nombreLinea" name="nombreLinea" size="50" value="<?php echo $nombreLineaX; ?>"/></td>
            <td><input type="text" id="abreviatura" name="abreviatura" size="20" value="<?php echo $abreviaturaLineaX; ?>"/></td>
        </tr>
        <tr>
            <th>Procedencia</th>
            <th>Margen de precio</th>
        </tr>
        <tr>
			<td>
			<select name="cod_procedencia" id="cod_procedencia">
		<?php
			$sqlProc="select cod_procedencia, nombre_procedencia from  tipos_procedencia";
			$respProc=mysqli_query($enlaceCon, $sqlProc);
			while($datProc=mysqli_fetch_array($respProc,MYSQLI_NUM)){
  				$codProcedencia=$datProc[0];
				$nombreProcedencia=$datProc[1];
				if($codProcedencia==$codProcedenciaX){
					echo "<option value='$codProcedencia' selected>$nombreProcedencia</option>";	
				}else{
					echo "<option value='$codProcedencia'>$nombreProcedencia</option>";
				}
			}
		?>
            </select>
			</td>
            <td><input type="number" id="margen" name="margen" min="1" max="100" value="<?php echo $margenPrecioX;?>" step="1"/></td>
        </tr>
		<tr>
            <th>Contacto 1 (Nombre - Telefono)</th>
            <th>Contacto 2 (Nombre - Telefono)</th>
        </tr>
        <tr>
            <td><input type="text" id="contacto1" name="contacto1" size="80" value="<?php echo $contacto1X;?>"/></td>
            <td><input type="text" id="contacto2" name="contacto2" size="80" value="<?php echo $contacto2X;?>"/></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="guardar(this.form);" />
    <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegadorLineasDistribuidores.php?codProveedor=<?php echo $codProveedor;?>'" />
</div>
</form>