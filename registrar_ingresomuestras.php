<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type='text/javascript' language='javascript'>
function enviar_form(f)
{   f.submit();
}
function validar(f)
{   var i,j,cantidad_material,nombre_tipo, nota_entrega;
    variables=new Array(f.length-1);
    vector_material=new Array(100);
    vector_nrolote=new Array(100);
    vector_fechavenci=new Array(100);
    vector_cantidades=new Array(100);
    vector_tipomaterial=new Array(100);
    vector_precio=new Array(100);
    vector_neto=new Array(100);
    var indice,fecha, tipo_ingreso, observaciones, nro_factura;
    fecha=f.fecha.value;
    tipo_ingreso=f.tipo_ingreso.value;
    nota_entrega=f.nota_entrega.value;
    nro_factura=f.nro_factura.value;
    observaciones=f.observaciones.value;
    cantidad_material=f.cantidad_material.value;
    if(f.fecha.value=='')
    {   alert('El campo Fecha esta vacio.');
        f.fecha.focus();
        return(false);
    }
    if(f.nota_entrega.value=='')
    {   alert('El campo Nota de Entrega esta vacio.');
        f.nota_entrega.focus();
        return(false);
    }
    for(i=4;i<=f.length-2;i++)
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
    {   if(f.elements[j].name.indexOf('nrolote')!=-1)
        {   vector_nrolote[indice]=f.elements[j].value;
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
    //
    indice=0;
    for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('precio')!=-1)
        {   vector_precio[indice]=f.elements[j].value;
            indice++;
        }
    }
    indice=0;
    for(j=0;j<=f.length-1;j++)
    {   if(f.elements[j].name.indexOf('neto')!=-1)
        {   vector_neto[indice]=f.elements[j].value;
            indice++;
        }
    }
    //
    var buscado,cant_buscado;
    for(k=0;k<=indice;k++)
    {   buscado=vector_nrolote[k];
        cant_buscado=0;
        for(m=0;m<=indice;m++)
        {   if(buscado==vector_nrolote[m])
            {   cant_buscado=cant_buscado+1;
            }
        }
        if(cant_buscado>1)
        {   alert('Los Numero de Lote no pueden repetirse.');
            return(false);
        }
    }
    location.href='guarda_ingresomuestras.php?vector_material='+vector_material+'&vector_nrolote='+vector_nrolote+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&vector_precio='+vector_precio+'&vector_neto='+vector_neto+'&fecha='+fecha+'&tipo_ingreso='+tipo_ingreso+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&vector_tipomaterial='+vector_tipomaterial+'&nota_entrega='+nota_entrega+'&nro_factura='+nro_factura+'';
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
}
	</script>
<?php

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
echo "<form action='' method='post'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Ingreso de Muestras</th></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota de Entrega</th><th>Factura</th></tr>";
$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' and grupo_ingreso='1' order by cod_ingreso_almacen desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}
echo "<tr>";
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";

echo "<input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo "<img id='imagenFecha' src='imagenes/fecha.bmp'>";
/*echo "<DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo "daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo "navbar_style='background-color: 7992B7; color:ffffff;' ";
echo "input_element_id='fecha'";
echo "click_element_id='imagenFecha'></DLCALENDAR>";*/
echo "</td>";

$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where tipo_almacen='$global_tipoalmacen' order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_ingreso' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
    if($cod_tipoingreso==$tipo_ingreso)
    {   echo "<option value='$cod_tipoingreso' selected>$nombre_tipoingreso</option>";
    }
    else
    {   echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
    }
}
echo "</select></td>";
echo "<td align='center'><input type='text' class='texto' name='nota_entrega' value='$nota_entrega'></td>";
echo "<td align='center'><input type='text' class='texto' name='nro_factura' value=''></td></tr>";
echo "<tr><th colspan='5'>Observaciones</th></tr>";
echo "<tr><td colspan='5' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='100%' align='center'>";
echo "<tr><th colspan='5'>Cantidad de Materiales a ingresar: <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=50;$i++)
{   if($i==$cantidad_material)
    {   echo "<option value='$i' selected>$i</option>";
    }
    else
    {   echo "<option value='$i'>$i</option>";
    }
}
echo "</select><th></tr>";
echo "<tr><th width='5%'>&nbsp;</th><th width='35%'>Material</th><th width='20%'>Nro. Lote</th><th width='20%'>Fecha Vencimiento</th><th width='20%'>Cantidad Unitaria</th><th>Precion</th><th>Neto</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{   echo "<tr><td align='center'>$indice_detalle</td>";
    $sql_materiales="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
    $resp_materiales=mysql_query($sql_materiales);
    //obtenemos los valores de las variables creadas en tiempo de ejecucion
    $var_material="materiales$indice_detalle";
    $valor_material=$$var_material;
    echo "<td align='center'><select name='materiales$indice_detalle' class='textomini'>";
    echo "<option></option>";
    while($dat_materiales=mysql_fetch_array($resp_materiales))
    {   $cod_material=$dat_materiales[0];
        $nombre_material=$dat_materiales[1];
        $presentacion_material=$dat_materiales[2];
        if($cod_material==$valor_material)
        {   echo "<option value='$cod_material' selected>$nombre_material $presentacion_material</option>";
        }
        else
        {   echo "<option value='$cod_material'>$nombre_material $presentacion_material</option>";
        }
    }
    echo "</select></td>";

    $var_nrolote="nrolote$indice_detalle";
    $valor_nrolote=$$var_nrolote;
    echo "<td align='center'><input type='text' name='nrolote$indice_detalle' value='$valor_nrolote' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";

    $var_fecha_vencimiento="fecha_vencimiento$indice_detalle";
    $valor_fecha_vencimiento=$$var_fecha_vencimiento;
    echo "<td align='center'>";
    echo" <INPUT type='text' class='texto' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle'>";
    echo" <IMG id='imagenFecha$indice_detalle' src='imagenes/fecha.bmp'>";
    echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    echo" input_element_id='fecha_vencimiento$indice_detalle'";
    echo" click_element_id='imagenFecha$indice_detalle'></DLCALENDAR></td>";
    $var_cant_unit="cantidad_unitaria$indice_detalle";
    $valor_cant_unit=$$var_cant_unit;
    echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
    //
    echo "<td><input type='text' id='precio$indice_detalle' name='precio$indice_detalle' value='' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57  ) event.returnValue = false;' onkeyup='javascript:fun13(\"precio$indice_detalle\",\"neto$indice_detalle\");'></td>";
    echo "<td><input type='text' id='neto$indice_detalle' name='neto$indice_detalle' value='' class='texto' readonly></td>";
    //
    echo "</tr>";
}
echo "</table><br>";
echo "<table align='center'><tr><td><a href='navegador_ingresomuestras.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>