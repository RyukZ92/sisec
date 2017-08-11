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
$_historial = $objPaginador->paginar( "SELECT h.id, nombre, accion, fecha, hora, ip_cliente, navegador, so, isp "
                                    . " FROM historial h JOIN usuario u ON u.id = h.id_usuario "
                                    . " ORDER BY fecha DESC, hora DESC", $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/historial/consultarHistorialDeUsuarioView.phtml";
require "application/views/historial/consultarHistorialDetalleView.phtml";
