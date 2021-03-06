<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que permite procesar lsod datos de la configuración
 * de la creación de multiusuario o diccionario de datos
 * sesión iniciada
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 * 
 */

require "application/models/crud/CrudModel.php";
require "librarys/GestorDeArchivos/GestorDeArchivos.php";
require "librarys/Helper/Helper.php";
if(file_exists("config/adicional/config.empleado-parte-1.ini")) {
    $configMultiUsuarioParte1 = parse_ini_file("config/adicional/config.empleado-parte-1.ini", true);
}
$objArchivo = new GestorDeArchivos();
$objCrud = new Crud();

if(isset($_POST["continuar"])) {
    $_SESSION["cantidad"] = $_POST["cantidad"];
    if(Helper::validarCamposVacios(array($_POST["url"], $_POST["campo"], 
                                         $_POST["tipo"], $_POST["longitud"]))
    ) {
        $notificacion = $alerta["campos_vacios"];
    } else {
        $contenido .= "## Archivo de Configuración para Registro de Empleados Parte 1" . PHP_EOL;
        $contenido .= "## Última actualización automática: " . date("d-m-Y") . " " 
                   . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "## Usuario: " . strtoupper($_SESSION["usuario"]) ." (". $_SESSION["nivel_usuario"] .")". PHP_EOL.PHP_EOL;
        #$contenido .= "cantidad = \"" . $_POST["cantidad"] . "\"" . PHP_EOL;
        $contenido .= "url = \"" . $_POST["url"] . "\"" . PHP_EOL;
        #$contenido .= "cadena_valida = \"" . $_POST["cadena_valida"] . "\"" . PHP_EOL;
        $contenido .= "total_campos = \"" . count($_POST["total_campos"]) . "\"" . PHP_EOL.PHP_EOL;
        for ($i=0; $i<count($_POST["total_campos"]); $i++) {
            $contenido .= "[campos_$i]" . PHP_EOL;
            $contenido .= "nombre = \"" . $_POST["campo"][$i] . "\"" . PHP_EOL;
            $contenido .= "tipo = \"" . $_POST["tipo"][$i] . "\"" . PHP_EOL;
            $contenido .= "longitud = \"" . $_POST["longitud"][$i] . "\"" . PHP_EOL . PHP_EOL;
        }
	$contenido .= substr($contenido_array, 0, -1) . PHP_EOL . PHP_EOL;        
        $contenido .= "[campo_adicional]" . PHP_EOL;
        for($i=0; $i<count($_POST["nombre_campo_adicional"]); $i++) {            
            $contenido_array_adicional .= $_POST["nombre_campo_adicional"][$i] . " = \"" 
                                       . $_POST["valor_campo_adicional"][$i] . "\"" . PHP_EOL;
        }
        $contenido .= substr($contenido_array_adicional, 0, -1);

       $archivo = $objArchivo->escrbir("config/adicional/config.empleado-parte-1.ini", $contenido);

        if($archivo) {
          #$notificacion = $alerta["actualizacion_exitosa"];
          header("refresh:0;" . URLBASE . $vista . "/configurar-multi-usuarios-parte-2");
           //header("refresh:6;". URLBASE . $vista . "/" . $opcion);
        } else {
   
          $notificacion = $alerta["actualizacion_error"];
        }
    }
}

require "application/views/adicionales/configurarMultiUsuariosParte1View.phtml";