<?php
header('Content-Type: application/json');

	require("../conexion.inc");
	
	function utf8json($inArray) { 
	static $depth = 0; 
		/* our return object */ 
		$newArray = array(); 
		/* safety recursion limit */ 
		$depth ++; 
		if($depth >= '1000000') { 
			return false; 
		} 
		/* step through inArray */ 
		foreach($inArray as $key=>$val) { 
			if(is_array($val)) { 
				/* recurse on array elements */ 
				$newArray[$key] = utf8json($val); 
			} else { 
				/* encode string values */ 
				$newArray[$key] = utf8_encode($val); 
			} 
		} 
		/* return utf8 encoded array */ 
		return $newArray; 
	}
	

	$emparray[] = array();
		
	$sql="select l.cod_accionterapeutica as value, l.nombre_accionterapeutica as texto from acciones_terapeuticas l;";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
		$id=$dat[0];
		$nombre=$dat[1];
		
		$emparray[]=array("text"=>$nombre, "value"=>$id);
	}
	array_splice($emparray, 0,1);
	//echo json_encode(utf8json($emparray));
	echo json_encode($emparray);
?>