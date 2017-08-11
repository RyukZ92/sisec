<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para capturar los datos del historial de usuario por
 * range de fecha
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

$pagina = $_GET['pagina'];

$_historial = $objPaginador->paginar("SELECT h.id, nombre, accion, fecha, hora, ip_cliente, navegador, so, isp  "
                                    . "FROM historial h JOIN usuario u ON u.id = h.id_usuario "
                                    . " WHERE fecha BETWEEN '" . $_SESSION["desde"] . "' AND '" . $_SESSION["hasta"] . "'"                                  
                                    . " ORDER BY fecha ASC, hora ASC", $pagina);
$params = $objPaginador->getPaginacion();

require "application/views/historial/consultarHistorialDeUsuarioView.phtml";
require "application/views/historial/consultarHistorialDetalleView.phtml";