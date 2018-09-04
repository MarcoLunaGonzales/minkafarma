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
require("../../conexionmysqli.inc");

require("../../estilos_almacenes.inc");

require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($codProveedor);

?>
<form action="guardaLineaDistribuidor.php" method="post">
<input type="hidden" name="codProveedor" id="codProveedor" value="<?php echo $codProveedor?>">
<center>
    <br/>
    <h1>Adicionar Linea<br>Distribuidor - <?php echo $nombreProveedor;?></h1>
    <table class="texto">
        <tr>
            <th>Nombre</th>
            <th>Abreviatura</th>
        </tr>
        <tr>
            <td><input type="text" id="nombreLinea" name="nombreLinea" size="50"/></td>
            <td><input type="text" id="abreviatura" name="abreviatura" size="20"/></td>
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
				echo "<option value='$codProcedencia'>$nombreProcedencia</option>";
			}
		?>
            </select>
			</td>
            <td><input type="number" id="margen" name="margen" min="1" max="100" value="15" step="1"/></td>
        </tr>
		<tr>
            <th>Contacto 1 (Nombre - Telefono)</th>
            <th>Contacto 2 (Nombre - Telefono)</th>
        </tr>
        <tr>
            <td><input type="text" id="contacto1" name="contacto1" size="80"/></td>
            <td><input type="text" id="contacto2" name="contacto2" size="80"/></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="guardar(this.form);" />
    <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegadorLineasDistribuidores.php?codProveedor=<?php echo $codProveedor;?>'" />
</div>
</form>