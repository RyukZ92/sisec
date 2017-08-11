<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que permite mostrar y confirmar la prueba de fuerza bruta
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require "application/models/crud/CrudModel.php";
require "librarys/Helper/Helper.php";
$objCrud = new Crud();
if(file_exists("config/config.tests/config.fuerza-bruta.ini")) {
    $configFuerzaBruta = parse_ini_file("config/config.tests/config.fuerza-bruta.ini", true);
}
$nombreArchivo = $_SESSION["nombre_archivo"];

if (isset($_POST["confirmar"])) {
    sleep(1.5);
    header("refresh:0; ataque-de-fuerza-bruta");
}

require "application/views/fuerzaBruta/confirmacionDePruebaDeFuerzaBrutaView.phtml";