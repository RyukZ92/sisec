<?php
/**
 * =================================================
 * @Descripcion: Modelo de las Pruebas de Cargas
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Marzo, 2016
 * @Version: 1.0
 * @Licencia: Libre uso GNU-GPL 
 * @E-Mail: miguelangel.bux@gmail.com
 * ==================================================
 * 
 */

set_time_limit(900);
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
require_once "librarys/Snoopy-2.0.0/Snoopy.class.php";
require_once "librarys/Stoper/class.stoper.php";
require_once "librarys/Helper/Helper.php";

class CargaDeDatos
{    
    private $url;
    private $datos;
    private $datosDeCarga;
    private $mensajeDeError;
    private $mensajeDeExito;
    private $urlDatos;
    private $resultado;
    private $cantidad;
    private $tiempoOptimo;
    private $tiempoIntermedio;
    private $tiempoMinimo;

    
    public function __construct() {
        $this->resultado = array();
        $this->tiempoOptimo = 1;
        $this->tiempoIntermedio = 2;
        $this->tiempoMinimo = 3;
    }

        public function setDatosDeSesion($url, $datos, $mensajeDeError) 
    {
        $this->url = $url;
        $this->datos = $datos;     
        $this->mensajeDeError = $mensajeDeError;
    }
    /**
     * Método para cargar las variables o parametros requeridas para la pruebas
     * @param type $urlDatos (URL donde se van a enviar los datos)
     * @param type $datosDeCarga (array con los datos que se van a enviar)
     * @param type $campoAdicional (Campos adicionles, ejemplo, botones de envío, campos ocultos)
     * @param type $mensajeDeExito (Mensaje de exito que arroja el sistema para validar el envío de datos)
     * @param type $usuario (Nombre del usuario quien realiza la prueba)
     * @param type $nivelDeUsuario (Nivel o tipo de usuario que realiza la prueba)
     * @param type $cantidad (Total de cargas que se desean realizar
     * @param type $optimo (El tiempo más corto medible qyue se desea superar)
     * @param type $intermedio (El tiempo intermedio que se desea superar)
     * @param type $minimo (El tiempo más bajo  que se desea superar)
     */
    public function setDatosDeCarga($urlDatos, $datosDeCarga, $campoAdicional, 
                                    $mensajeDeExito, $usuario, $nivelDeUsuario,
                                    $cantidad, $optimo, $intermedio, $minimo)
    {
        $this->urlDatos = $urlDatos;
        $this->datosDeCarga = $datosDeCarga;
        $this->campoAdicional = $campoAdicional;
        $this->mensajeDeExito = $mensajeDeExito;
        $this->cantidad = $cantidad;
        $this->tiempoOptimo = (empty($optimo)) ? $this->tiempoOptimo : $optimo;
        $this->tiempoIntermedio = (empty($intermedio)) ? $this->tiempoIntermedio : $intermedio;
        $this->tiempoMinimo = (empty($minimo)) ? $this->tiempoMinimo : $minimo;        
        $this->usuario = $usuario;
        $this->nivelDeUsuario = $nivelDeUsuario;
        
    }
    /**
     * Método que realiza el efecto de la carga de datos
     * @return type array
     */
    public function CargarDatos()
    {   

        
        $ObjStoper = new Stoper();
        $ObjStoper->Start(); 
        
        $cargasExitosas = 0;
        $totalIntentos = 0;
       
        $objSnoopy = new Snoopy();
        $objSnoopy->maxredirs = 2;
        $objSnoopy->submit($this->url, $this->datos);
        $objSnoopy->setcookies();
        $resultadoSesion = $objSnoopy->results;
        while($cargasExitosas < $this->cantidad) {           
            $datos = $this->datosDeCarga;
            for($i=0; $i<count($datos); $i++) {
                $indice = $datos[$i]["nombre"];
                $long = $datos[$i]["longitud"];
                
                if ($datos[$i]["tipo"] == "Letras/Números") {
                    $datosDeCarga[$indice] = Helper::generarCaracteresAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Letras") {
                    $datosDeCarga[$indice] = Helper::generarLetrasAleatorias($long);
                }
                if ($datos[$i]["tipo"] == "Números") {
                    $datosDeCarga[$indice] = Helper::generarNumerosAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Cualquier Carácter") {
                    $datosDeCarga[$indice] = Helper::generarCaracteresAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Fecha") {
                    $datosDeCarga[$indice] = Helper::generarFechaAleatoria();
                }   
                if ($datos[$i]["tipo"] == "Hora") {
                    $datosDeCarga[$indice] = Helper::generarHoraAleatoria();
                }                
            }
                foreach($this->campoAdicional as $llave => $valor){
                   $datosDeCarga[$llave] = $valor;
                   $i++;
                }                
                
                if (!strstr($resultadoSesion, $this->mensajeDeError)) {
                    $objSnoopy->submit($this->urlDatos, $datosDeCarga);
                    $resultadoCarga = $objSnoopy->results;
                    if (strstr($resultadoCarga, $this->mensajeDeExito)) {                        
                        $cargasExitosas++;
                    } else {                        
                        die("<br><center><h4>ERROR: Configuración erronea.</h4></center>");                        
                    }
                } else {
                    die("<BR><center><h4>ERROR: Datos de sesión invalidos.</h4></center>");
                }
            $totalIntentos++;
        }
        $cargasFallidas = $totalIntentos - $cargasExitosas;
        $ObjStoper->Stop();
        $objArchivo = new GestorDeArchivos();        
        
        $tiempoDeLaPrueba = $_tiempoDeEjecucion = $tiempoDeEjecucion = $ObjStoper->showResult();
        
        $tiempo = Helper::conversorDeSegundosAHora($tiempoDeEjecucion);
        
        if ($tiempo["horas"] > 0) {
            $tiempoDeEjecucion = $tiempo["horas"] 
                                . " hora con " . $tiempo["minutos"] 
                                . " minutos y " . $tiempo["segundos"]
                                . " segundos";
        } else if ($tiempo["minutos"] > 0 
                   && $tiempo["horas"] == 0) {
            if ($tiempo["minutos"] > 1) $m = "s";
            if ($tiempo["segundos"] != 1) $s = "s";
            $tiempoDeEjecucion = $tiempo["minutos"] 
                                . " minuto$m con " . $tiempo["segundos"]
                                . " segundo$s";
        } else {
            if ($tiempo["segundos"] != 1) $s = "s";
            $tiempoDeEjecucion = $tiempo["segundos"]
                                . " segundo$s";
        }
        if ($_tiempoDeEjecucion <= ($this->tiempoOptimo * $this->cantidad)) {
            $resultado = "\"Rendimiento Óptimo\".<br>"
                    . "<strong>¡Enhorabuena!</strong>, el software ha alcanzado y/o sobrepasado los tiempos de medición óptimos "
                    . "por lo tanto puede considerarse como un software de alto rendimiento en carga de datos.";
            
            $resultadoT = "\"Rendimiento Óptimo\".\n\r"
                    . "¡Enhorabuena!, el software ha alcanzado y/o sobrepasado los tiempos de medición óptimos "
                    . "por lo tanto puede considerarse como un software de alto rendimiento en carga de datos.";
        } else if ($_tiempoDeEjecucion > ($this->tiempoOptimo * $this->cantidad) 
                   && $_tiempoDeEjecucion <= ($this->tiempoIntermedio * $this->cantidad)
          ) {
            $resultado = "\"Rendimiento Intermedio\".<br>"
                        . "<strong>¡Muy bien!</strong>, de acuerdo a los tiempos definidos, "
                    . "el software tiene un rendimiento en carga de datos bueno, "
                    . " es decir, no es de tan alto, pero tampoco bajo"
                    . " y se considera en un rendimiento normal y bastante aceptable.";
            
            $resultadoT = "\"Rendimiento Intermedio\".\r\n"
                        . "¡Muy bien!, de acuerdo a los tiempos definidos, "
                    . "el software tiene un rendimiento en carga de datos bueno, "
                    . " es decir, no es de tan alto, pero tampoco bajo"
                    . " y se considera en un rendimiento normal y bastante aceptable.";
        } else if ($_tiempoDeEjecucion > ($this->tiempoIntermedio * $this->cantidad)
                   && $_tiempoDeEjecucion <= ($this->tiempoMinimo * $this->cantidad)) {
            $resultado = "\"Rendimiento Mínimo Aceptable\".<br>"
                    . "<strong>¡Bien!</strong>, por suerte el software cumple con los tiempos de medición mínimos establecidos "
                    . " para la carga de datos, lo cual se recomienda revisar la condificación y programación de este para "
                    . "obtener mayores resultados. Esto es muy importante para el futuro del software.";
            
            $resultadoT = "\"Rendimiento Mínimo Aceptable\".\r\n"
                    . "¡Bien!, por suerte el software cumple con los tiempos de medición mínimos establecidos "
                    . " para la carga de datos, lo cual se recomienda revisar la condificación y programación de este para "
                    . "obtener mayores resultados. Esto es muy importante para el futuro del software.";
        } else {
            $resultado = "<strong>\"Rendimiento Muy Bajo\"</strong> (NO CUMPLE CON LOS TIEMPOS ESTABLECIDOS).<br>" 
                        . "<strong class='text-danger'>ATENCIÓN </strong>: Este resultado es de acuerdo a los tiempos establecidos, "
                        . "si usted considera que ha habido un error, entonces se le recomienda repitir "
                        . "la prueba en otro ciclo de evaluación. "
                        . "Pero si sigue teniendo el mismo resultado con los tiempos establecidos actualmente, "
                        . "el problema está en la condificación y programación del software evaluado.";
            
            $resultadoT = "\"Rendimiento Muy Bajo\" (NO CUMPLE CON LOS TIEMPOS ESTABLECIDOS).\r\n" 
                        . "ATENCIÓN: Este resultado es de acuerdo a los tiempos establecidos, "
                        . "si usted considera que ha habido un error, entonces se le recomienda repitir "
                        . "la prueba en otro ciclo de evaluación. "
                        . "Pero si sigue teniendo el mismo resultado con los tiempos establecidos actualmente, "
                        . "el problema está en la condificación y programación del software evaluado.";
        }
        
        $contenido = "DETALLES DE LA PRUEBA DE CARGA DE DATOS" . PHP_EOL.PHP_EOL.PHP_EOL;
        $contenido .= "Fecha y Hora de Creación: " . date("d-m-Y")
                . " " . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "Usuario: " . strtoupper($this->usuario) . " (" . $this->nivelDeUsuario . ")" . PHP_EOL.PHP_EOL;

        $contenido .= "URL de la carga de datos: " . $this->urlDatos . PHP_EOL;
        
        if($this->tiempoOptimo == 1)
            $s = "";
        else
            $s = "s";
        $contenido .= "Cargas totales: ". $totalIntentos . PHP_EOL;
        $contenido .= "Cargas exitosas: ". $cargasExitosas . PHP_EOL;
        $contenido .= "Cargas fallidas: ". $cargasFallidas . PHP_EOL.PHP_EOL;
        $contenido .= "Tiempos Referenciales para la Medición (por cada carga): " . PHP_EOL . PHP_EOL;
        $contenido .= "Tiempo Óptimo: ". $this->tiempoOptimo . " segundo$s." . PHP_EOL;
        $contenido .= "Tiempo Intermedio: ". $this->tiempoIntermedio . " segundos." . PHP_EOL;
        $contenido .= "Tiempo Mínimo: ". $this->tiempoMinimo . " segundos." . PHP_EOL . PHP_EOL;
        $contenido .= "Tiempo de Ejecución: " . $tiempoDeEjecucion . "." . PHP_EOL . PHP_EOL;
        $contenido .= PHP_EOL;       
        $contenido .= "RESULTADO: " . $resultadoT . PHP_EOL . PHP_EOL;
        $contenido .= "NOTA: El resultado no es absoluto, porque depente del ambiente donde se ejecuta la prueba"
                . PHP_EOL . "puesto que éste debe estar previamente establecidos por el evaluador. "
                . PHP_EOL . "Por ejemplo: La velocidad de subida del servidor y velocidad de baja del cliente." . PHP_EOL;
        $rutaDeArchivo = "files/tests-results/data-load/" . date("d-m-Y") . "_" . date("H-i-s") 
                        . "_prueba-de-carga.log";
        $this->resultado["fecha"] = date("Y-m-d");
        $this->resultado["hora"] = date("H:i:s");
        $this->resultado["intentos_exitosos"] = $cargasExitosas;
        $this->resultado["intentos_totales"] = $totalIntentos;
        $this->resultado["intentos_fallidos"] = $cargasFallidas;
        $this->resultado["tiempo_optimo"] = $this->tiempoOptimo;
        $this->resultado["tiempo_intermedio"] = $this->tiempoIntermedio;
        $this->resultado["tiempo_minimo"] = $this->tiempoMinimo;
        $this->resultado["tiempo_de_ejecucion"] = $tiempoDeEjecucion;
        $this->resultado["tiempo_de_la_prueba"] = $tiempoDeLaPrueba;
        $this->resultado["resultado"] = $resultado;
        $this->resultado["ruta_de_archivo"] = URLBASE . $rutaDeArchivo;
        
        $result = $objArchivo->escrbir($rutaDeArchivo, $contenido);
        if ($result) {            
            $this->resultado["result_prueba"] = 1;             
        } else {
            $this->resultado["result_prueba"] = 0;
        }
        return $this->resultado;
    }
    /**
     * Método para mostrar resultados la la prueba. Requeriere el llamo previo de la función anterior
     * @return string
     */
    public function mostrar()
    {
        $salida ='
            <table class="table table-striped">          
                    <tr>
                        <th>Cargas Totales</th>
                        <td>'.$this->resultado["intentos_totales"].'</td>
                    </tr>
                    <tr>
                        <th>Cargas Exitosas</th>
                        <td>'.$this->resultado["intentos_exitosos"].'</td>
                    </tr>
                    <tr>
                        <th>Cargas Fallidas</th>
                        <td>'.$this->resultado["intentos_fallidos"].'</td>
                    </tr>';
        $salida .= "    <tr>
                            <th>Tiempo de Ejecución</th>
                            <td>".$this->resultado["tiempo_de_ejecucion"]."</td>
                        </tr>
                        <tr>
                            <th>Resultado</th>
                            <td>".$this->resultado["resultado"]."</td>
                        </tr>";
        $salida .= "
                
            </table>";
        return $salida;
    }
    
}

