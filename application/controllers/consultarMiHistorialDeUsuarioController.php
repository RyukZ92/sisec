<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que permite obtener los datos del historial del
 * usuario que ha iniciado sesión
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

$pagina =  (int) $_GET['pagina'];
unset($_SESSION["desde"]);
unset($_SESSION["hasta"]);
$_historial = $objPaginador->paginar("SELECT * "
                                    . "FROM historial h JOIN usuario u ON u.id = id_usuario "
                                    . "WHERE id_usuario = '" . $_SESSION["id_usuario"] . "' "
                                    . "ORDER BY fecha DESC, hora DESC", $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/historial/consultarMiHistorialDeUsuarioView.phtml";