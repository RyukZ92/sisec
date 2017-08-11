<?php
/**
 * @Descripcion: Modelo de las Inyecciones SQL
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abr, 2016
 * @Version: 0.1 Beta
 * @Licencia: Libre uso GNU-GPL 
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */
set_time_limit(2400);
require_once "application/models/crud/CrudModel.php";
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
require_once "librarys/Stoper/class.stoper.php";
require_once "librarys/Helper/Helper.php";

class InyeccionXSS
{    
    private $url;
    private $curl;
    private $campo;
    private $totalCampos;
    private $campoAdicional = array();
    private $archivo;
    private $cadenaInvalida;
    private $parametros;
    private $usuario;
    private $tipoDeUsuario;
    private $exitoso;
    private $fallido;
    private $totalIntentos;
    private $parametrosExitosos = array();
    private $nrosIntentosExitosos = array();
    
    /**
     * 
     * @param type $url
     * @param type $totalCampos
     * @param type $campo
     * @param type $campoAdicional
     * @param type $archivo
     * @param type $cadenaInvalida
     * @param type $usuario
     * @param type $tipoDeUsuario
     */
    public function __construct($url, $totalCampos, $campo, $campoAdicional, 
                                $archivo, $cadenaInvalida, $usuario, $tipoDeUsuario)
    {
        $this->url = $url;
        $this->totalCampos = $totalCampos;
        $this->campo = $campo;
        $this->campoAdicional = $campoAdicional;
        $this->archivo  = $archivo;
        $this->cadenaInvalida = $cadenaInvalida;
        $this->parametros = array();
        $this->usuario = $usuario;
        $this->tipoDeUsuario = $tipoDeUsuario;

        if(!function_exists('curl_init')
           ||!function_exists('curl_exec')) {
            die('Este servidor no dispone de Curl.</br>'             
                . 'Comando de instalación: <BR><b>apt-get install php5-curl</b>');            
        }        
        $this->curl = curl_init();
        // Indicamos que debe devolver el resultado
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        // Se indica la url destino.
        curl_setopt($this->curl,CURLOPT_URL, $this->url);
        // Se indica que va a ser un POST y su longitud.
        curl_setopt($this->curl,CURLOPT_POST, $this->totalCampos);
        // Se indica que siga las redirecciones
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        // Se simula ser un navegador Firefox.
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0');              
    }
    /**
     * 
     */
    public function inyectarXSS() 
    {
        $ObjStoper = new Stoper();
        $ObjStoper->Start();
        
        $campos = $this->campo;

        $objArchivo = new GestorDeArchivos();
        $inyecciones = $objArchivo->leer($this->archivo);        
        
        if(empty($inyecciones)
            || !is_array($inyecciones)) {
            die('El archivo no existe y/o no es valido'); 
            }

        $completar = 0;
        for($i=0; $i<count($inyecciones); $i++) {            
            $cadena = '';
            if(!empty($inyecciones[$i])) {
                foreach ($campos as $llave => $valor) {
                    $cadena .= $llave . "=" . $inyecciones[$i]."&";
                    $i++;
                } 
            } else {
                $completar = 1;
            }
            if(empty($this->campoAdicional) && $completar = 1) {
                $this->parametros[] = $cadena;
            } else {
                if ($completar = 1) {
                    $this->parametros[] = $cadena . $this->campoAdicional;
                }
            }
        }
        
        $exitoso = 0;
        $fallido = 0;
        $contenido = "DETALLES DE LA PRUEBA DE INYECCIÓN XSS." . PHP_EOL.PHP_EOL.PHP_EOL;
        $contenido .= "Fecha y Hora de Creación: " . date("d/m/Y")
                . " " . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "Usuario: " . strtoupper($this->usuario) . " (" . $this->tipoDeUsuario . ")" . PHP_EOL.PHP_EOL;

        $contenido .= "URL Destino del Formulario: " . $this->url . PHP_EOL;
        $contenido .= "Mensaje de Respuesta Invalido: " . $this->cadenaInvalida . PHP_EOL.PHP_EOL;
        $contenido .= "INTENTOS: " . PHP_EOL.PHP_EOL;
        $intento = 0;
        foreach ($this->parametros as $valor) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $valor);
            
            $resultado = curl_exec($this->curl);
            $params = explode("&", $valor);
            $intento++;
            if(!strstr($resultado, $this->cadenaInvalida)) {
                $exitoso++;
                $contenido .= "$intento => Exitoso: ";
                $nros_exitosos .= $intento. ",";
                $this->nrosIntentosExitosos[] = $intento;
                $paramsExitosos = '';
                $c = 1;
                foreach ($params as $cad) {                   
                    
                    $paramsExitosos .= "[$cad] </br>";
                    $contenido .= "[$cad] ";                    
                    $c++;
                }                
                $this->parametrosExitosos[] = $paramsExitosos;               
                $contenido .= PHP_EOL.PHP_EOL;
                
            } else {
                
                $fallido++;
                $contenido .= "$intento => Fallido: ";
                foreach ($params as $cad) {
                    $contenido .= "[$cad] ";
                }
                $contenido .= PHP_EOL.PHP_EOL;               
            }            
                       
        }
        $this->exitoso = $exitoso;
        $this->fallido = $fallido;
        $this->totalIntentos = $this->exitoso + $this->fallido;
        $ObjStoper->Stop();
        
        $contenido .= PHP_EOL.PHP_EOL;
        $contenido .= "TOTAL DE INTENTOS: " . $this->totalIntentos  . PHP_EOL;
        $contenido .= "INTENTOS EXITOSOS: " . $this->exitoso . " (" . substr($nros_exitosos, 0, -1) .")". PHP_EOL;
        $contenido .= "INTENTOS FALLIDOS: " . $this->fallido . PHP_EOL.PHP_EOL;
        
        $contenido .= $ObjStoper->showResult("Tiempo de Ejecución: ");
        $rutaDeArchivo = "files/tests-results/xss/prueba-xss_fecha_" 
                        . date("d-m-Y") . "_hora_" . date("H-i-s") . ".log";
        $contenido .= PHP_EOL.PHP_EOL;
        $contenido .= "RESULTADOS: " . $resultadoTexto;      
        
        $result = $objArchivo->escrbir($rutaDeArchivo, $contenido);
        if($result) {
            $this->resultado["result_prueba"] = 1;     
        } else {
            $this->resultado["result_prueba"] = 0;
        }
        
       $this->resultado["fecha"] = date("Y-m-d"); 
       $this->resultado["hora"] = date("H:i:s");
       $this->resultado["ruta_de_archivo"] = URLBASE . $rutaDeArchivo;
       $this->resultado["intentos_totales"] = $this->totalIntentos;
       $this->resultado["intentos_exitosos"] = $this->exitoso;
       $this->resultado["intentos_fallidos"] = $this->fallido;
       $this->resultado["tiempo_de_ejecucion"] = $ObjStoper->showResult() . " segundos";
       $this->resultado["resultado"] = $this->resultado["resultado"];
       
       return $this->resultado;   
    }
    /**
     * 
     * @return string
     */
    public function mostrar()
    {        
        $salida = "<tr>"
                    . "<td>"
                    . $this->totalIntentos
                    . "</td>"
                    . "<td>"
                    . $this->exitoso
                    . "</td>"
                    . "<td>"
                    . $this->fallido
                    . "</td>"
                . "</tr>";
        $salida .='<tr>
                <th colspan="3" class="active">
                    <a href="#" class="fa fa-lock" id="btn-bloqueo"> Mostrar Sentencias con Éxito</a>
                </th>
            </tr>';

        $salida .= "
                        <tr class='oculto' style='display:none;'>
                            <th >#</th>
                            <th >N° de Intento Exitoso</th>
                            <th>Sentencias con Exito</th>
                        </tr>
                    ";
        $i = 0;
        $k = 1;
        foreach ($this->parametrosExitosos as $valor) {
            $nros_intentos = $this->nrosIntentosExitosos;
            
            $params = explode("&", $valor);
   
            $salida .= "<tr class='oculto' style='display:none;'>"
                        . "<td>"
                        . "$k"                        
                        . "</td>"
                        . "<td>"
                        . $nros_intentos[$i]
                        . "</td>"
                        . "<td>"
                        . $valor
                        . "</td>";                  
            $i++;$k++;    
        }
        return $salida;
    }    
}