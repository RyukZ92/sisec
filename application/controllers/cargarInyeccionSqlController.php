<?php
/**
 * =============================================================================
 *  @Descripcion: Controlador para cargar los archivos de inyección sql (se hace dinamicamente)
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
        if(file_exists("config/config.tests/config.inyeccion-sql.ini")) {
            $configSQL = parse_ini_file("config/config.tests/config.inyeccion-sql.ini", true);
        }
        $objArchivo = new GestorDeArchivos();
        unset($_SESSION["nombre_archivo"]);
        if (isset($_POST["continuar"])) {  

                $sizeFile["archivo_sql"] = $_FILES["archivo_sql"]["size"] / 1024;
                $typeFile["archivo_sql"] = $_FILES["archivo_sql"]["type"];
                $nameFile["archivo_sql"] = $_FILES["archivo_sql"]["name"];
                $sourceFile["archivo_sql"] = $_FILES["archivo_sql"]["tmp_name"];

                if (strlen($nameFile["archivo_sql"]) > 0
                    && substr($nameFile["archivo_sql"], -3) != "txt"               
                    && substr($nameFile["archivo_sql"], -3) != "log"                
                    ) {
                        $notificacion = $alerta['formato_archivo_invalido'];
                        $error = 1;
                } else if ($sizeFile["archivo_sql"] > 512) {
                        $notificacion = $alerta['archivo_pesado'];
                        $error = 1;
                } else {
                    $destFile = RUTARAIZ . "files/injections-sql/inyeccion-sql-personalizada.txt";

                    if ($sizeFile["archivo_sql"] > 0) {                
                        $saveFile = move_uploaded_file($sourceFile["archivo_sql"], $destFile);
                    }
                    if ($saveFile){                
                        $_SESSION["nombre_archivo"]["archivo_sql"] = $nameFile["archivo_sql"];
                    }                   
                }

            if(!$error) {
                unset($_SESSION["resultados_de_prueba"]);
                header("location:confirmacion-de-prueba-de-inyeccion-sql");    
            }
        }    
    } else {
        $error = $alerta["no_acceso_de_usuario_prueba"];
    }
} else {
    $error = $alerta["no_hay_ciclo_abierto"];
}
require "application/views/helper/tips.phtml";
require "application/views/inyeccionSql/cargarInyeccionSqlView.phtml";