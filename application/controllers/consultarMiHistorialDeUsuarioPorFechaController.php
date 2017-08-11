<?php
/**
 * =============================================================================
 * @Descripcion: Controlador capturar los datos del usuarios de usuario que tiene
 * una sesión de iniciada. Los datos se obtienen por rango de fecha en en controlador
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/PaginadorQuery/PaginadorQuery.php";
$objCrud = new Crud();
$objPaginador = new PaginadorQuery();

if(!empty($_POST["fecha_desde"]) 
        && !empty($_POST["fecha_desde"])) {
    $_SESSION["desde"] = Helper::convertirFechaAaaaMmDd($_POST["fecha_desde"]);
    $_SESSION["hasta"] = Helper::convertirFechaAaaaMmDd($_POST["fecha_hasta"]);
}

$pagina = (int) $_GET['pagina'];

$query = "SELECT nombre, accion, navegador, so, ip_cliente, hora, fecha FROM historial h JOIN usuario u ON u.id = id_usuario "
        . "WHERE fecha BETWEEN '" . $_SESSION["desde"] . "' AND '" . $_SESSION["hasta"] . "' "
        . "AND id_usuario = '" . $_SESSION["id_usuario"] . "' "                                    
        . "ORDER BY fecha ASC, hora ASC";

$_historial = $objPaginador->paginar($query, $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/historial/consultarMiHistorialDeUsuarioView.phtml";