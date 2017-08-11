<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que permite seleccionar el módulo que se configurará
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */

require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
$objCrud = new Crud();
/*
if($objCrud->contarFilas('ciclo_evaluacion', "estatus = '1'") > 0) {
    $error = $alerta["no_acceso_a_la_configuracion"];
} else {*/
    if(isset($_POST["continuar"])) {

        if(Helper::validarCamposVacios(array($_POST["tipo_evaluacion"]))) {
            $notificacion = $alerta["campos_vacios"];
        } else if ($_POST["tipo_evaluacion"] != "basico"
                    &&empty($_POST["opcion"])) {
            $notificacion = $alerta["campos_vacios"];
        } else {
            if ($_POST["tipo_evaluacion"] == "basico") {
                header("location:" . URLBASE . "configurar/software");
            } else if($_POST["tipo_evaluacion"] == "seguridad") {
                  if($_POST["opcion"] == "fuerza_bruta") {
                    header("location:" . URLBASE . "configurar/fuerza-bruta");
                } else if ($_POST["opcion"] == "sql"){
                    header("location:" . URLBASE . "configurar/inyeccion-sql");
                } else {
                    header("location:" . URLBASE . "configurar/inyeccion-xss");
                }
            } else {
                if($_POST["opcion"] == "carga") {
                    header("location:" . URLBASE . "configurar/prueba-de-carga");
                } else if ($_POST["opcion"] == "sesion"){
                    header("location:" . URLBASE . "configurar/prueba-de-volumen");
                } else {
                    header("location:" . URLBASE . "configurar/prueba-de-estres");
                }
            }
        }
    }
//}
require "application/views/configuracion/evaluacion/seleccionarConfiguracionDeSistemaEnEvaluacionView.phtml";
