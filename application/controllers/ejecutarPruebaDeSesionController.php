<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que activa la ejecución de la prueba de carga
 * @Author: Miguel Salazar
 * @Fecha: Marzo, 2016
 * @Version: 1.1
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * ============================================================================= 
 */



if(file_exists("config/config.tests/config.carga-de-sesion.ini")) {
    $configPruebaDeSesion = parse_ini_file("config/config.tests/config.carga-de-sesion.ini", true);
}

if(file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
    $configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-1.ini")) {
    $configMultiUsuarioParte1 = parse_ini_file("config/adicional/config.empleado-parte-1.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-2.ini")) {
    $configMultiUsuarioParte2 = parse_ini_file("config/adicional/config.empleado-parte-2.ini", true);
}
$configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
$configPruebaDeCarga = parse_ini_file("config/config.tests/config.prueba-de-carga.ini", true);

require_once "librarys/Helper/Helper.php";
require_once "application/models/CargaDeSesionModel.php";   
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
require_once "application/models/crud/CrudModel.php";
require_once "application/models/DiccionarioDeUsuariosModel.php";

$objCrud = new Crud();
if(file_exists("config/config.basica-del-sistema-en-evaluacion.ini")) {
    $configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-1.ini")) {
    $configMultiUsuarioParte1 = parse_ini_file("config/adicional/config.empleado-parte-1.ini", true);
}
if(file_exists("config/adicional/config.empleado-parte-2.ini")) {
    $configMultiUsuarioParte2 = parse_ini_file("config/adicional/config.empleado-parte-2.ini", true);
}
if (empty($_SESSION["resultados_de_prueba"])) {
    if (empty($_SESSION["nombre_archivo"])) {        
        
        $urlSesion = $configSistemaEnEvaluacion["url_sesion"];
        $datos = $configSistemaEnEvaluacion["login"];    
        $mensajeDeError = $configSistemaEnEvaluacion["mensaje_de_error"];

        $multiUsuario = new DiccionarioDeUsuarios();
        $multiUsuario->setDatosDeSesion($urlSesion, $datos, $mensajeDeError);

        $urlParte1 = $configMultiUsuarioParte1["url"];
        $urlParte2 = $configMultiUsuarioParte2["url"];
        if(file_get_contents($urlSesion)) {   
            $totalCamposParte1 = $configMultiUsuarioParte1["total_campos"];
            $totalCamposParte2 = $configMultiUsuarioParte2["total_campos"];

            $mensajeDeExito = $configMultiUsuarioParte2["cadena_valida"];

            $usuario = $_SESSION["usuario"];
            $nivelDeUsuario = $_SESSION["nivel_usuario"];

            for($i=0; $i<$totalCamposParte1; $i++) {
                foreach ($configMultiUsuarioParte1["campos_$i"] as $llave => $valor) {
                    $datosParte1[$i][$llave] =  $valor;
                }
            }
            for($i=0; $i<$totalCamposParte2; $i++) {
                foreach ($configMultiUsuarioParte2["campos_$i"] as $llave => $valor) {
                    $datosParte2[$i][$llave] =  $valor;
                }
            }        

            $cantidad = $_SESSION["cantidad"];
            $camposAdicionalesParte1 = $configMultiUsuarioParte1["campo_adicional"];
            $camposAdicionalesParte2 = $configMultiUsuarioParte2["campo_adicional"];

            $multiUsuario->setDatosDeCarga($urlParte1, $urlParte2,
                                           $datosParte1, $datosParte2,
                                           $camposAdicionalesParte1, $camposAdicionalesParte2,
                                           $mensajeDeExito, $_SESSION["usuarios"], 
                                           $_SESSION["nivel_usuario"],
                                           100);    

            $result = $multiUsuario->CrearMultiplesUsuarios();

            $_SESSION["ruta_raiz_de_archivo"] = $result["ruta_raiz_de_archivo"];
            
            
            $objArchivo = new GestorDeArchivos(); //Guardando el evento realizado
            
            $ipCliente = $_SERVER["REMOTE_ADDR"];
            $info = Helper::getBrowser();
            $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
            $so = $info["os"];

            $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
            $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"])
                    . " generó un diccionario de usuarios para la prueba de sesión (".$result['total_creado']." usuarios) ";
            $eventoLog .= "desde la IP " . $ipCliente . ", "; 
            $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;               
            $objArchivo->agregar("logs/successEvents.log", $eventoLog);

            $datos = array("accion"     => "Generó un diccionario de usuarios para la prueba de sesión (".$result['total_creado']." usuarios)",
                           "fecha"      => date("Y-m-d"),
                           "hora"       => date("H:i:s"),
                           "ip_cliente" => $ipCliente,
                           "navegador"  => $navegador,
                           "so"         => $so,
                           "id_usuario" => $_SESSION["id_usuario"]);
            $objCrud->guardar("historial", $datos);
        } else {
            $notificacion = $alerta["ruta_invalidad"];
            $error = 1;
        }
    } else {
        $_SESSION["ruta_raiz_de_archivo"] = $configPruebaDeSesion["archivo"];        
    }
    $urlDeSesion = $configPruebaDeSesion["url"];
    $urlDeInicio = $configPruebaDeSesion["urlDeInicio"];
    $urlDeSalir = $configPruebaDeSesion["urlDeSalir"];
    $mensajeDeError = $configPruebaDeSesion["cadena_invalida"];
    $campoContrasenia = $configPruebaDeSesion["campo_contrasenia"];
    $campoUsuario = $configPruebaDeSesion["campo_usuario"];
    $campoAdicional = $configPruebaDeSesion["campo_adicional"];
    $rutaArchivo = $_SESSION["ruta_raiz_de_archivo"];
    $objSesion = new CargaDeSesion();
    $objSesion->setDatosDeSesion($urlDeSesion, $urlDeInicio, $urlDeSalir, $campoUsuario, $campoContrasenia, 
                                $campoAdicional, $mensajeDeError, 
                                $rutaArchivo, $_SESSION["usuarios"], 
                                           $_SESSION["nivel_usuario"]);
    $result = $objSesion->cargarSesiones();
    $_SESSION["ruta_de_archivo"] = $result["ruta_de_archivo"];
    $_SESSION["resultados_de_prueba"] = $objSesion->mostrar();
        if($result["result_prueba"]) {
            $objCrud = new Crud();

            $_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");                
            
            $datos = array(
                "tipo"                  =>  "Carga de Sesiones",
                "fecha"                 =>  $result["fecha"],
                "hora"                  =>  $result["hora"],
                "ruta_de_archivo"       =>  $result["ruta_de_archivo"],
                "id_usuario"            =>  $_SESSION["id_usuario"],
                "id_ciclo_evaluacion"   =>  $_ciclo[0]["id"]
            );
            $prueba = $objCrud->guardar("prueba", $datos, null, true);
            if($prueba) {
                $datos = array(
                    "sesiones_totales"    =>    $result["sesiones_totales"],
                    "sesiones_exitosas"   =>    $result["sesiones_exitosas"],
                    "sesiones_fallidas"   =>    $result["sesiones_fallidas"],
                    "tiempo_de_ejecucion" =>    $result["tiempo_de_ejecucion"] . " segundos",
                    "resultado"           =>    $result["resultado"],
                    "ruta_de_archivo"     =>    $result["ruta_de_archivo"],                    
                    "id_prueba"           =>    $prueba
                );
                $guardar = $objCrud->guardar("resultado_carga_de_sesiones", $datos);
                $ipCliente = $_SERVER["REMOTE_ADDR"];
                $info = Helper::getBrowser();
                $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
                $so = $info["os"];
                $datos = array( "accion"     => "Realizó una prueba de carga de sesiones",
                                "ip_cliente" => $ipCliente,
                                "navegador"  => $navegador,
                                "so"         => $so,                
                                "fecha"      => date("Y-m-d"),
                                "hora"       => date("H:i:s"),                  
                                "id_usuario"   => $_SESSION["id_usuario"]);
                $objCrud->guardar("historial", $datos, null, null);
            } else {
                die("\nERROR AL INTENTAR GUARDAR LA PRUEBA EN LA BD");           
            }
        }
    $datos = array("accion"     => "Realizó una prueba de carga de sesiones",
                   "fecha"      => date("Y-m-d"),
                   "hora"       => date("H:i:s"),
                   "ip_cliente" => $ipCliente,
                   "navegador"  => $navegador,
                   "so"         => $so,
                   "id_usuario" => $_SESSION["id_usuario"]);
    $objCrud->guardar("historial", $datos);
    
}
require_once "application/views/rendimiento/pruebaDeSesion/resultadosDePruebaDeSesionView.phtml";
