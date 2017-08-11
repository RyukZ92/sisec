<?php
/**
 * =============================================================================
 * @Descripcion: Controlador de confirmación de la prueba de sesión.
 * También redirecciona a la ejecución de la prueba.
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0 
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * ============================================================================= 
 */

require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
$objCrud = new Crud();
if(file_exists("config/config.tests/config.carga-de-sesion.ini")) {
    $configPruebaDeSesion = parse_ini_file("config/config.tests/config.carga-de-sesion.ini", true);
}
$nombreArchivo = $_SESSION["nombre_archivo"];

if (isset($_POST["confirmar"])) {
    sleep(1.5);
    header("refresh:0; ejecutar-prueba-de-sesion");
}

require "application/views/rendimiento/pruebaDeSesion/confirmacionDePruebaDeSesionView.phtml";