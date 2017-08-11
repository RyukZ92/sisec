<?php
/**
 * @Descripcion: Controlador de Edición de Usuario 
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
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


$tabla = "usuario";
$datos = array("nombre_completo", "nombre", "email", "nivel_usuario", "id", "telefono");
$condicion = "id = '" . $id . "'";
$orden = null;
$_usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);
$condicion = "lower(nombre) = '".strtolower($_POST["usuario"])."' "
        . "AND lower(nombre) != '".strtolower($_usuario[0]["nombre"])."'";
$_usuarioExiste = -$objCrud->contarFilas("usuario", $condicion);
$condicion = "lower(email) = '".strtolower($_POST["email"])."' "
        . "AND lower(email) != '".strtolower($_usuario[0]["email"])."'";
$_emailExiste = -$objCrud->contarFilas("usuario", $condicion);
if (isset($_POST["actualizar"])) {
    if (Helper::validarCamposVacios(array($_POST["usuario"], $_POST["nombre_completo"], $_POST["email"], $_POST["nivel_usuario"]))) {
        $mensajes[] = "<li class='text-danger'>Rellene los campos requeridos</li>";
    }
    if($_usuarioExiste) {
        $mensajes[] = "<li class='text-danger'>El nombre de usuario ingresado se encuentra registrado</li>";
    }
    if($_emailExiste) {
        $mensajes[] = "<li class='text-danger'>El correo electrónico ingresado se encuentra registrado</li>";
    }
    if (count($mensajes) == 0) {

        $nombreCompleto = Helper::limpiarCampo($_POST["nombre_completo"]);
        $usuario = Helper::limpiarCampo($_POST["usuario"]);
        $email = Helper::limpiarCampo($_POST["email"]);
        $telefono = Helper::limpiarCampo($_POST["telefono"]);
        $nivelUusuario = Helper::limpiarCampo($_POST["nivel_usuario"]);
        
        $tabla = "usuario";
        $datos = array('nombre_completo'    =>  $nombreCompleto,
                       'nombre'             =>  $usuario,
                       'email'              =>  $email,
                       'telefono'           =>  $telefono,
                       'nivel_usuario'      =>  $nivelUusuario);
        $condicion = "id = '" . $id . "'";

        $result = $objCrud->guardar($tabla, $datos, $condicion); 
        if ($result) {
            
            $notificacion = $alerta["actualizacion_exitosa"];
            
            $ipCliente = $_SERVER["REMOTE_ADDR"];
            $info = Helper::getBrowser();
            $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
            $so = $info["os"];
            $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
            $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " ";
            $eventoLog .= "actualizó al usuario " . strtoupper($_POST["usuario"]) . " ";
            $eventoLog .= "desde la IP " . $ipCliente . ", "; 
            $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;                 
            $objArchivo->agregar("logs/successEvents.log", $eventoLog);
            
            $datos = array("accion"     => "Actualizó al usuario: <b>" . strtoupper($_POST["usuario"]) . "</b>",
                           "fecha"      => date("Y-m-d"),
                           "hora"       => date("H:i:s"),
                           "ip_cliente" => $ipCliente,
                           "navegador"  => $navegador,
                           "so"         => $so,
                           "id" => $_SESSION["id"]);
            $objCrud->guardar("historial", $datos);
            $_SESSION["nivel_usuario"] = $_POST["nivel_usuario"];
            header("refresh:2;" . URLBASE . $vista . "/detalle/$id");
        } else {
            $notificacion = $alerta["actualizacion_error"];
        }
    }
}
require "application/views/usuario/editarUsuarioView.phtml";
} else {
    header("refresh:0;" . URLBASE . $vista . "/detalle/$id");
}