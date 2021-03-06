<?php
/**
 * =============================================================================
 * @Descripcion: Vista para crear un nuevo ciclo de evaluación
 * @Fecha: Abril, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */
require "librarys/phpmailer/enviar_email.php";
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
$sistemaEvaluado = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
$nombreDelProducto = $sistemaEvaluado["nombre"];
$versionDelProducto = $sistemaEvaluado["version"];
$urlDelProducto = $sistemaEvaluado["url"];
$objCrud = new Crud();

$_ciclo = $objCrud->contarFilas("ciclo_evaluacion", "estatus = '1'");

if($_ciclo) {
    $error = $alerta["ciclo_abierto"];
} else {
    if (isset($_POST['crear'])) {
        if (Helper::validarCamposVacios(array($_POST["descripcion"],
                                              $_POST["estatus"]))
        ) {
            $mensajes[] = "<li class='text-danger'>Rellene los campos requeridos</li>";
        } else {
            $tabla = "ciclo_evaluacion";
            $datos = array('fecha_creacion'  =>  date("Y-m-d"),
                           'hora_creacion'   =>  date("H:i:s"),
                           'descripcion'     => Helper::limpiarCampo($_POST["descripcion"]),
                           'id_usuario'      =>  $_SESSION["id_usuario"],
			   'nombre_producto' => $nombreDelProducto,
		           'version_producto'=> $versionDelProducto,
			   'url_producto'    => $urlDelProducto);
            $condicion = null;

            $result = $objCrud->guardar($tabla, $datos, $condicion, null);


            if ($result) {
                $ipCliente = $_SERVER["REMOTE_ADDR"];
                $info = Helper::getBrowser();
                $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
                $so = $info["os"];

                $notificacion = $alerta["registro_exitoso"];
                $datos = array("accion"         => "Creó Nuevo Ciclo de Evaluación",
                                "ip_cliente" => $ipCliente,
                                "navegador"  => $navegador,
                                "so"         => $so,                
                                "fecha"      => date("Y-m-d"),
                                "hora"       => date("H:i:s"),
                                "id_usuario"    => $_SESSION["id_usuario"]);
                $objCrud->guardar("historial", $datos, null, null);
                unset($_POST);
                header("refresh:2;" . URLBASE. "evaluacion/listado");
            } else {
                $notificacion = $alerta["registro_error"];
            }

        }
    }
}
require "application/views/evaluacion/nuevoCicloDeEvaluacionView.phtml";
