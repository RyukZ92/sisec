<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para gestionar la carga del archivo con inyecciones sql
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 * 
 */
require "librarys/Helper/Helper.php";
if(file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
    $configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-1.ini")) {
    $configMultiUsuarioParte1 = parse_ini_file("config/adicional/config.empleado-parte-1.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-2.ini")) {
    $configMultiUsuarioParte2 = parse_ini_file("config/adicional/config.empleado-parte-2.ini", true);
}
if (isset($_POST["confirmar"])) {
    #sleep(1);
     header("location:crear-diccionario-de-usuarios");
    
}
unset($_SESSION["resultados_de_prueba"]);
require "application/views/adicionales/confirmacionDeMultiUsuarioView.phtml";