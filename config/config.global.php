<?php
/**
 * =============================================================================
 * @Descripcion: Configuración general del software; se especifica el contro del cache,
 * la zona horaria, la url global del sistema y la ruta raiz del mismo
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */
header("Cache-control: private");
date_default_timezone_set("America/Caracas");#Se define la zona horaria

#define("URLBASE", "http://sisec.no-ip.org/"); # Se define la ubicación URL del servidor base ESTÁTICO
define('URLBASE', 'http://localhost/sisec/'); # Se define la ubicación URL del servidor base ESTÁTICO
//define("URLBASE", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/"); # DINÁMICO


define('RUTARAIZ', $_SERVER['DOCUMENT_ROOT']. '/sisec/'); #Se define la ruta base que tiene el sistema

?>
