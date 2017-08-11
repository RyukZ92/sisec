<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para manejar la confirmación de la prueba de inyección sql
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
if(file_exists("config/config.tests/config.inyeccion-sql.ini")) {
    $configSQL = parse_ini_file("config/config.tests/config.inyeccion-sql.ini", true);
}
$nombreArchivo = $_SESSION["nombre_archivo"];
$campo = $_SESSION["campo"];

if (isset($_POST["confirmar"])) {
    sleep(1.5);
    header("refresh:0;inyectar-sql");
}
unset($_SESSION["inyeccion_sql"]);
require "application/views/inyeccionSql/confirmacionDePruebaDeInyeccionSqlView.phtml";