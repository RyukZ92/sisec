<?php
/**
 * @Descripcion: Controlador para Inyectar SQL a sistemas web en el inicio de sesión
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abril, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */
require "librarys/Helper/Helper.php";
$configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
if(empty($_SESSION["resultados_de_prueba"])) {
    require_once "application/models/crud/CrudModel.php";
    require "application/models/FuerzaBrutaModel.php";
    
    if (file_exists("config/config.tests/config.fuerza-bruta.ini")) {
        $configFuerzaBruta = parse_ini_file("config/config.tests/config.fuerza-bruta.ini", true);

        $url = $configFuerzaBruta["url"];
        $totalCampos = count($configFuerzaBruta["campo_adicional"]) + count($configFuerzaBruta["campo"]);
        $campos = $configFuerzaBruta["campo"]; 
        if (empty($_SESSION["nombre_archivo"])) {
            $archivo = "files/brute-force/fuerza-bruta-por-defecto.txt";
        } else {
            $archivo = $configFuerzaBruta["archivo"];
        }

        $contrasenia = $configFuerzaBruta["campo_contrasenia"];
        $camposAdicionales = $configFuerzaBruta["campo_adicional"];

        $cadenaInvalida = $configFuerzaBruta["cadena_invalida"];

        $objInyeccion = new FuerzaBruta($url, $totalCampos, $contrasenia, $campos, $camposAdicionales, 
                                       $archivo, $cadenaInvalida, $_SESSION["usuario"], $_SESSION["nivel_usuario"]);
        $result = $objInyeccion->GenerarAtaque();
        $_SESSION["ruta_de_archivo"] = $result["ruta_de_archivo"];
        $_SESSION["resultados_de_prueba"] = $objInyeccion->mostrar();

        if($result["result_prueba"]) {
            $objCrud = new Crud();

            $_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");                

            $datos = array(
                "tipo"                  =>  "Fuerza Bruta",
                "fecha"                 =>  $result["fecha"],
                "hora"                  =>  $result["hora"],
                "ruta_de_archivo"       =>  $result["ruta_de_archivo"],
                "id_usuario"            =>  $_SESSION["id_usuario"],
                "id_ciclo_evaluacion"   =>  $_ciclo[0]["id"]
            );
            $prueba = $objCrud->guardar("prueba", $datos, null, true);
            if($prueba) {
                $datos = array(
                    "intentos_totales"      =>    $result["intentos_totales"],
                    "intentos_exitosos"     =>    $result["intentos_exitosos"],
                    "intentos_fallidos"     =>    $result["intentos_fallidos"],
                    "tiempo_de_ejecucion"   =>    $result["tiempo_de_ejecucion"],
                    "id_prueba"             =>    $prueba
                );
                $guardar = $objCrud->guardar("resultado_seguridad", $datos);
                $ipCliente = $_SERVER["REMOTE_ADDR"];
                $info = Helper::getBrowser();
                $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
                $so = $info["os"];
                $datos = array( "accion"     => "Realizó una prueba de fuerza bruta",
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
    } else {
        $resultados = null;
        $notificacion = "<BR><br><center><h3>Debe configurar los parametros de fuerza bruta para realizar esta prueba</h3></center>";
    }
}
//print_r ($campos);
//unset($_SESSION["inyeccion_sql"]);
require "application/views/fuerzaBruta/resultadosDeFuerzaBrutaView.phtml";
