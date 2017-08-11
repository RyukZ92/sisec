<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para mostrar el listado de los usuarios del sistema
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abril, 2016
 * @Version: 1.1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */ 
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/PaginadorQuery/PaginadorQuery.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objCrud = new Crud();
$objPaginador = new PaginadorQuery();

if(isset($_POST["confirmar"])) {
    $datos = array("eliminado" => 1);
    $tabla = "usuario";
    $condicion = "id = '".$_POST["id"]."'";
    $result = $objCrud->guardar($tabla, $datos, $condicion);
    if($result) {
        $notificacion = $alerta["enviado_a_la_papalera"];
        $objArchivo = new GestorDeArchivos();
        $tabla = "usuario";
        $datos = array("nombre as usuario");
        $condicion = "id = '" . Helper::limpiarCampo($_POST["id"]) . "'";
        $orden = null;
        $usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);        
        
        $ipCliente = $_SERVER["REMOTE_ADDR"];
        $info = Helper::getBrowser();
        $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
        $so = $info["os"];
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
        $eventoLog .= "eliminó (envió a la papelera) al usuario " . strtoupper($usuario[0]["usuario"]) . 
                " ('" . Helper::limpiarCampo($_POST["id"]) . "')";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);

        $datos = array("accion"      => "Eliminó (envió a la papelera) al usuario: <b>" . strtoupper($usuario[0]["usuario"]) . ""
            . " (" . Helper::limpiarCampo($_POST["id"]) . ")</b>",
                        "fecha"      => date("Y-m-d"),
                        "hora"       => date("H:i:s"),  
                        "ip_cliente" => $ipCliente,
                        "navegador"  => $navegador,
                        "so"         => $so,
                        "id_usuario" => $_SESSION["id_usuario"]);
        $objCrud->guardar("historial", $datos);        
    }
}

$pagina = (int) $_GET['pagina'];
$_usuario = $objPaginador->paginar("SELECT * FROM usuario
                                    WHERE eliminado != '1'
                                    -- AND id != '" . $_SESSION["id_usuario"] . "'
                                    ORDER BY nombre ASC",
                                    $pagina);
$params = $objPaginador->getPaginacion();






require "application/views/usuario/consultarUsuarioView.phtml";