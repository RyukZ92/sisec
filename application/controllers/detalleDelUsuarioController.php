<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para capturar el id del usuario y realizar una busqueda
 * especifica de un usuario
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require_once "application/models/crud/CrudModel.php";
require_once "librarys/Helper/Helper.php";
$objCrud = new Crud();


$tabla = "usuario";
$datos = array("nombre_completo", "nombre", "email", "telefono", "nivel_usuario", "fecha_creacion");
$condicion = "id = '" . $_GET["id"] . "'";
$orden = null;
$_usuario = $objCrud->consultar($tabla, $datos, $condicion, $orden);
if (count($_usuario) == 0) {
    $error = $alerta["sin_registro"];
}

require "application/views/usuario/detalleDelUsuarioView.phtml";