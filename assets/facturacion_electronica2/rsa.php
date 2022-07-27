<?php

class RSA
{
    /**
     * Firma RSA
           * @param $ datos de datos a firmar
           * @param $ private_key cadena de clave privada
           * devolver el resultado de la firma
     */
    function rsaSign($data, $private_key,$sign_type='OPENSSL_ALGO_SHA256') {

            $search = [
                    "-----BEGIN PRIVATE KEY-----",
                    "-----END PRIVATE KEY-----",
                    "\n",
                    "\r",
                    "\r\n"
            ];

            $private_key=str_replace($search,"",$private_key);
            $private_key=$search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];

            
            $res=openssl_get_privatekey($private_key);

            if($res)
            {
                    openssl_sign($data, $sign,$res,$sign_type);
                    openssl_free_key($res);
            }else {
                    exit("Formato de clave privada incorrecto");
            }
            $sign = base64_encode($sign);
            return $sign;
    }

    /**
           * Verificación RSA
           * @param $ datos de datos a firmar
           * @param $ public_key cadena de clave pública
           * @param $ firma el resultado de la firma para ser corregido
           * resultado de verificación de devolución
     */
    function rsaCheck($data, $public_key, $sign,$sign_type='OPENSSL_ALGO_SHA256')  {
            $search = [
                    "-----BEGIN CERTIFICATE-----",
                    "-----END CERTIFICATE-----",
                    "\n",
                    "\r",
                    "\r\n"
            ];
            $public_key=str_replace($search,"",$public_key);
            
            $public_key=$search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
            $res=openssl_get_publickey($public_key);

            if($res)
            {
                    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
                    openssl_free_key($res);
            }else{
                    exit("¡Formato de clave pública incorrecto!");
            }
            return $result;
    }

}

