<?php
require("conexionmysqli2.inc");
$cadComboInstitucion = "";
$consulta="SELECT c.codigo, c.nombre FROM instituciones c WHERE estado = 1 ORDER BY c.codigo ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codInstitucion = $reg["codigo"];
    $nomInstitucion = $reg["nombre"];
    $cadComboInstitucion=$cadComboInstitucion."<option value='$codInstitucion'>$nomInstitucion</option>";
   }
   echo $cadComboInstitucion;