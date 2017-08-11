<?php
/**
 * =============================================================================
 * @Descripcion: Controlador obtener los datos del historial de usuario
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/PaginadorQuery/PaginadorQuery.php";
$objCrud = new Crud();
$objPaginador = new PaginadorQuery();

$pagina = (int) $_GET['pagina'];
unset($_SESSION["desde"]);
unset($_SESSION["hasta"]);
$_resultado = $objPaginador->paginar( "SELECT p.id, ruta_de_archivo, tipo, fecha, hora, nombre "
                                    . " FROM prueba p JOIN usuario u ON id_usuario = u.id "
                                    . " ORDER BY p.id DESC", $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/resultado/listarPruebasDeSeguridadView.phtml";

