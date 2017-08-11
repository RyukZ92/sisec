<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para preparar los datos de la prueba de carga
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Feb, 2016
 * @Version: 1.1
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
        require "librarys/Helper/Helper.php";
        if(!file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
            $error = $alerta["hacer_configuracion"];
        } else {
            $configSQL = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
            $disabled = "disabled";        
            if (isset($_POST["continuar"])) {
                $_SESSION["tiempos_personalizados"] = $_POST["personalizar"];
                $_SESSION["cantidad"] = $_POST["cantidad"];
                $optimo = $_SESSION["optimo"] = $_POST["optimo"];
                $intermedio = $_SESSION["intermedio"] = $_POST["intermedio"];
                $minimo = $_SESSION["minimo"] = $_POST["minimo"];
                if (Helper::validarCamposVacios(array($_POST["cantidad"]))
                ) {
                    $notificacion = $alerta["campos_vacios"];
                } else if (!empty($_POST["personalizar"])){
                    $disabled = null;
                    if ($optimo >= $intermedio
                        || $optimo  >= $minimo
                        || $intermedio >= $minimo) { 
                        $notificacion = $alerta["intervalo_incorrecto"];   
                    } else {
                        unset($_SESSION["resultados_de_prueba"]);
                        header("location:confirmacion-de-prueba-de-carga");
                    }
                }else {
                    unset($_SESSION["resultados_de_prueba"]);
                    header("location:confirmacion-de-prueba-de-carga");
                }
            } else {
                unset($_POST["continuar"]);
            }
        }
    } else {
        $error = $alerta["no_acceso_de_usuario_prueba"];
    }
} else {
    $error = $alerta["no_hay_ciclo_abierto"];
}    
unset($_SESSION["resultados_de_prueba"]); //Limpiar registro de algún otro resultado de prueba
require "application/views/pruebaDeCarga/ayuda/ayuda.phtml";
require "application/views/pruebaDeCarga/prepararPruebaDeCargaView.phtml";