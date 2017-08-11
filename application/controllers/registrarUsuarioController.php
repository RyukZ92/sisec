<?php
/**
 * @Descripcion: Controlador de Registro de Usuario 
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */

require "librarys/phpmailer/enviar_email.php";
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if (isset($_POST["registrar"])) {
    
    $condicion = "lower(nombre) = '".strtolower($_POST["usuario"])."'";
    $_usuarioExiste = -$objCrud->contarFilas("usuario", $condicion);
    $condicion = "lower(email) = '".strtolower($_POST["email"])."'";
    $_emailExiste = -$objCrud->contarFilas("usuario", $condicion);
    
    if (Helper::validarCamposVacios(array(  $_POST["nombre"],
                                            $_POST["usuario"],
                                            $_POST["contrasenia"],
                                            $_POST["contrasenia2"],                                                    
                                            $_POST["email"],
                                            $_POST["nivel_usuario"]))
    ) {
       $mensajes[] = "<li class='text-danger'>Rellene los campos requeridos</li>";
    } 
    if($_usuarioExiste) {
        $mensajes[] = "<li class='text-danger'>El nombre de usuario ingresado se encuentra registrado</li>";
        } 
    if($_emailExiste) {
            $mensajes[] = "<li class='text-danger'>El correo electrónico ingresado se encuentra registrado</li>";
        }
    if (Helper::validarContrasenia($_POST["contrasenia"])) {
            $mensajes[] = "<li class='text-danger'>Ajuste la contraseña al formato permitido</li>";
        }
    if ($_POST["contrasenia"] != $_POST["contrasenia2"]) {
            $mensajes[] = "<li class='text-danger'>Las contraseñas no coinciden</li>";
        }
     if(count($mensajes) == 0) {

        $ipCliente = $_SERVER["REMOTE_ADDR"];
        $info = Helper::getBrowser();
        $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
        $so = $info["os"];
        # Guaradr eventos en LOG
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
        $eventoLog .= "registró al nuevo usuario " . strtoupper($_POST["usuario"]) . " ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);

        #$contrasenia = Helper::generarContraseniaAleatorio();
        $nombre = Helper::limpiarCampo($_POST["nombre"]);
        $usuario = Helper::limpiarCampo($_POST["usuario"]);
        $email = Helper::limpiarCampo(trim(strtolower($_POST["email"])));
        $contrasenia = sha1($_POST["contrasenia"]);
        $telefono = Helper::limpiarCampo($_POST["telefono"]);
        $nivelDeUsuario = Helper::limpiarCampo($_POST["nivel_usuario"]);
        $tabla = 'usuario';
        $datos = array('nombre_completo' =>  $nombre,
                       'nombre'          =>  $usuario,
                       'contrasenia'     =>  $contrasenia,
                       'email'           =>  $email,
                       'telefono'        =>  $telefono,
                       'fecha_creacion'  =>  date("Y-m-d"),
                       'hora_creacion'   =>  date("H:i:s"),
                       'nivel_usuario'   =>  $nivelDeUsuario);
        $result = $objCrud->guardar($tabla, $datos);
        if ($result) {           
            /*smtpmailer(strtolower($_POST['email']), 'miguelangel.bux@hotmail.com', 'Sistema Automatizado SISEC',
                                            'Bienvenido a SISEC'  ,
                                            "<font FACE='roman' size='3'>¡Hola!, se le creado un usuario y contraseña de acceso en nuestra aplicación web SISEC.
                                            <br><br>Sus credenciales son:<br> <b>Usuario: </b>" . $_POST["usuario"] . "
                                            <br><b>Contraseña: </b>" . trim($contrasenia) . "
                                            <br><a href=".URLBASE.">Enlace de Acceso</a>
                                            <br><br><br>
                                            <font size='2' color='red'><b>Nota:</b> No responda este correo electrónico, no está siendo monitoreado.</font></font>");
        */
            $datos = array("accion"     => "Realizó un registro de usuario (<b>" . $_POST["usuario"] . "</b>)",
                           "fecha"      => date("Y-m-d"),
                           "hora"       => date("H:i:s"),   
                           "ip_cliente" => $ipCliente,
                           "navegador"  => $navegador,
                           "so"         => $so,
                           "id_usuario" => $_SESSION["id_usuario"]);
            $objCrud->guardar("historial", $datos, null, null);
            unset($_POST);
            $notificacion = $alerta["registro_exitoso"];
        } else {
            $notificacion = $alerta["registro_error"];
        }        
    }
}
include "application/views/usuario/ayuda/ayuda.phtml";
require "application/views/usuario/registrarUsuarioView.phtml";
