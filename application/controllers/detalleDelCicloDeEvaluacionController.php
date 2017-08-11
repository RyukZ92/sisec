<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para capturar el id del ciclo de evaluación y buscar
 * sus datos para mostrarlo en la vista
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require_once "application/models/crud/CrudModel.php";
require_once "librarys/Helper/Helper.php";
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objCrud = new Crud();
$objArchivo = new GestorDeArchivos();
$tabla = "ciclo_evaluacion";
$subConsulta = "(SELECT count(*) "
        . "FROM prueba JOIN ciclo_evaluacion ce ON id_ciclo_evaluacion = ce.id "
        . "WHERE ce.id = '".$_GET["id"]."') as pruebas";
$datos = array("*", "$subConsulta");
$condicion = "id = '" . $_GET["id"] . "'";
$orden = null;
$_ciclo = $objCrud->consultar($tabla, $datos, $condicion, $orden);
if (count($_ciclo) == 0) {
    $error = $alerta["sin_registro"];
}

$_fechaCierre = explode ('-', $_ciclo[0]["fecha_cierre"]);
if($_fechaCierre[0] == 0) {
    $horaDeCierre = "No Aplicable";
    $fechaDeCierre = "No Aplicable";
} else {
    $fechaDeCierre = Helper::convertirFechaDdMmAaaa($_ciclo[0]["fecha_cierre"]);
   $horaDeCierre = Helper::convertirHoraA12H($_ciclo[0]["hora_cierre"]);
}

require "application/views/evaluacion/detalleDelCicloDeEvaluacionView.phtml";
