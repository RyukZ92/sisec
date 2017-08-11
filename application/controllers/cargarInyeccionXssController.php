<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para cargar los archivos de inyección sql (se hace dinamicamente)
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abr, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 */
require_once 'application/models/crud/CrudModel.php';
$objCrud = new Crud();
$_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");

$tabla = "ciclo_evaluacion ce";
$condicion = "id = '" . $_ciclo[0]['id'] . "' "
        . "AND ce.id_usuario = '" . $_SESSION['id_usuario'] . "'";
$_pruebaUsuario = $objCrud->contarFilas($tabla, $condicion);
if(count($_ciclo) > 0) {
    if($_pruebaUsuario) {
        require "librarys/GestorDeArchivos/GestorDeArchivos.php";
        if(file_exists("config/config.tests/config.inyeccion-xss.ini")) {
            $configXSS = parse_ini_file("config/config.tests/config.inyeccion-xss.ini", true);
        }
        $objArchivo = new GestorDeArchivos();
        unset($_SESSION["nombre_archivo"]);
        if (isset($_POST["continuar"])) {
            $sizeFile["codigos_xss"] = $_FILES["codigos_xss"]["size"] / 1024;
            $typeFile["codigos_xss"] = $_FILES["codigos_xss"]["type"];
            $nameFile["codigos_xss"] = $_FILES["codigos_xss"]["name"];
            $sourceFile["codigos_xss"] = $_FILES["codigos_xss"]["tmp_name"];

            if (strlen($nameFile["codigos_xss"]) > 0
                && substr($nameFile["codigos_xss"], -3) != "txt"               
                && substr($nameFile["codigos_xss"], -3) != "log"                
                ) {
                    $notificacion = $alerta['formato_archivo_invalido'];
                    $error = 1;
            } else if ($sizeFile["codigos_xss"] > 512) {
                    $notificacion = $alerta['archivo_pesado'];
                    $error = 1;
            } else {
                $destFile = RUTARAIZ . "files/injections-xss/inyeccion-xss-personalizada.txt";

                if ($sizeFile["codigos_xss"] > 0) {                
                    $saveFile = move_uploaded_file($sourceFile["codigos_xss"], $destFile);
                }
                if ($saveFile){                
                    $_SESSION["nombre_archivo"]["codigos_xss"] = $nameFile["codigos_xss"];
                }                   
            }
            if(!$error) {
                unset($_SESSION["resultados_de_prueba"]);
                header("location:confirmacion-de-prueba-de-inyeccion-xss");
            }
        } 
    } else {
        $error = $alerta["no_acceso_de_usuario_prueba"];
    }
} else {
    $error = $alerta["no_hay_ciclo_abierto"];
}
require "application/views/helper/tips.phtml";
require "application/views/inyeccionXss/cargarInyeccionXssView.phtml";