<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
$personal=$_GET["personal"];
$sql="SELECT CONCAT(fu.nombres,' ',fu.paterno,' ',fu.materno)personal,c.descripcion,fu.codigo_funcionario,c.cod_ciudad,u.codigo_funcionario,fu.ci,fu.cod_ciudad as origen_ciudad 
FROM funcionarios_agencias f 
JOIN funcionarios fu on fu.codigo_funcionario=f.codigo_funcionario
JOIN usuarios_sistema u on u.codigo_funcionario=fu.codigo_funcionario
JOIN ciudades c on c.cod_ciudad=f.cod_ciudad where f.codigo_funcionario='$personal'";

echo $sql;

$resp=mysqli_query($enlaceCon,$sql);

echo "";
echo "<tr class='bg-info text-white'><th colspan='6'>Sucursales Habilitadas</th></tr>";
echo "<tr class='bg-info text-white'><th>Codigo</th><th>Personal</th><th>Usuario</th><th>CI</th><th>Sucursal</th><th></th></tr>";
echo "";
$i=0;
while($dat=mysqli_fetch_array($resp))
{ $personal=$dat[0];
  $descripcion="<b style='font-size:20px'>".$dat[1]."</b>";
  $user=$dat[2];
  $ciudad=$dat[3];
  $usuario=$dat[4];
  $ci=$dat[5];
  $btnFavo="btn-default";
  if($dat['origen_ciudad']==$ciudad){
    $btnFavo="btn-info";
  }
  $boton='<div class="btn-group"><a class="btn btn-sm '.$btnFavo.' btn-fab" href="predeterminarAsignacion.php?p='.$user.'&c='.$ciudad.'"><i class="material-icons">turned_in</i></a><a class="btn btn-sm btn-danger btn-fab" href="deleteAsignacion.php?p='.$user.'&c='.$ciudad.'"><i class="material-icons">remove</i></a></div>';
  if($i%2==0){
      echo "<tr style='background:#D3D6D6'><td>$user</td><td>$personal</td><td>$usuario</td><td>$ci</td><td>$descripcion</td><td width='2%'>$boton</td></tr>";
  }else{
  	  echo "<tr style='background:#fff'><td>$user</td><td>$personal</td><td>$usuario</td><td>$ci</td><td>$descripcion</td><td width='2%'>$boton</td></tr>";
  } 
  $i++;
}