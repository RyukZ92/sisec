<?php
/**
 * =============================================================================
 * @Descripcion: Controlador obtener los datos del historial de usuario
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
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

if(!$_GET["id"]) {
        if(isset($_POST["confirmar"])) {
            $_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");
            $tabla = "ciclo_evaluacion ce";
            $condicion = "id = '" . $_ciclo[0]['id'] . "' "
                    . "AND ce.id_usuario = '" . $_SESSION['id_usuario'] . "'";
            $_pruebaUsuario = $objCrud->contarFilas($tabla, $condicion);
            if ($_pruebaUsuario) {
                
                
                $datos =  array("estatus"       => '0',
                                "fecha_cierre"  =>  date("Y-m-d"),
                                "hora_cierre"   =>  date("H:i:s"));
                $guardar = $objCrud->guardar("ciclo_evaluacion", 
                                 $datos, "id = '" . $_POST["id"] . "'");
                if($guardar) {
                    $notificacion = $alerta["ciclo_cerrado_con_exito"];    
                } else {
                    $notificacion = $alerta["actualizacion_error"];  
                }
            }  else {
                $notificacion = $alerta["cierre_de_prueba_denegada"];
            }
    }
    $pagina = (int) $_GET['pagina'];
    unset($_SESSION["desde"]);
    unset($_SESSION["hasta"]);
    #$_cicloActivo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");
    
    $_ciclo = $objPaginador->paginar( "SELECT ce.id, descripcion, ce.fecha_creacion, ce.hora_creacion, ce.estatus, u.nombre"
                                        . " FROM ciclo_evaluacion ce JOIN usuario  u ON u.id = id_usuario"
                                        . " ORDER BY id ", $pagina);
    $params = $objPaginador->getPaginacion();
    for($i=0;$i<count($_ciclo);$i++) {
        $_prueba[$i] = $objCrud->consultar("prueba JOIN ciclo_evaluacion ce ON id_ciclo_evaluacion = ce.id",
                                        array("count(*) as pruebas"), 
                                        "ce.id='".$_ciclo[$i]["id"]."'");
    }

    require "application/views/evaluacion/listarCicloDeEvaluacionView.phtml";
} else {
    $tema["vista"] = "Detalle del Ciclo de Evaluación";
    require "application/controllers/detalleDelCicloDeEvaluacionController.php";
}