<?php
$estilosVenta=1;
require_once 'conexionmysqlipdf.inc';

$global_ciudad=$_COOKIE['global_agencia'];
$fecha_actual = date("d-m-Y");

$sql="SELECT codigo_funcionario,CONCAT(paterno,' ',materno,' ',nombres)personal FROM funcionarios where codigo_funcionario in (select DISTINCT cod_chofer from salida_almacenes where cod_almacen in (SELECT cod_almacen from almacenes where cod_ciudad='$global_ciudad') and cod_chofer!=-1) or cod_ciudad='$global_ciudad' order by paterno,materno,nombres";

//echo $sql;
  $resp=mysqli_query($enlaceCon,$sql);
  while($dat=mysqli_fetch_array($resp))
  { $codigo_funcionario=$dat[0];
    $nombre_funcionario=$dat[1];
    echo "<option value='$codigo_funcionario'>$nombre_funcionario</option>";  
  }