<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para actualizar datos delusuario que está en una 
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
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
$tabla = "usuario";
$datos = null;
$condicion = "id = '" . $_SESSION["id_usuario"] . "'";
$orden = null;
$_usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);
$guardar = null;
$contrasenia = null;



if (isset($_POST["actualizar"])) {        
    if (Helper::validarCamposVacios(array($_POST["email"]))) {     
        $mensajes[] = "<li class='text-danger'>Rellene los campos requeridos</li>";
        $guardar = null;

    }
    if (sha1($_POST["contrasenia_actual"]) != $_usuario[0]["contrasenia"]) {        
        $mensajes[] = "<li class='text-danger'>La contraseña actual es incorrecta</li>";
        $contrasenia = true;
    }
    if (strlen($_POST["nueva_contrasenia"]) > 0) {
        if (Helper::validarContrasenia($_POST["nueva_contrasenia"])) {
            $mensajes[] = "<li class='text-danger'>Ajuste la contraseña al formato permitido</li>";
        } else if ($_POST["contrasenia2"] != $_POST["nueva_contrasenia"]) {
            $mensajes[] = "<li class='text-danger'>Las contraseñas no coinciden</li>";
        } else {
            $guardar = true;
        }
    } else {
        if (strlen($_POST["pregunta"]) > 0 && empty($_POST["respuesta"])) {    
            $mensajes[] = "<li class='text-danger'>Rellene la respuesta secreta</li>";
            $guardar = null;
        } else if (strlen($_POST["respuesta"]) > 0 && empty($_POST["pregunta"])) {    
            $mensajes[] = "<li class='text-danger'>Rellene la pregunta secreta</li>";
            $guardar = null;
        } else {
            $guardar = true;
        }
    }
    if ($guardar == true && $contrasenia == null) {
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
        $eventoLog .= "realizó una actualización de sus datos de usuario ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);        
        
        $nombre = Helper::limpiarCampo($_POST["nombre"]);
        $email = Helper::limpiarCampo($_POST["email"]);
        $email = strtolower($email);
        $telefono = Helper::limpiarCampo($_POST["telefono"]);
        $contrasenia = empty($_POST["nueva_contrasenia"]) 
                ? $_usuario[0]["contrasenia"] 
                : sha1($_POST["nueva_contrasenia"]);
        
        $pregunta = empty($_POST["pregunta"]) 
                ? $_usuario[0]["pregunta_secreta"] 
                : Helper::encrypt($_POST["pregunta"], $_SESSION["id_usuario"]);
        $respuesta = empty($_POST["respuesta"]) 
                ? $_usuario[0]["respuesta_secreta"] 
                : Helper::encrypt(strtolower($_POST["respuesta"]), $_SESSION["id_usuario"]);
        $tabla = "usuario";
        $datos = array('nombre_completo'    =>  $nombre,
                        'email'             =>  $email,
                       'contrasenia'        =>  $contrasenia,
                       'telefono'           =>  $telefono,
                       'pregunta_secreta'   =>  $pregunta,
                       'respuesta_secreta'  =>  $respuesta);
        $condicion = "id = '" . $_SESSION["id_usuario"] . "'";

        $result = $objCrud->guardar($tabla, $datos, $condicion); 
        if ($result) {
            $notificacion = $alerta["actualizacion_exitosa"];
            $ipCliente = $_SERVER["REMOTE_ADDR"];
            $info = Helper::getBrowser();
            $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
            $so = $info["os"];
            $datos = array( "accion"     => "Realizó una actualización a sus datos de usuario",
                            "ip_cliente" => $ipCliente,
                            "navegador"  => $navegador,
                            "so"         => $so,                
                            "fecha"      => date("Y-m-d"),
                            "hora"       => date("H:i:s"),                  
                            "id_usuario"   => $_SESSION["id_usuario"]);
            $objCrud->guardar("historial", $datos, null, null);
            header("refresh:1.5;" . URLBASE . "usuario/cuenta");
        } else {
            $notificacion = $alerta["actualizacion_error"];
        }
    }
}
require "application/views/usuario/ayuda/ayuda.phtml";
require "application/views/usuario/actualizarMisDatosView.phtml";