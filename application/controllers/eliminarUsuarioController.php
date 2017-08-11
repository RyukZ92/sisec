<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para gestionar la eliminación de un usuario
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
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$id = Helper::limpiarCampo($_REQUEST["id"]);
$paginaReferencial = Helper::limpiarCampo($_REQUEST["pagina"]);
if ($_SESSION["id_usuario"] != $id) {
    $objArchivo = new GestorDeArchivos();
    $objCrud = new Crud();
    
    $ipCliente = $_SERVER["REMOTE_ADDR"];
    $info = Helper::getBrowser();
    $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
    $so = $info["os"];

    $tabla = "usuario";
    $datos = array("nombre, email, nivel_usuario");
    $condicion = "id = '" . $id . "'";
    $orden = null;
    $_usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);
    if ($_POST["confirmar"]) {

        $tabla = "usuario";
        $datos = array('eliminado' =>  1);
        $condicion = "id = '" . $id . "'";

        $result = $objCrud->guardar($tabla, $datos, $condicion); 
        if ($result) {
            $notificacion = $alerta["eliminacion_exitosa"];

            $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
            $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
            $eventoLog .= "eliminó al usuario " . strtoupper($_POST["usuario"]) . " ";
            $eventoLog .= "desde la IP " . $ipCliente . ", "; 
            $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
            $objArchivo->agregar("logs/successEvents.log", $eventoLog);

            $datos = array("accion"      => "Eliminó al usuario: <b>" . strtoupper($_POST["usuario"]) . "</b>",
                            "fecha"      => date("Y-m-d"),
                            "hora"       => date("H:i:s"),  
                            "ip_cliente" => $ipCliente,
                            "navegador"  => $navegador,
                            "so"         => $so,
                            "id_usuario" => $_SESSION["id_usuario"]);
            $objCrud->guardar("historial", $datos);
            header("refresh:3;" . URLBASE . $vista . "/listado/pagina/" . $paginaReferencial);
        } else {
            $notificacion = $alerta["eliminacion_error"];
        }

    }
    require "application/views/usuario/eliminarUsuarioView.phtml";
} else {
    header("refresh:0;" . URLBASE . $vista . "/listado/pagina/" . $paginaReferencial);
}