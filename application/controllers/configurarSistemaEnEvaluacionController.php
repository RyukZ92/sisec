<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para gestionar la configuración de la evaluación
 * (datos básicos)
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */

require "application/models/crud/CrudModel.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
require "librarys/Helper/Helper.php";
if(file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
    $configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
}
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if($_POST["guardar"]) {

    if(Helper::validarCamposVacios(array($_POST["url"], 
                                         $_POST["nombre"], 
                                         $_POST["version"], 
                                         $_POST["url_sesion"],
                                         $_POST["mensaje_de_error"],
                                         $_POST["nombre_campo_adicional"], 
                                         $_POST["valor_campo_adicional"]))
    ) {
        $notificacion = $alerta["campos_vacios"];
    } else {
        $contenido .= "## Archivo de Configuración Básica del Sistema en Evaluación" . PHP_EOL;
        $contenido .= "## Última actualización automática: " . date("d/m/Y") . " " 
                   . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "## Usuario: " . strtoupper($_SESSION["usuario"]) ." (". $_SESSION["nivel_usuario"] .")". PHP_EOL.PHP_EOL;
        $contenido .= "url = \"" . $_POST["url"] . "\"" . PHP_EOL;
        $contenido .= "nombre = \"" . $_POST["nombre"] . "\"" . PHP_EOL;
        $contenido .= "version = \"" . $_POST["version"] . "\"" . PHP_EOL.PHP_EOL;
        $contenido .= "## Configuración de Inicio de Sesión" . PHP_EOL.PHP_EOL;
        $contenido .= "url_sesion = \"" . $_POST["url_sesion"] . "\"" . PHP_EOL;
        $contenido .= "mensaje_de_error = \"" . $_POST["mensaje_de_error"] . "\"" . PHP_EOL;
        $contenido .= "[login]" . PHP_EOL;
        for($i=0; $i<count($_POST["nombre_campo_adicional"]); $i++) {            
            $contenido_array_sesion .= $_POST["nombre_campo_adicional"][$i] . " = \"" 
                                    . $_POST["valor_campo_adicional"][$i] . "\"" . PHP_EOL;
        }
        $contenido .= substr($contenido_array_sesion, 0, -1);
        $contenido .=  PHP_EOL . PHP_EOL;
        
       $archivo = $objArchivo->escrbir("config/config.basica-del-sistema-en-evaluacion.ini", $contenido);
        if($archivo) {
          $notificacion = $alerta["actualizacion_exitosa"];
          unset($_POST);
           header("refresh:3;". URLBASE . 'configurar/software');
        } else {
          $notificacion = $alerta["actualizacion__error"];
        }
    }
}
require "application/views/configuracion/evaluacion/configurarSistemaEnEvaluacionView.phtml";
