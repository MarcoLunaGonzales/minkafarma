<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
function enviar_form(f)
{   f.submit();
}
function ver_detalle(f,indice)
{   var material,cadena,ind,codigo_material;
    var i;
    cadena='materiales'+indice;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].name==cadena)
        {   codigo_material=f.elements[i].value;
            ind=i+1;
        }
    }
    window.open('registrar_salidadetallemuestras.php?codigo_muestra='+codigo_material+'&indice='+ind+'','_blank',toolbar=0);
}
function validar(f)
{   var i,j,cantidad_material;
    variables=new Array(f.length-1);
    vector_material=new Array(30);
    vector_nrolote=new Array(30);
    vector_cantidades=new Array(30);
    vector_precios=new Array(30);
    vector_fechavenci=new Array(30);
    var indice,fecha, tipo_salida, almacen, funcionario, observaciones;
    fecha=f.fecha.value;
    tipo_salida=f.tipo_salida.value;
    observaciones=f.observaciones.value;
    cantidad_material=f.cantidad_material.value;
    almacen=f.almacen.value;
    funcionario=f.funcionario.value;
    territorio=f.territorio.value;
    if(f.fecha.value=='')
    {   alert('El campo Fecha esta vacio.');
        f.fecha.focus();
        return(false);
    }
    if(f.territorio.value=='')
    {   alert('El campo Territorio esta vacio.');
        f.territorio.focus();
        return(false);
    }
    if(f.almacen.value=='' && f.funcionario.value=='')
    {   alert('Al menos uno de los siguientes campos debe ser llenado (Almacen Destino, Funcionario).');
        f.focus();
        return(false);
    }
    for(i=6;i<=f.length-2;i++)
    {   variables[i]=f.elements[i].value;
        if(f.elements[i].value=='')
        {   alert('Algun elemento no tiene valor');
            return(false);
        }
    }
    indice=0;
    for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('materiales')!=-1)
        {   vector_material[indice]=f.elements[j].value;
            indice++;
        }
    }
    indice=0;
    for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('fecha_vencimiento')!=-1)
        {   vector_fechavenci[indice]=f.elements[j].value;
            indice++;
        }
    }
    indice=0;
    for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('cantidad_unitaria')!=-1)
        {   vector_cantidades[indice]=f.elements[j].value;
            indice++;
        }
    }
    indice=0;
	for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('precio_unitario')!=-1)
        {   vector_precios[indice]=f.elements[j].value;
            indice++;
        }
    }
	
    var buscado,cant_buscado;
    /*for(k=0;k<=indice;k++)
    {   buscado=vector_material[k];
        cant_buscado=0;
        for(m=0;m<=indice;m++)
        {   if(buscado==vector_material[m])
            {   cant_buscado=cant_buscado+1;
            }
        }
        if(cant_buscado>1)
        {   alert('Los Materiales no pueden repetirse.');
            return(false);
        }
    }*/
	
    for(k=1;k<=document.getElementById("totalmat").value;k++)
    {   stock_unitario=document.getElementById("stock"+k).value;
        cant_unitaria =document.getElementById("cantidad_unitaria"+k).value;
        if(cant_unitaria > stock_unitario)
        {   alert('No puede sacar cantidades superiores a lo que se tiene en stock.'+'_'+stock_unitario+'_'+cant_unitaria);
            document.getElementById("cantidad_unitaria"+k).focus();
            return(false);
        }
    }

    location.href='guarda_salidamateriales.php?vector_material='+vector_material+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&vector_precios='+vector_precios+'&fecha='+fecha+'&tipo_salida='+tipo_salida+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&almacen='+almacen+'&funcionario='+funcionario+'&territorio='+territorio+'';
}
function actStock(ind)
{   var codmat=document.getElementById("idmat"+ind).value;
    var codalm=document.getElementById("codalmacen").value;
    pnlAjax("idstock"+ind,"programas/salidas/ajaxStockSalidaMateriales.php","codmat="+codmat+"&codalm="+codalm+"&indice="+ind);
	
}

function actPrecio(ind)
{   var codmat=document.getElementById("idmat"+ind).value;
	var codTipoPrecio=document.getElementById("precio").value;
    pnlAjax("idPrecio"+ind,"programas/salidas/ajaxPrecioMaterial.php","codmat="+codmat+"&codTipoPrecio="+codTipoPrecio+"&indice="+ind);
}

function calculaMontoMaterial(indice){
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	alert(cantidadUnitaria);
	//var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario1").value;
	var montoUnitario=parseFloat(cantidadUnitaria)*parseFloat(precioUnitario);
	document.getElementById("montoMaterial"+indice).innerHTML=montoUnitario;
	
	totales();
}

function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var subtotal=0;
	for(var i=2; i<=numFilas-2; i++){
		var dato=parseFloat(main.rows[i].cells[5].firstChild.innerHTML);
		subtotal=subtotal+dato;
	}
	document.getElementById("montoTotalVenta").innerHTML=subtotal;
}
function cargarMontos(){
	var main=document.getElementById('main');   
	var numFilas=main.rows.length;
	var subtotal=0;
	for(var i=2; i<=numFilas-2; i++){
		var j=i-1;
		var cantidadUnitaria=document.getElementById("cantidad_unitaria"+j).value;
		var precioUnitario=document.getElementById("precio_unitario"+j).value;
		var montoUnitario=parseFloat(cantidadUnitaria)*parseFloat(precioUnitario);
		
		document.getElementById("montoMaterial"+j).innerHTML=montoUnitario;
		var dato=parseFloat(main.rows[i].cells[5].firstChild.innerHTML);
		subtotal=subtotal+montoUnitario;
	}
	document.getElementById("montoTotalVenta").innerHTML=subtotal;
}
</script>
<?php
echo "<body onLoad='cargarMontos()'>";
require("conexion.inc");
if($global_tipoalmacen==1)
{   require("estilos_almacenes_central.inc");
}
else
{   require("estilos_almacenes.inc");
}
if($fecha=="")
{   $fecha=date("d/m/Y");
}
$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
echo "<form action='' method='get'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Salida de Almacen</th></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>Numero de Salida</th><th>Fecha</th><th>Tipo de Salida</th><th>Territorio Destino</th><th>Almacen Destino</th></tr>";
echo "<tr>";
echo "<td align='center'>$codigo</td>";
echo "<td align='center'>";

echo "<input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo "<img id='imagenFecha' src='imagenes/fecha.bmp'>";

echo "</td>";
$sql1="select cod_tiposalida, nombre_tiposalida from tipos_salida where tipo_almacen='$global_tipoalmacen' order by nombre_tiposalida";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_salida' class='texto' onchange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while($dat1=mysql_fetch_array($resp1))
{   $cod_tiposalida=$dat1[0];
    $nombre_tiposalida=$dat1[1];
    if($cod_tiposalida==$tipo_salida)
    {   echo "<option value='$cod_tiposalida' selected>$nombre_tiposalida</option>";
    }
    else
    {   echo "<option value='$cod_tiposalida'>$nombre_tiposalida</option>";
    }
}
echo "</select></td>";
$sql1="select * from ciudades order by descripcion";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='territorio' class='texto' OnChange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while($dat1=mysql_fetch_array($resp1))
{   $cod_ciudad=$dat1[0];
    $nombre_ciudad=$dat1[1];
    if($territorio==$cod_ciudad)
    {   echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
    }
    else
    {   echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
    }
}
echo "</select></td>";
$sql3="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$territorio' order by nombre_almacen";
$resp3=mysql_query($sql3);
echo "<td align='center'><select name='almacen' class='texto'>";
while($dat3=mysql_fetch_array($resp3))
{   $cod_almacen=$dat3[0];
    $nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
    if($almacen==$cod_almacen)
    {   echo "<option value='$cod_almacen' selected>$nombre_almacen</option>";
    }
    else
    {   echo "<option value='$cod_almacen'>$nombre_almacen</option>";
    }
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th colspan=2>Cliente</th><th colspan=2>Observaciones</th><th>Precio Venta</th></tr>";

    $sql2="select c.`cod_cliente`, c.`nombre_cliente` from clientes c where c.`cod_area_empresa`=$territorio";
    $resp2=mysql_query($sql2);
	
echo "<tr><td align='center' colspan=2><select name='funcionario' class='texto'>";
echo "<option value=''>--Cliente--</option>";
while($dat2=mysql_fetch_array($resp2))
{   $cod_funcionario=$dat2[0];
    $nombre_funcionario="$dat2[1] $dat2[2] $dat2[3]";
    $cargo=$dat2[4];
    if($cod_funcionario==$funcionario)
    {   echo "<option value='$cod_funcionario' selected>$nombre_funcionario</option>";
    }
    else
    {   echo "<option value='$cod_funcionario'>$nombre_funcionario</option>";
    }
}
echo "</select></td>";
echo "<td align='center' colspan=2><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td>";
echo "<td><select name='precio' id='precio' class='texto'>
	<option value='1'>Precio A</option>
	<option value='2'>Precio B</option>
	<option value='3'>Precio C</option>
	<option value='4'>Precio F</option>
</select></td>";

echo "</tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='70%' align='center' cellspacing='0' cellpadding='0' id='main'>";
echo "<tr><th colspan='6'>Cantidad de Materiales a sacar: <select name='cantidad_material' onchange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=40;$i++)
{   if($i==$cantidad_material)
    {   echo "<option value='$i' selected>$i</option>";
    }
    else
    {   echo "<option value='$i'>$i</option>";
    }
}
echo "</select></th></tr>";
echo "<tr><th>&nbsp;</th><th>Material</th><th>Stock</th><th>Cantidad Unitaria</th><th>Precio</th><th>Monto</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{   echo "<tr>";
    echo "<td align='center'>$indice_detalle</td>";
    echo "<td align='center'>";
    $sql_materiales="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
    $resp_materiales=mysql_query($sql_materiales);
    //obtenemos los valores de las variables creadas en tiempo de ejecucion
    $var_material="materiales$indice_detalle";
    $valor_material=$$var_material;
    echo "<select id='idmat$indice_detalle' name='materiales$indice_detalle' class='textomini' onchange='javascript:actPrecio($indice_detalle); javascript:actStock($indice_detalle);'>";
    echo "<option value='0'></option>";
    while($dat_materiales=mysql_fetch_array($resp_materiales))
    {   $cod_material=$dat_materiales[0];
        $nombre_material=$dat_materiales[1];
        if($cod_material==$valor_material)
        {   echo "<option value='$cod_material' selected>$nombre_material</option>";
        }
        else
        {   echo "<option value='$cod_material'>$nombre_material</option>";
        }
    }
    echo "</select>";
    echo "</td>";
    $var_cant_unit="cantidad_unitaria$indice_detalle";
    $valor_cant_unit=$$var_cant_unit;
	
	$var_precio_unit="precio_unitario$indice_detalle";
	$valor_precio_unit=$$var_precio_unit;
	
    $sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
    where id.cod_material='$valor_material' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$global_almacen'";
    $resp_stock=mysql_query($sql_stock);
    $dat_stock=mysql_fetch_array($resp_stock);
    $stock_real=$dat_stock[0];
    if($stock_real=="")
    {   $stock_real=0;
    }
    echo "<td align='center'>";
    echo "<div id='idstock$indice_detalle'>";
    echo "<input type='text' id='stock$indice_detalle' name='stock$indice_detalle' value='$stock_real' readonly>";
    echo "</div>";
    echo "</td>";
    echo "<td align='center'><input type='text' id='cantidad_unitaria$indice_detalle' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto'></td>";

    echo "<td align='center'>";
	echo "<div id='idPrecio$indice_detalle'>";
    
	//<input type='text' id='precio_unitario$indice_detalle' name='precio_unitario$indice_detalle' value='$valor_precio_unit' class='texto' onChange='calculaMontoMaterial($indice_detalle)';>
	echo "</td>";
	echo "<td><div id='montoMaterial$indice_detalle'>0</div></td>";
    //
    //
    echo "</tr>";
}
echo "<tr><th colspan='5'>Monto Total</th><th><div id='montoTotalVenta'>0</div></th></tr>";
echo "</table><br>";
echo "<input type='hidden' id='totalmat' value='$cantidad_material'>";
echo "<input type='hidden' id='codalmacen' value='$global_almacen'>";
echo "<table align='center'><tr><td><a href='navegador_salidamuestras.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center>";
//echo "<input type='button' class='boton' value='Actualizar' onClick='enviar_form(this.form)'>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>";
echo "</center>";
echo "</form>";
echo "</div></body>";

?>
