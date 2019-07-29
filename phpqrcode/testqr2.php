<?php 

    include('qrlib.php'); 
    // how to save PNG codes to server 
     
     
    $codeContents = 'TESTCODEQR2124234234'; 
     
	$fechahora=date("dmy.His");
	$archivoName=$fechahora;

    $fileName = $archivoName.".png"; 
     
    QRcode::png($codeContents, $fileName); 
     
    echo 'Server PNG File: '.$fileName; 
    echo '<hr />'; 
     
    // displaying 
    echo '<img src="'.$fileName.'" />'; 