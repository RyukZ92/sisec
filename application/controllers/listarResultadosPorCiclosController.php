<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para obtener los datos de los ciclos de evaluaciones 
 * para descargar el informe de calidad 
 * @Author: Miguel Salazar
 * @Fecha: Abril, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require_once "application/models/crud/CrudModel.php";
require_once "librarys/Helper/Helper.php";
require_once "librarys/PaginadorQuery/PaginadorQuery.php";
$objCrud = new Crud();
$objPaginador = new PaginadorQuery();


$pagina = (int) $_GET['pagina'];

$_ciclo = $objPaginador->paginar( "SELECT * "
                                . "FROM ciclo_evaluacion "
				. "WHERE estatus = '0' "
                                . "ORDER BY id ", $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/resultado/listarResultadosPorCiclosView.phtml";
