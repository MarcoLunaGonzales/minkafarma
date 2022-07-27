<?php
declare(strict_types = 1);

/**
 * @see https://www.jc-mouse.net/
 * @author mouse
 */
class ValidacionXSD {

    private $errors;
    private $doc;

    function __construct() {
        //Representa un documento HTML o XML en su totalidad;
        $this->doc = new \DOMDocument('1.0', 'utf-8');
    }

    /**
     * @param String $filexml Archivo XML a validar
     * @param String $xsd Esquema de validacion
     * 
     * @return bool TRUE El arcivo XML es valido
     *              FALSE El archiv XML no es valido
     */
    public function validar(string $filexml, string $xsd): bool {
        if (!file_exists($filexml) || !file_exists($xsd)) {
            echo "Archivo <b>$filexml</b> o <b>$xsd</b> no existe.";
            return false;
        }

        //Habilita/Deshabilita errores libxml y permite al usuario extraer 
        //información de errores según sea necesario
        libxml_use_internal_errors(true);
        //lee archivo XML
        $myfile = fopen($filexml, "r");
        $contents = fread($myfile, filesize($filexml));
        $this->doc->loadXML($contents, LIBXML_NOBLANKS);
        fclose($myfile);
        // Valida un documento basado en un esquema
        if (!$this->doc->schemaValidate($xsd)) {
            //Recupera un array de errores
            $this->errors = libxml_get_errors();
            return false;
        }
        return true;
    }

    /**
     * Retorna un string con los errores de validacion si es que existieran
     */
    public function mostrarError(): string {
        $msg = '';
        if ($this->errors == NULL) {
            return '';
        }
        foreach ($this->errors as $error) {
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $nivel = 'Warning';
                    break;
                case LIBXML_ERR_ERROR :
                    $nivel = 'Error';
                    break;
                case LIBXML_ERR_FATAL:
                    $nivel = 'Fatal Error';
                    break;
            }
            $msg .= "<b>Error $error->code [$nivel]:</b><br>"
                    . str_repeat('&nbsp;', 6) . "Linea: $error->line<br>"
                    . str_repeat('&nbsp;', 6) . "Mensaje: $error->message<br>";
        }
        //Limpia el buffer de errores de libxml
        libxml_clear_errors();
        return $msg;
    }

    function getErrors() {
        return $this->errors;
    }

    function setErrors($errors) {
        $this->errors = $errors;
    }

}