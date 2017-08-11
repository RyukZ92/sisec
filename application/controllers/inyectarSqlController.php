<?php
/**
 * =============================================================================
 * @Descripcion: Controlador para Inyectar SQL a sistemas web en el inicio de sesión
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.2
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * =============================================================================
 */
$configSistemaEnEvaluacion = parse_ini_file("config/config.basica-del-sistema-en-evaluacion.ini", true);
if(empty($_SESSION["resultados_de_prueba"])) {
require "application/models/InyeccionSQLModel.php";
require_once "librarys/Helper/Helper.php";
$configSQL = parse_ini_file("config/config.tests/config.inyeccion-sql.ini", true);

    $url = $configSQL["url"];
    $totalCampos = count($configSQL["campo_adicional"]) + count($configSQL["campo"]);
    $campo = $configSQL["campo"]; 

    if (empty($_SESSION["nombre_archivo"])) {
        $archivo = "files/injections-sql/inyeccion-sql-por-defecto.txt";
    } else {
        $archivo = $configSQL["archivo"];
    }

    $camposAdicionales = $configSQL["campo_adicional"];

    foreach($camposAdicionales as $llave => $valor){
       $campoAdicional .= $llave.'='.$valor . '&';            
    } 
    $cadenaInvalida = $configSQL["cadena_invalida"];

    $objInyeccion = new InyeccionSQL($url, $totalCampos, $campo, substr($campoAdicional,0 , -1), 
                                     $archivo, $cadenaInvalida, $_SESSION["usuario"], $_SESSION["nivel_usuario"]);
    $result = $objInyeccion->inyectarSQL();    
    $_SESSION["resultados_de_prueba"] = $objInyeccion->mostrar();
    $_SESSION["ruta_de_archivo"] = $result["ruta_de_archivo"];
        if($result["result_prueba"]) {
            $objCrud = new Crud();

            $_ciclo = $objCrud->consultar("ciclo_evaluacion", array("id"), "estatus = '1'");                

            $datos = array(
                "tipo"                  =>  "Inyección SQL",
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
                    "id_prueba"             =>    $prueba);
                $guardar = $objCrud->guardar("resultado_seguridad", $datos);
                $ipCliente = $_SERVER["REMOTE_ADDR"];
                $info = Helper::getBrowser();
                $navegador = $info["browser"] . " " . number_format($info["version"], 1, '.', '');
                $so = $info["os"];
                $datos = array( "accion"     => "Realizó una prueba de inyeccion sql",
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
require "application/views/inyeccionSql/resultadosDeInyeccionSqlView.phtml";