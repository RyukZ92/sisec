<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para procesar los datos de la configuración de la 
 * inyección xss
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */

require "application/models/crud/CrudModel.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
require "librarys/Helper/Helper.php";
if(file_exists("config/config.tests/config.inyeccion-xss.ini")) {
    $configXSS = parse_ini_file("config/config.tests/config.inyeccion-xss.ini", true);
}
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if($_POST["guardar"]) {
/*
echo "<pre>";
print_r($configXSS);
echo "</pre>";
*/
    if(Helper::validarCamposVacios(array($_POST["url"], $_POST["nombre_campo"], 
                                         $_POST["cadena_invalida"]))) {
        $notificacion = $alerta["campos_vacios"];
    } else {
        $contenido .= "## Archivo de Configuración de Inyección XSS" . PHP_EOL;
        $contenido .= "## Última actualización automática: " . date("d/m/Y") . " " 
                   . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "## Usuario: " . strtoupper($_SESSION["usuario"]) ." (". $_SESSION["nivel_usuario"] .")". PHP_EOL.PHP_EOL;
        $contenido .= "url = \"" . $_POST["url"] . "\"" . PHP_EOL;
        $contenido .= "archivo = " . RUTARAIZ . "files/injections-xss/inyeccion-xss-personalizada.txt" . PHP_EOL;
        $contenido .= "cadena_invalida = \"" . $_POST["cadena_invalida"] . "\"" . PHP_EOL.PHP_EOL;
        
		$contenido .= "[campo]" . PHP_EOL;         
        for($i=0; $i<count($_POST["nombre_campo"]); $i++) {            
            $contenido_array .= $_POST["nombre_campo"][$i] ." = \"\" ". PHP_EOL;            
        }
		$contenido .= substr($contenido_array, 0, -1) . PHP_EOL . PHP_EOL;        
        $contenido .= "[campo_adicional]" . PHP_EOL;
        for($i=0; $i<count($_POST["nombre_campo_adicional"]); $i++) {            
            $contenido_array_adicional .= $_POST["nombre_campo_adicional"][$i] . " = \"" 
                                       . $_POST["valor_campo_adicional"][$i] . "\"" . PHP_EOL;
        }
        $contenido .= substr($contenido_array_adicional, 0, -1);
             
       $archivo = $objArchivo->escrbir("config/config.tests/config.inyeccion-xss.ini", $contenido);
        if($archivo) {
          $notificacion = $alerta["actualizacion_exitosa"];
          unset($_POST);
           header("refresh:6;". URLBASE . 'configurar/inyeccion-xss');
        } else {
          $notificacion = $alerta["actualizacion__error"];
        }
    }
}
require "application/views/helper/tips.phtml";
require "application/views/configuracion/evaluacion/configurarInyeccionXssView.phtml";