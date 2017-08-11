<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para cargar los archivos de inyección sql (se hace dinamicamente)
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Feb, 2016
 * @Version: 0.1 Beta
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
        unset($_SESSION["nombre_archivo"]); //Limpiar el nombre del archivo
        if (isset($_POST["continuar"])) {   
            $sizeFile["diccionario_de_usuarios"] = $_FILES["diccionario_de_usuarios"]["size"] / 1024;
            $typeFile["diccionario_de_usuarios"] = $_FILES["diccionario_de_usuarios"]["type"];
            $nameFile["diccionario_de_usuarios"] = $_FILES["diccionario_de_usuarios"]["name"];
            $sourceFile["diccionario_de_usuarios"] = $_FILES["diccionario_de_usuarios"]["tmp_name"];

            if (strlen($nameFile["diccionario_de_usuarios"]) > 0
                && substr($nameFile["diccionario_de_usuarios"], -3) != "txt"               
                && substr($nameFile["diccionario_de_usuarios"], -3) != "log"                
                ) {
                    $notificacion = $alerta['formato_archivo_invalido'];
                    $error = 1;
            } else if ($sizeFile["diccionario_de_usuarios"] > 512) {
                    $notificacion = $alerta['archivo_pesado'];
                    $error = 1;
            } else {
                $destFile = RUTARAIZ . "files/login-load/diccionario-de-usuarios-personalizado.txt";

                if ($sizeFile["diccionario_de_usuarios"] > 0) {                
                    $saveFile = move_uploaded_file($sourceFile["diccionario_de_usuarios"], $destFile);
                }
                if ($saveFile){
                    $_SESSION["nombre_archivo"]["diccionario_de_usuarios"] = $nameFile["diccionario_de_usuarios"];
                } else {
                    if (strlen($nameFile["diccionario_de_usuarios"]) > 0)
                        $error = 1;
                }
            }

            if(!$error) {
                header("location: confirmacion-de-prueba-de-sesion");
            }
        }     
    } else {
        $error = $alerta["no_acceso_de_usuario_prueba"];
    }
} else {
    $error = $alerta["no_hay_ciclo_abierto"];
}
unset($_SESSION["resultados_de_prueba"]); //Limpiar registro de algún otro resultado de prueba
require "application/views/helper/tips.phtml";
require "application/views/rendimiento/pruebaDeSesion/prepararPruebaDeSesionView.phtml";