<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para procesar y configurar los datos de la prueba
 * de fuerza bruta
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
if (file_exists("config/config.tests/config.fuerza-bruta.ini")) {
    $configFuerzaBruta = parse_ini_file("config/config.tests/config.fuerza-bruta.ini", true);
}
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if($_POST["guardar"]) {
   if(Helper::validarCamposVacios(array($_POST["url"], $_POST["campo_contrasenia"], 
                                         $_POST["cadena_invalida"], $_POST["nombre_campo"], $_POST["valor_campo"]))
        ) {
        $notificacion = $alerta["campos_vacios"];
    } else {
        $contenido .= "## Archivo de Configuración de Ataque de Fuerza Bruta" . PHP_EOL;
                $contenido .= "## Última actualización automática: " . date("d/m/Y") . " " 
                   . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "## Usuario: " . strtoupper($_SESSION["usuario"]) ." (". $_SESSION["nivel_usuario"] .")". PHP_EOL.PHP_EOL;
        $contenido .= "url = \"" . $_POST["url"] . "\"" . PHP_EOL;
        $contenido .= "archivo = " . RUTARAIZ . "files/brute-force/fuerza-bruta-personalizada.txt" . PHP_EOL;
        $contenido .= "cadena_invalida = \"" . $_POST["cadena_invalida"] . "\"" . PHP_EOL;
        $contenido .= "campo_contrasenia = \"" . $_POST["campo_contrasenia"] . "\"" . PHP_EOL.PHP_EOL;
        
		$contenido .= "[campo]" . PHP_EOL;         
        for($i=0; $i<count($_POST["nombre_campo"]); $i++) {            
            $contenido_array .= $_POST["nombre_campo"][$i] ." = \"". $_POST["valor_campo"][$i] ."\"  ". PHP_EOL;            
        }
		$contenido .= substr($contenido_array, 0, -1) . PHP_EOL . PHP_EOL;        
        $contenido .= "[campo_adicional]" . PHP_EOL;
        for($i=0; $i<count($_POST["nombre_campo_adicional"]); $i++) {            
            $contenido_array_adicional .= $_POST["nombre_campo_adicional"][$i] . " = \"" . $_POST["valor_campo_adicional"][$i] . "\"" . PHP_EOL;
        }
        $contenido .= substr($contenido_array_adicional, 0, -1);
        
        $archivo = $objArchivo->escrbir("config/config.tests/config.fuerza-bruta.ini", $contenido);

        if($archivo) {
          $notificacion = $alerta["actualizacion_exitosa"];
          unset($_POST);
           header("refresh:3;". URLBASE . "configurar/fuerza-bruta");
        } else {
          $notificacion = $alerta["actualizacion_error"];
        }
    }
}
require "application/views/helper/tips.phtml";
require "application/views/configuracion/evaluacion/configurarFuerzaBrutaView.phtml";