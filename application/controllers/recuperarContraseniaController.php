<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
$objCrud = new Crud();
if (isset($_POST["enviar"])) {
    $dni = empty($_POST["dni"]) ? 1 :$_POST["dni"];
    $usuario = empty($_POST["usuario"]) ? 1 : strtolower($_POST["usuario"]);
    if (Helper::validarCamposVacios(array($_POST["opcion_recuperacion"]))) {
        $notificacion = $alerta["campos_vacios"];
    } else if((Helper::validarCamposVacios(array($_POST["usuario"])) 
            && $_POST["opcion_recuperacion"] != "email")
            ) {
        $notificacion = $alerta["campos_vacios"];
    }
        else if ($objCrud->contarFilas("usuario", "nombre = '" . $usuario . "'") == 0
                && $_POST["opcion_recuperacion"] != "email") {
        $notificacion = $alerta["registro_no_existe"];
    } else {        
        $_SESSION["usuario"] = $_POST["usuario"];
        $_SESSION["opcion_recuperacion"] = $_POST["opcion_recuperacion"];
        $_SESSION["recuperacion_contrasenia"] = true;
        if ($_POST["opcion_recuperacion"] == "pregunta") {
            header("location:" . URLBASE . "recuperacion-por-pregunta-y-respuesta-secreta");
        } else {
            header("location:" . URLBASE . "recuperacion-por-email");
        }
    }
}

require "application/views/usuario/recuperarContraseniaView.phtml";