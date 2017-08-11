<?php
/**
 * =============================================================================
 * @Descripcion: Controlador paramostra la vista de carga rchivo de inyección sql
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 */
require "librarys/Helper/Helper.php";
if(file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
    $configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
}
if(file_exists("config/config.tests/config.prueba-de-carga.ini")) {
    $configPruebaDeCarga = parse_ini_file("config/config.tests/config.prueba-de-carga.ini", true);
}
if (isset($_POST["confirmar"])) {
    #sleep(1);
     header("location:ejecutar-prueba-de-carga");    
}

require "application/views/pruebaDeCarga/confirmacionDePruebaDeCargaView.phtml";