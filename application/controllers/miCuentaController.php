<?php
/**
 * =============================================================================
 * @Descripcion: Controlador procesar o capturar los datos del usuario actual
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.0
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

require "application/views/usuario/miCuentaView.phtml";