<?php
require("conexion.inc");

//mysql_query("insert into ciudades values (10, 'AV. COCHABAMBA',1);");
//mysql_query("insert into almacenes values (1010, 10, 'TIENDA AV. CBBA.',1025)");
//mysql_query("update almacenes set responsable_almacen=1029 where cod_almacen=1010");
mysql_query("insert into usuarios_sistema values(1029, 'tiendacbba')");
/*mysql_query("delete from salida_almacenes;");
mysql_query("delete from salida_detalle_almacenes;");
mysql_query("delete from salida_detalle_ingreso;");*/

echo "creado";
?>