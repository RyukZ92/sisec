<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que procesa el cierre de sesión del usuario
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */
require "librarys/Helper/Helper.php";
require "application/models/crud/CrudModel.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

$ipCliente = $_SERVER["REMOTE_ADDR"];
$isp = gethostbyaddr($_SERVER["REMOTE_ADDR"]);

$info = Helper::getBrowser();
$navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
$so = $info["os"];

$eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
$eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " cerró su sesión "
        . " con el navegador " . $navegador . " ($so) y ";
$eventoLog .= "desde la IP " . $ipCliente . ", ISP $isp"; 
$eventoLog .= "" . PHP_EOL;
$objArchivo->agregar("logs/successEvents.log", $eventoLog);
                
$datos = array("accion"     => "Cerró Sesión",
               "fecha"      => date("Y-m-d"),
               "hora"       => date("H:i:s"),
               "ip_cliente" => $ipCliente,
               "navegador"  => $navegador,
               "so"         => $so,
               "id_usuario" => $_SESSION["id_usuario"]);

$objCrud->guardar("historial", $datos);

foreach ($_SESSION as $llave => $valor) {
    unset($_SESSION[$llave]);
    }

header("refresh:0;" . URLBASE . "iniciar-sesion");
