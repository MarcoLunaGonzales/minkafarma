<?php
if (!$almacén_cert = file_get_contents("softoken.p12")) {
    echo "Error: No se puede leer el fichero del certificado\n";
    exit;
}

if (openssl_pkcs12_read($almacén_cert, $info_cert, "CobofarFact2022")) {
    echo "Información del certificado\n";    
    print_r($info_cert);
} else {
    echo "Error: No se puede leer el almacén de certificados.\n";
    exit;
}
?>