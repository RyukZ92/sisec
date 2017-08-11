<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para procesar la recuperación de contrase por pregunta
 * y respuesta secreta del usuario
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abril, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objCrud = new Crud();
$objArchivo = new GestorDeArchivos();
$datos = array("id", "nombre", "pregunta_secreta", "respuesta_secreta");
$condicion =  "lower(nombre) = '" . $_SESSION["usuario"] ."'";
$_usuario = $objCrud->consultar("usuario", $datos, $condicion, null);
$_usuario[0]["pregunta_secreta"] = Helper::decrypt($_usuario[0]["pregunta_secreta"], $_usuario[0]["id"]);
$urlCaptcha = "librarys/cool-php-captcha-master/captcha.php";

if (isset($_POST["enviar"])) {
    if (Helper::validarCamposVacios(array($_POST["respuesta"], $_POST["captcha"]))) {
        
        $notificacion = $alerta["campos_vacios"];
} else if (strtolower($_POST["respuesta"]) != Helper::decrypt($_usuario[0]["respuesta_secreta"], $_usuario[0]["id"])) {
        $notificacion = $alerta["respuesta_secreta_incorrecta"];        
    } else if (strtolower($_POST["captcha"]) != $_SESSION["captcha"]) {
        $notificacion = $alerta["captcha_incorrecta"];        
    }  else {

        $nuevaContrasenia = Helper::generarContraseniaAleatorio();
        $recuperacion = 1;
        $tabla = "usuario";
        $datos = array("contrasenia" => sha1($nuevaContrasenia));
        $condicion =  "lower(nombre) = '" . strtolower($_SESSION["usuario"]) ."'";
        $objCrud->guardar($tabla, $datos, $condicion);
        
        $ipCliente = $_SERVER["REMOTE_ADDR"];
        $info = Helper::getBrowser();
        $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
        $so = $info["os"];
        
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
        $eventoLog .= "realizó una recuperación de contraseña mediante pregunta y respuesta secreta ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);  
        
        $datos = array("accion"     => "Recuperó contraseña mediante pregunta y respuesta secreta",
                        "ip_cliente" => $ipCliente,
                        "navegador"  => $navegador,
                        "so"         => $so,                
                        "fecha"      => date("Y-m-d"),
                        "hora"       => date("H:i:s"),                 
                        "id_usuario" => $_usuario[0]["id"]);
        $objCrud->guardar("historial", $datos);
        unset($_POST);
        unset($_SESSION["captcha"], $_SESSION["usuario"]);
        
    }
}
require "application/views/usuario/recuperarContraseniaPorPreguntaYRespuestaSecretaView.phtml";