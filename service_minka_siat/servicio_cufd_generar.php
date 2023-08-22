<?php
    /**
     * GENERACIÓN DE NUEVO CUFD
     */
    require("../conexion.inc");
    require("../funciones.php");
    ob_clean();
    date_default_timezone_set('America/La_Paz');

    // Codigo de Empresa 
    $cod_empresa = $_COOKIE['global_cod_empresa'];
    // Codigo de Empresa NIT
    $empresa_nit = $_COOKIE['global_empresa_nit'];
    // Codigo de Sucursal 
    $cod_sucursal = $_COOKIE['global_agencia'];
    $url_siat     = valorConfig(5);
    try{
        // Datos a enviar en formato JSON
        $data = array(
            "sIdentificador" => "MinkaSw123*",
            "sKey"           => "rrf656nb2396k6g6x44434h56jzx5g6",
            "accion"         => "generarCufdMinka",
            "idEmpresa"      => $cod_empresa,
            "nitEmpresa"     => $empresa_nit,
            "codSucursal"    => $cod_sucursal
        );
        // URL del servicio web
        $url = $url_siat."wsminka/ws_operaciones.php";
        $data_json = json_encode($data);
        // Configuración de la solicitud CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        ));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo json_encode(array(
                'status'  => false,
                'message' => 'Error en la solicitud CURL: ' . curl_error($ch)
            ));
            exit;
        }
        curl_close($ch);
        $data_response = json_decode($response, true);

        echo json_encode(array(
            'status' => !empty($data_response['estado']) ? ($data_response['estado']==1 ? true : false) : false,
            'message'=> !empty($data_response['estado']) ? $data_response['mensaje'] : 'Ocurrió un error inesperado, contacte a su administrador.'
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'status'  => false,
            'message' => 'Error: '.$e->getMessage()
        ));
    }
    exit;