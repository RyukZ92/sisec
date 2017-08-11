<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para gestionar la configuración de la prueba de carga
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
if(file_exists("config/config.tests/config.prueba-de-carga.ini")) {
    $configPruebaDeCarga = parse_ini_file("config/config.tests/config.prueba-de-carga.ini", true);
}
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if($_POST["guardar"]) {

    if(Helper::validarCamposVacios(array($_POST["url"], $_POST["campo"], 
                                         $_POST["tipo"], $_POST["longitud"]))
    ) {
        $notificacion = $alerta["campos_vacios"];
    } else {
        $contenido .= "## Archivo de Configuración para Carga de Datos" . PHP_EOL;
        $contenido .= "## Última actualización automática: " . date("d/m/Y") . " " 
                   . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "## Usuario: " . strtoupper($_SESSION["usuario"]) ." (". $_SESSION["nivel_usuario"] .")". PHP_EOL.PHP_EOL;
        $contenido .= "url = \"" . $_POST["url"] . "\"" . PHP_EOL;
        $contenido .= "archivo = " . RUTARAIZ . "files/injections-xss/inyeccion-xss-personalizada.txt" . PHP_EOL;
        $contenido .= "cadena_valida = \"" . $_POST["cadena_valida"] . "\"" . PHP_EOL;
        $contenido .= "total_campos = \"" . count($_POST["total_campos"]) . "\"" . PHP_EOL.PHP_EOL;
        for ($i=0; $i<count($_POST["total_campos"]); $i++) {
            $contenido .= "[campos_$i]" . PHP_EOL;
            $contenido .= "nombre = \"" . $_POST["campo"][$i] . "\"" . PHP_EOL;
            $contenido .= "tipo = \"" . $_POST["tipo"][$i] . "\"" . PHP_EOL;
            $contenido .= "longitud = \"" . $_POST["longitud"][$i] . "\"" . PHP_EOL . PHP_EOL;
        }
            
        $contenido .= "[campo_adicional]" . PHP_EOL;
        for($i=0; $i<count($_POST["nombre_campo_adicional"]); $i++) {            
            $contenido_array_adicional .= $_POST["nombre_campo_adicional"][$i] . " = \"" 
                                       . $_POST["valor_campo_adicional"][$i] . "\"" . PHP_EOL;
        }
        $contenido .= substr($contenido_array_adicional, 0, -1);
             
       $archivo = $objArchivo->escrbir("config/config.tests/config.prueba-de-carga.ini", $contenido);
        if($archivo) {
          $notificacion = $alerta["actualizacion_exitosa"];
          unset($_POST);
           header("refresh:6;". URLBASE . $vista);
        } else {
          $notificacion = $alerta["actualizacion__error"];
        }
    }
}

require "application/views/configuracion/evaluacion/configurarPruebaDeCargaView.phtml";