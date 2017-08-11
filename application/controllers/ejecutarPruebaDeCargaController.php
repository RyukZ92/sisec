<?php
/**
 * =============================================================================
 * @Descripcion: Controlador que activa la ejecución de la prueba de carga
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 */
$configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
$configPruebaDeCarga = parse_ini_file("config/config.tests/config.prueba-de-carga.ini", true);
require_once "application/models/CargaDeDatosModel.php";
require_once "application/models/crud/CrudModel.php";
require_once "librarys/Helper/Helper.php";
if (empty($_SESSION["resultados_de_prueba"])) {
    $urlSesion = $configSistemaEnEvaluacion["url_sesion"];
    $datos = $configSistemaEnEvaluacion["login"];    
    $mensajeDeError = $configSistemaEnEvaluacion["mensaje_de_error"];
    
    $objCarga= new CargaDeDatos();    
    $objCarga->setDatosDeSesion($urlSesion, $datos, $mensajeDeError);
    
    if($sesion) {
        $url = $configPruebaDeCarga["url"];
        $totalCampos = $configPruebaDeCarga["total_campos"];
        $mensajeDeExito = $configPruebaDeCarga["cadena_valida"];
        $usuario = $_SESSION["usuario"];
        $nivelDeUsuario = $_SESSION["nivel_usuario"];
        for($i=0; $i<$totalCampos; $i++) {
            foreach ($configPruebaDeCarga["campos_$i"] as $llave => $valor) {
                    $datosDeCarga[$i][$llave] =  $valor;
            }
        }
    }
    $cantidad = $_SESSION["cantidad"];
    $optimo = $_SESSION["optimo"];
    $intermedio = $_SESSION["intermedio"];
    $minimo = $_SESSION["minimo"];
    $camposAdicionales = $configPruebaDeCarga["campo_adicional"];
    $objCarga->setDatosDeCarga($url, $datosDeCarga, $camposAdicionales, 
                                $mensajeDeExito, $usuario, $nivelDeUsuario,
                                $cantidad, $optimo, $intermedio, $minimo);  

    $result = $objCarga->CargarDatos();
    $_SESSION["resultados_de_prueba"] = $objCarga->mostrar();
    $_SESSION["ruta_de_archivo"] = $result["ruta_de_archivo"];
        if($result["result_prueba"]) {
            $objCrud = new Crud();

            $_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");                
            
            $datos = array(
                "tipo"                  =>  "Carga de Datos",
                "fecha"                 =>  $result["fecha"],
                "hora"                  =>  $result["hora"],
                "ruta_de_archivo"       =>  $result["ruta_de_archivo"],
                "id_usuario"            =>  $_SESSION["id_usuario"],
                "id_ciclo_evaluacion"   =>  $_ciclo[0]["id"]
            );
            $prueba = $objCrud->guardar("prueba", $datos, null, true);
            
            if($prueba) {
                $datos = array(
                    "cargas_totales"      =>    $result["intentos_totales"],
                    "cargas_exitosas"     =>    $result["intentos_exitosos"],
                    "cargas_fallidas"     =>    $result["intentos_fallidos"],
                    "tiempo_de_ejecucion" =>    $result["tiempo_de_ejecucion"],
                    "tiempo_optimo"       =>    $result["tiempo_optimo"],
                    "tiempo_intermedio"   =>    $result["tiempo_intermedio"],
                    "tiempo_minimo"       =>    $result["tiempo_minimo"],
                    "tiempo_de_la_prueba" =>    $result["tiempo_de_la_prueba"],
                    "resultado"           =>    $result["resultado"],
                    "ruta_de_archivo"     =>    $result["ruta_de_archivo"],                    
                    "id_prueba"           =>    $prueba
                );
                $guardar = $objCrud->guardar("resultado_carga_de_datos", $datos);
                $ipCliente = $_SERVER["REMOTE_ADDR"];
                $info = Helper::getBrowser();
                $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
                $so = $info["os"];
                $datos = array( "accion"     => "Realizó una prueba de carga de datos",
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
}
unset($_SESSION["tiempos_personalizados"]);
require "application/views/pruebaDeCarga/resultadosDePruebaDeCargaView.phtml";
