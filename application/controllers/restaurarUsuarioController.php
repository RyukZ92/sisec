<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que permite restaurar un usuario eliminado del sistema
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

$id = Helper::limpiarCampo($_REQUEST["id"]);
$paginaReferencial = Helper::limpiarCampo($_REQUEST["pagina"]);

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
    $datos = array('eliminado' =>  0);
    $condicion = "id = '" . $id . "'";

    $result = $objCrud->guardar($tabla, $datos, $condicion); 
    if ($result) {
        $notificacion = $alerta["restauracion_exitosa"];
        
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
        $eventoLog .= "restauró al usuario " . strtoupper($_POST["usuario"]) . " ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);
        
        $datos = array("accion"     => "Restauró al usuario: <b>" . strtoupper($_POST["usuario"]) . "</b>",
                        "fecha"     => date("Y-m-d"),
                        "hora"      => date("H:i:s"),
                        "ip_cliente" => $ipCliente,
                        "navegador"  => $navegador,
                        "so"         => $so,
                        "id_usuario"   => $_SESSION["id_usuario"]);
        $objCrud->guardar("historial", $datos);
        header("refresh:3; " . URLBASE . $vista . "/consultar/pagina/" . $paginaReferencial);
    } else {
        $notificacion = $alerta["restaurar_error"];
    }
    
}

require "application/views/usuario/restaurarUsuarioView.phtml";