<?php

echo "testcurl";

print_r($_POST);

print_r($_FILES);

$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['file']['name'];
if($_FILES['file']["error"] > 0){
	echo "Error: " . $_FILES['file']['error'] . "<br>";
	$archivoName="";
}
else{
	move_uploaded_file($_FILES['file']['tmp_name'], "files/".$archivoName);		
}

?>