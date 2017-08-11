<?php
/**
 * @Descripcion: Controlador que activa la ejecución de la prueba de carga
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */
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
//if(empty($_SESSION["resultados_de_prueba"])) {
if (empty($_SESSION["resultados_de_prueba"])) {
    
    $urlSesion = $configSistemaEnEvaluacion["url_sesion"];
    $datos = $configSistemaEnEvaluacion["login"];    
    $mensajeDeError = $configSistemaEnEvaluacion["mensaje_de_error"];
    
    $multiUsuario= new DiccionarioDeUsuarios();  

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
                                       $mensajeDeExito, $usuario, 
                                       $nivelDeUsuario,
                                       $cantidad);    

        $result = $multiUsuario->CrearMultiplesUsuarios();
        #print_r($result);
        $_SESSION["resultados_de_prueba"] = $multiUsuario->mostrar();
        $_SESSION["ruta_de_archivo"] = $result["ruta_de_archivo"];

        /*Guardando actividad realizada*/
        $objArchivo = new GestorDeArchivos(); //Guardando el event
        
        $ipCliente = $_SERVER["REMOTE_ADDR"];
        $info = Helper::getBrowser();
        $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
        $so = $info["os"];
        
        $eventoLog = date("d/m/Y") . " " . Helper::convertirHoraA12H(date("H:i:s")) . ", ";
        $eventoLog .= "el usuario " . strtoupper($_SESSION["usuario"]) . " generó un diccionario de usuarios "
                   . "(".$result['total_creado']." usuarios) ";
        $eventoLog .= "desde la IP " . $ipCliente . ", "; 
        $eventoLog .= "navegador " . $navegador . "($so)" . PHP_EOL;               
        $objArchivo->agregar("logs/successEvents.log", $eventoLog);

        $datos = array("accion"     => "Generó un diccionario de usuarios "
                        . "(".$result['total_creado']." usuarios)",
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
}
require_once "application/views/adicionales/resultadosDeDicccionarioDeUsuariosView.phtml";
