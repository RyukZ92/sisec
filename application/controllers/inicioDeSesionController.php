<?php
/**
 * =================================================
 * @Descripcion: Controlador controlar el majeo del
 * inicio de sesión al software
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * ==================================================
 * 
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

$isp = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$ipCliente = $_SERVER["REMOTE_ADDR"];
$info = Helper::getBrowser();
$navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
$so = $info["os"];
if (empty($_SESSION["activar_captcha"])) {
    $_SESSION["activar_captcha"] = null;
}
if (empty($_SESSION["intentos"])) {
    $_SESSION["intentos"] = 0;
} 
if (empty($_SESSION["intentos_fallidos"])) {
    $_SESSION["intentos_fallidos"] = 0;
}
$urlCaptcha = "librarys/cool-php-captcha-master/captcha.php";

if ($_SESSION["intentos"] == 0) {
    $ipPetecion = $ipCliente;
}
$intentos = $_SESSION["intentos"];
if ($intentos > 1) {
        
    $_SESSION["activar_captcha"] = true;
    unset($_SESSION["intentos"]);
}
$activarCaptcha = $_SESSION["activar_captcha"];

if (isset($_POST["entrar"])) {
        
        if(Helper::validarCamposVacios(array($_POST["usuario"], $_POST["contrasenia"]))) {
            $notificacion = $alerta["sesion_ingrese_credenciales"];
        } else {
            $_SESSION["intentos"]++;
            $usuario = Helper::limpiarCampo(strtolower($_POST["usuario"]));
            $contrasenia = Helper::limpiarCampo(sha1($_POST["contrasenia"]));   


            $tabla = "usuario";
            $datos = array("nombre_completo", "nombre", "email", "nivel_usuario", "id");
            $condicion = "eliminado = '0' AND lower(nombre) = '" . $usuario
                    . "' AND contrasenia = '" . $contrasenia . "'";
            $_usuario = $objCrud->consultar($tabla, $datos, $condicion);

            if (count($_usuario) > 0) {

                if ($activarCaptcha && $_POST["captcha"] != $_SESSION["captcha"]
                        ) {
                            $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
                            $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " "
                                    . "ha fallado al ingresar el captcha en el inicio de sesión con "
                                    . "el navegador " . $navegador . " ($so)";
                            $eventoLog .= "desde la IP " . $ipCliente . ", ISP: $isp"; 
                            $eventoLog .= "" . PHP_EOL;               
                            $objArchivo->agregar("logs/errorEvents.log", $eventoLog);
                            $captchaLog = true;

                        $notificacion = $alerta["sesion_captcha_incorreta"];
                } else {
                    $_SESSION["nombre"] =  $_usuario[0]["nombre_completo"];
                    $_SESSION["usuario"] =  $_usuario[0]["nombre"];
                    $_SESSION["sesion_" . $_SESSION["usuario"]] = true;
                    $_SESSION["nivel_usuario"] =  $_usuario[0]["nivel_usuario"];
                    $_SESSION["id_usuario"] = $_usuario[0]["id"];

                    $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
                    $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " inició una sesión "
                            . "con el navegador " . $navegador . " ($so) ";
                    $eventoLog .= "y desde la IP " . $ipCliente . ", ISP: $isp "; 
                    $eventoLog .= "" . PHP_EOL;               
                    $objArchivo->agregar("logs/successEvents.log", $eventoLog);


                    $datos = array("accion"     => "Inició Sesión",
                                   "fecha"      => date("Y-m-d"),
                                   "hora"       => date("H:i:s"),
                                   "ip_cliente" => $ipCliente,
                                   "navegador"  => $navegador,
                                   "so"         => $so,
                                   "isp"        => $isp,
                                   "id_usuario" => $_usuario[0]["id"]);
                    $objCrud->guardar("historial", $datos);

                    unset($_SESSION["intentos"], $_SESSION["activar_captcha"]);
                    header("location:" . URLBASE . "inicio");
                }    
            } else {
                $notificacion = $alerta["sesion_credenciales_ivalidas"];
            }
        }
    $_SESSION["intentos_fallidos"]++;    
    if ($_SESSION["intentos_fallidos"] > 0 
            && empty($_SESSION["usuario"])
            && empty($captchaLog)
            ) {
        if (empty($usuario)) {
            $usuario = "\"sin dato\"";
        }
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "intento fallido de inicio de sesión con el usuario " . strtoupper($usuario) . ", "
                . "navegador " . $navegador . " ($so) y ";
        $eventoLog .= "desde la IP " . $ipCliente . ", ISP: $isp"; 
        $eventoLog .= "" . PHP_EOL;                
        $objArchivo->agregar("logs/errorEvents.log", $eventoLog);
    }   
}
require "application/views/usuario/inicioSesionView.phtml";