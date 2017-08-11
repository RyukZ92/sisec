<?php
/* @Descripcion: Controlador para cargar los archivos de inyección sql (se hace dinamicamente)
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Feb, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * 
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
        if(file_exists("config/config.tests/config.inyeccion-sql.ini")) {
            $configSQL = parse_ini_file("config/config.tests/config.inyeccion-sql.ini", true);
        }
        require "librarys/GestorDeArchivos/GestorDeArchivos.php";
        $objArchivo = new GestorDeArchivos();
        unset($_SESSION["nombre_archivo"]);
        $validar = 0;
        if (isset($_POST["continuar"])) {
                $sizeFile["archivo_contrasenias"] = $_FILES["archivo_contrasenias"]["size"] / 1024;
                $typeFile["archivo_contrasenias"] = $_FILES["archivo_contrasenias"]["type"];
                $nameFile["archivo_contrasenias"] = $_FILES["archivo_contrasenias"]["name"];
                $sourceFile["archivo_contrasenias"] = $_FILES["archivo_contrasenias"]["tmp_name"];

                if (strlen($nameFile["archivo_contrasenias"]) > 0
                    && substr($nameFile["archivo_contrasenias"], -3) != "txt"               
                    && substr($nameFile["archivo_contrasenias"], -3) != "log"                
                    ) {
                        $notificacion = $alerta['formato_archivo_invalido'];
                        $validar = 1;

                } else if ($sizeFile["archivo_contrasenias"] > 512) {
                        $notificacion = $alerta['archivo_pesado'];
                        $validar = 1;
                } else {
                    $destFile = RUTARAIZ . "files/brute-force/fuerza-bruta-personalizada.txt";

                    if ($sizeFile["archivo_contrasenias"] > 0) {                
                        $saveFile = move_uploaded_file($sourceFile["archivo_contrasenias"], $destFile);
                    }
                    if ($saveFile){                
                        $_SESSION["nombre_archivo"]["archivo_contrasenias"] = $nameFile["archivo_contrasenias"];
                    }                   
                }
            unset($_SESSION["resultados_de_prueba"]);
            if(!$validar) {
                header("location:confirmacion-de-prueba-de-fuerza-bruta");
            }
        }
    } else {
        $error = $alerta["no_acceso_de_usuario_prueba"];
    }
} else {
    $error = $alerta["no_hay_ciclo_abierto"];
}
require "application/views/helper/tips.phtml";
require "application/views/fuerzaBruta/cargarFuerzaBrutaView.phtml";