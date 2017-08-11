<?php
/**
 * @Descripcion: Modelo de las Inyecciones SQL
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: Libre uso GNU-GPL 
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */
set_time_limit(2400);
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
require_once "application/models/crud/CrudModel.php";
require_once "librarys/Snoopy-2.0.0/Snoopy.class.php";
require_once "librarys/Stoper/class.stoper.php";
require_once "librarys/Helper/Helper.php";
class InyeccionSQL
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
    private $resultado;
    /**
     * 
     * @param type $url Es la dirección donde se realizará la prueba
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
        $this->resultado = array();

        if(!function_exists('curl_init')
           ||!function_exists('curl_exec')) {
            die('Este servidor no dispone de Curl.<br>'             
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
     * Método que hace efecto de inyectar códigos sql
     * @return array
     */
    public function inyectarSQL() 
    {
        $ObjStoper = new Stoper();
        $ObjStoper->Start();
        
        $campos = $this->campo;


        $objArchivo = new GestorDeArchivos();
        $inyecciones = $objArchivo->leer($this->archivo);        
        
        if(empty($inyecciones)
            ||!is_array($inyecciones)) {
            die('El archivo no existe y/o no es valido'); 
            }

        $completar = 0;
        $n = 1;
        for($i=0; $i<count($inyecciones); $i++) {
            
            $cadena = '';
            if(!empty($inyecciones[$i])) {
                
                foreach ($campos as $llave => $valor) {
                    if($n % 2) {
                        $cadena .= $llave . "=" . trim($inyecciones[$i])."&";
                    } else {
                        $cadena .= $llave . "=" . $inyecciones[$i]."&";
                    }
                    if (count($campos) > 1 ) {
                        $i++;
                    }
                    $n++;
                } 
            } else {
                $completar = 1;
            }
            if(empty($this->campoAdicional) && $completar = 1) {
                $this->parametros[] = $cadena;
            } else {
                if ($completar = 1) {
                    $this->parametros[] = $cadena.$this->campoAdicional;
                }
            }
        }
        
        $contenido = "DETALLES DE LA PRUEBA DE INYECCIÓN SQL: INICIO DE SESIÓN" . PHP_EOL.PHP_EOL.PHP_EOL;
        $contenido .= "Fecha y Hora de Creación: " . date("d/m/Y")
                . " " . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "Usuario: " . strtoupper($this->usuario) . " (" . $this->tipoDeUsuario . ")" . PHP_EOL.PHP_EOL;

        $contenido .= "URL Destino del Formulario: " . $this->url . PHP_EOL;
        $contenido .= "Mensaje de Respuesta Invalido: " . $this->cadenaInvalida . PHP_EOL.PHP_EOL;
        $contenido .= "INTENTOS: " . PHP_EOL.PHP_EOL;
        $intento = 0;
        $exitoso = 0;
        $fallido = 0;        
        foreach ($this->parametros as $valor) {
            
            $snoopy = new Snoopy();
            $snoopy->submit($this->url);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $valor);
            $resultado = curl_exec($this->curl);
            $params = explode("&", $valor);
            $intento++;
            echo htmlspecialchars($resultado)."<BR><BR><BR>";
            if(!strstr($resultado, Helper::limpiarCampo($this->cadenaInvalida))
               and !strstr($resultado, "Fatal error:" 
               )                       
               ) {               
                $exitoso++;
                $contenido .= "$intento => Exitoso: ";
                $nros_exitosos .= $intento. ",";
                $this->nrosIntentosExitosos[] = $intento;
                $paramsExitosos = '';
                $c = 1;
                foreach ($params as $cad) {                   
                    
                    $paramsExitosos .= "[$cad] <br>";
                    $contenido .= trim("[$cad] ");                    
                    $c++;
                }
                //$contenido = substr($contenido, 0, -2);
                $this->parametrosExitosos[] = $paramsExitosos;               
                $contenido .= PHP_EOL.PHP_EOL;
                
            } else {
                
                $fallido++;
                $contenido .= "$intento => Fallido: ";
                foreach ($params as $cad) {
                    $contenido .= trim("[$cad] ");
                }
                //$contenido = substr($contenido, 0, -2);
                $contenido .= PHP_EOL.PHP_EOL;
               
            }
            
            #$this->parametrosExitosos[] = $paramsExitosos;            
        }
        $this->exitoso = $exitoso;
        $this->fallido = $fallido;
        $this->totalIntentos = $this->exitoso + $this->fallido;
        $ObjStoper->Stop();
        if($this->exitoso > 0) {
            $resultadoTexto = "Se encontró vulnerabilidades mediante la prueba. "
                    . "Aseguruse de incorporar filtracciones de entrada del lado del servidor "
                    . "para eviar este tipo de ataques sensibles a los datos que maneja el sistema evaluado.";
        } else {
            $resultadoTexto = "No se encontró vunernerabilidades en la prueba, pero se le recomienda "
                    . "estar al actualizado con las nuevas tecnológicas para manetener la integridad del software.";
        }
        $contenido .= PHP_EOL.PHP_EOL;
        $contenido .= "TOTAL DE INTENTOS: " . $this->totalIntentos  . PHP_EOL;
        $contenido .= "INTENTOS EXITOSOS: " . $this->exitoso . " (" . substr($nros_exitosos, 0, -1) .")". PHP_EOL;
        $contenido .= "INTENTOS FALLIDOS: " . $this->fallido . PHP_EOL.PHP_EOL;
        
        $contenido .= "Tiempo de Ejecución: " . $ObjStoper->showResult() . " segundos";
        $contenido .= PHP_EOL.PHP_EOL;
        $contenido .= "RESULTADOS: " . $resultadoTexto;
        $rutaDeArchivo = "files/tests-results/sql/prueba-sql_fecha_" 
                        . date("d-m-Y") . "_hora_" . date("H-i-s") . ".log";
        #$_SESSION["inyeccion_sql"]["ruta_de_archivo"] = $rutaDeArchivo;       
        
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
       $this->resultado["resultado"] = $resultadoTexto;
       return $this->resultado;
    }
    /**
     * Método que muestra el resultado de la inyección sql
     * @return string (cadena te texto)
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
            $nrosIntentos = $this->nrosIntentosExitosos;
            
            $params = explode("&", $valor);
   
            $salida .= "<tr class='oculto' style='display:none;'>"
                        . "<td>"
                        . "$k"                        
                        . "</td>"
                        . "<td>"
                        . $nrosIntentos[$i]
                        . "</td>"
                        . "<td>"
                        . $valor
                        . "</td>";                  
            $i++;$k++;
        }
        $salida .= "<tr>"
                . "     <th>Resultado</th>"
                . "     <td colspan='2'>".$this->resultado["resultado"]."</td>"
                . "</tr>";
        $salida .='<tr>
                <th colspan="3" class="active">
                    <a href="#" class="fa fa-lock" id="btn-bloqueo"> Mostrar Sentencias con Éxito</a>
                </th>
            </tr>';
        return $salida;
       // return $salida;
    }    
}
