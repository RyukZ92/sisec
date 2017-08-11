<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para proecesar la recuperación del usuario por email
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abril, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */ 
require "librarys/phpmailer/enviar_email.php";
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objCrud = new Crud();
$objArchivo = new GestorDeArchivos();
$datos = array("nombre", "email", "id");
$condicion =  "lower(email) = '" . $_POST["email"] ."'";
$_usuario = $objCrud->consultar("usuario", $datos, $condicion);

$urlCaptcha = "librarys/cool-php-captcha-master/captcha.php";

if (isset($_POST["enviar"])) {
    if (Helper::validarCamposVacios(array($_POST["email"]))) {        
        $notificacion = $alerta["campos_vacios"];
    } else if (count($_usuario) == 0) {
        $notificacion = $alerta["email_no_existe"];        
    } else if (strtolower ($_POST["captcha"]) != $_SESSION["captcha"]) {
        $notificacion = $alerta["captcha_incorrecta"];        
    }  else {
        $nuevaContrasenia = Helper::generarContraseniaAleatorio();
        $recuperacion = 1;
        $tabla = "usuario";
        $datos = array("contrasenia" => sha1($nuevaContrasenia));
        $condicion =  "lower(nombre) = '" . strtolower($_usuario[0]["nombre"]) ."'";
        $objCrud->guardar($tabla, $datos, $condicion, null);
        
        smtpmailer(strtolower($_POST['email']),'sisec@gmail.com','Sistema Automatizado SISEC',
                                        'Recuperación de Contraseña'  ,
                                        "<font FACE='roman' size='3'>Hola " . $_usuario[0]["nombre"] . ",
                                        <br><br>Sus credenciales de acceso son:<br> <b>Usuario: </b>" . $_usuario[0]["nombre"] . "
                                        <br><b>Contraseña: </b>" . trim($nuevaContrasenia) . "
                                        <br><a href=".URLBASE.">Enlace de Acceso</a>
                                        <br><br><br>
                                        <font size='2'><b>Nota:</b> No responda este correo electrónico, no está siendo monitoreado.</font></font>");
        
        $ipCliente = $_SERVER["REMOTE_ADDR"];
        $info = Helper::getBrowser();
        $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
        $so = $info["os"];
        
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_usuario[0]["nombre"]) . " ";
        $eventoLog .= "realizó una recuperación de contraseña mediante correo electrónico ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);  
        
        $datos = array("accion"     => "Recuperó contraseña mediante correo electrónico",
                        "ip_cliente" => $ipCliente,
                        "navegador"  => $navegador,
                        "so"         => $so,                
                        "fecha"      => date("Y-m-d"),
                        "hora"       => date("H:i:s"),                 
                        "id_usuario" => $_usuario[0]["id"]);
        $objCrud->guardar("historial", $datos);
        
        unset($_POST);
        unset($_SESSION);
        
    }
}
require "application/views/usuario/recuperarContraseniaPorEmailView.phtml";