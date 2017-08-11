<?php
/**
 * =============================================================================
 * @Descripcion: Controlador simple para motrar la vista de inicio. También es
 * utilizada para recargar los datos de sesión del usuario, esto se hace para
 * mantener actualido los datos en las vistas mostradas
 * @Author: Miguel Salazar
 * @Fecha: Marzo, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 */
require "application/models/crud/CrudModel.php";
$objCrud = new Crud();

$tabla = "usuario";
$datos = null;
$condicion = "id = '" . $_SESSION["id_usuario"] . "'";
$orden = null;
$_usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);

$_SESSION["nombre"] =  $_usuario[0]["nombre_completo"];
$_SESSION["usuario"] =  $_usuario[0]["nombre"];
$_SESSION["sesion_" . $_SESSION["usuario"]] = true;
$_SESSION["nivel_usuario"] =  $_usuario[0]["nivel_usuario"];
$_SESSION["id_usuario"] = $_usuario[0]["id"];

require "application/views/informativo/inicioView.phtml";
//echo $_COOKIE["sesion"];
