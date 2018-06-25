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
{   var i,j,cantidad_material;
    variables=new Array(f.length-1);
    vector_material=new Array(100);
    vector_cantidades=new Array(100);
    vector_tipomaterial=new Array(100);
    vector_precio=new Array(100);

    var indice,fecha, observaciones, proveedor;
    fecha=f.fecha.value;
    proveedor=f.cod_proveedor.value;
    observaciones=f.observaciones.value;
    cantidad_material=f.cantidad_material.value;
    
	for(i=4;i<=f.length-2;i++)
    {   variables[i]=f.elements[i].value;
        if(f.elements[i].name.indexOf('fecha')==-1)
        {   if(f.elements[i].value=='')
            {   alert('Algun elemento no tiene valor');
                return(false);
            }
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
    //
    /*var buscado,cant_buscado;
    for(k=0;k<=indice;k++)
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
	
    location.href='guarda_ordenCompra.php?vector_material='+vector_material+'&vector_cantidades='+vector_cantidades+'&vector_precio='+vector_precio+'&fecha='+fecha+'&cod_proveedor='+proveedor+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'';
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
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Orden de Compra</th></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>Nro. OC</th><th>Fecha</th><th>Proveedor</th></tr>";
echo "<tr>";
$sql="select nro_orden from orden_compra order by cod_orden desc";
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

$sql1="select p.`cod_proveedor`, p.`nombre_proveedor` from `proveedores` p order by p.`nombre_proveedor`";

$resp1=mysql_query($sql1);
echo "<td align='center'><select name='cod_proveedor' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $codProveedor=$dat1[0];
    $nombreProveedor=$dat1[1];
	
    if($codProveedor==$cod_proveedor)
    {   echo "<option value='$codProveedor' selected>$nombreProveedor</option>";
    }
    else
    {   echo "<option value='$codProveedor'>$nombreProveedor</option>";
    }
}
echo "</select></td>";
echo "<tr><th colspan='3'>Observaciones</th></tr>";
echo "<tr><td colspan='3' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='100%' align='center' cellspacing='0'>";
echo "<tr><th colspan='5'>Cantidad de Materiales a registrar: <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=15;$i++)
{   if($i==$cantidad_material)
    {   echo "<option value='$i' selected>$i</option>";
    }
    else
    {   echo "<option value='$i'>$i</option>";
    }
}
echo "</select><th></tr>";
echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad Unitaria</th><th>Precio</th><th>Monto</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{   echo "<tr><td align='center'>$indice_detalle</td>";
    //obtenemos los valores de las variables creadas en tiempo de ejecucion
    $var_material="materiales$indice_detalle";
    $valor_material=$$var_material;
    echo "<td align='center'><select name='materiales$indice_detalle' class='textomini'>";
    echo "<option></option>";
    $sql_materiales="select * from material_apoyo where estado='Activo' and codigo_material<>0 order by descripcion_material";
    $resp_materiales=mysql_query($sql_materiales);
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
    echo "</select></td>";

    $var_cant_unit="cantidad_unitaria$indice_detalle";
    $valor_cant_unit=$$var_cant_unit;
    echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
    //
    echo "<td align='center'><input type='text' id='precio$indice_detalle' name='precio$indice_detalle' value='' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57  ) event.returnValue = false;' onkeyup='javascript:fun13(\"precio$indice_detalle\",\"neto$indice_detalle\");'></td>";
    echo "<td>&nbsp;</td>";
    //
    echo "</tr>";
}
echo "</table><br>";
echo "<table align='center'><tr><td><a href='navegador_ingresomateriales.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>