<?php
/**
 * =================================================
 * @Descripcion: Modelo de Ataque de Fuerza Bruta 
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: Libre uso GNU-GPL 
 * @E-Mail: miguel__salazar@hotmail.com
 * ==================================================
 * 
 */
set_time_limit(2400);
require_once "librarys/GestorDeArchivos/GestorDeArchivos.php";
require_once "librarys/Snoopy-2.0.0/Snoopy.class.php";
require_once "librarys/Stoper/class.stoper.php";
require_once "librarys/Helper/Helper.php";

class FuerzaBruta
{    
    private $url;
    private $curl;
    private $contrasenia;
    private $campo;
    private $totalCampos;
    private $campoAdicional = array();
    private $archivo;
    private $cadenaInvalida;
    private $parametros;
    private $usuario;
    private $nivelDeUsuario;
    private $exitoso;
    private $fallido;
    private $totalIntentos;
    private $parametrosExitosos = array();
    private $nrosIntentosExitosos = array();
    private $resultado;

    /**
     * 
     * @param type $url (URL donde se va a realizar la prueba)
     * @param type $totalCampos (Cantidad de campos totales que se van a enviar por petición)
     * @param type $contrasenia (nombre del campo contraseña)
     * @param type $campo (Son los campos del campo user tipo array que se van a enviar)
     * @param type $campoAdicional (Son campos adicionales como botones, campos ocultos, etc)
     * @param type $archivo (Ruta del archivo donde se va a guardar la prueba)
     * @param type $cadenaInvalida (Es un string con los datos incorrectos)
     * @param type $usuario (Nombre del usuario que realiza la prueba)
     * @param type $nivelDeUsuario (Nivel del usaurio que realiza prueba)
     */
    public function __construct($url, $totalCampos, $contrasenia, $campo, $campoAdicional, 
                                $archivo, $cadenaInvalida, $usuario, $nivelDeUsuario)
    {
        $this->url = $url;
        $this->totalCampos = $totalCampos;
        $this->contrasenia = $contrasenia;
        $this->campo = $campo;
        $this->campoAdicional = $campoAdicional;
        $this->archivo  = $archivo;
        $this->cadenaInvalida = $cadenaInvalida;
        $this->parametros = array();
        $this->usuario = $usuario;
        $this->nivelDeUsuario = $nivelDeUsuario;
        $this->resultado = array();

        if(!function_exists('curl_init')
           ||!function_exists('curl_exec')) {
            die('Este servidor no dispone de Curl.<br>'             
                . 'Comando de instalación: <BR><b>apt-get install php5-curl</b>');            
        }        
        $this->curl = curl_init();
        /*
        // Indicamos que debe devolver el resultado
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        // Se indica la url destino.
        curl_setopt($this->curl,CURLOPT_URL, $this->url);
        // Se indica que va a ser un POST y su longitud.
        curl_setopt($this->curl,CURLOPT_POST, $this->totalCampos);
        // Se indica que siga las redirecciones
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        // Se simula ser un navegador Firefox.
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0');              */
    }
    /**
     * 
     */
    public function GenerarAtaque() 
    {
        $ObjStoper = new Stoper();
        $ObjStoper->Start();
        

        $objSnoopy = new Snoopy();

        $objSnoopy->maxredirs = 1;
        
        //$objSnoopy->setcookies();
        //$datos = $this->datosDeCarga;
        
        $camposSubmit = $this->campo;


        $objArchivo = new GestorDeArchivos();
        $contrasenias = $objArchivo->leer($this->archivo);        
        
        if(empty($contrasenias)
            ||!is_array($contrasenias)) {
            die('El archivo no existe y/o no es valido'); 
            }

        
        $contenido = "DETALLES DE LA PRUEBA DE ATAQUE DE FUERZA BRUTA: INICIO DE SESIÓN" . PHP_EOL.PHP_EOL.PHP_EOL;
        $contenido .= "Fecha y Hora de Creación: " . date("d/m/Y")
                . " " . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "Usuario: " . strtoupper($this->usuario) . " (" . $this->nivelDeUsuario . ")" . PHP_EOL.PHP_EOL;

        $contenido .= "URL Destino del Formulario: " . $this->url . PHP_EOL;
        $contenido .= "Mensaje de Exito: " . $this->cadenaInvalida . PHP_EOL.PHP_EOL;
        $contenido .= "INTENTOS: " . PHP_EOL.PHP_EOL;
        $intento = 0;
        $exitoso = 0;
        $fallido = 0;
        for($i=0; $i<count($contrasenias); $i++) {
            
            $cadena = '';
            if(!empty($contrasenias[$i])) {
                $camposSubmit[$this->contrasenia] = trim($contrasenias[$i]);                


                foreach($this->campoAdicional as $llave => $valor){
                   $camposSubmit[$llave] = $valor;       
                }
               

                $objSnoopy->submit($this->url, $camposSubmit);
                #print_r($camposSubmit);print"<br>";
                $resultado = $objSnoopy->results;
                $intento++;
                $this->resultado["resultado_grafico"][] = $resultado;
                if(!strstr($resultado, $this->cadenaInvalida)) {
                    //echo $resultado;
                    $exitoso++;
                    $contenido .= "$intento => Exitoso: ";
                    $nros_exitosos .= $intento. ",";
                    $this->nrosIntentosExitosos[] = $intento;
                    $paramsExitosos = '';
                    $c = 1;
                    foreach ($camposSubmit as $indice => $valor) {                  

                        $paramsExitosos .= "CAMPO[$indice] = $valor<br>";
                        $contenido .= "\"CAMPO[$indice] = $valor\", ";                    
                        $c++;
                    }
                    $contenido = substr($contenido, 0, -2);
                    $this->parametrosExitosos[] = $paramsExitosos;               
                    $contenido .= PHP_EOL.PHP_EOL;

                    break;
                } else {
                    //echo $resultado;
                    $fallido++;
                    $contenido .= "$intento => Fallido: ";
                    foreach ($camposSubmit as $indice => $valor) {
                        $contenido .= "\"CAMPO[$indice] = $valor\", ";
                    }
                    $contenido = substr($contenido, 0, -2);
                    $contenido .= PHP_EOL.PHP_EOL;
                }
            }
            
        }
        $this->exitoso = $exitoso;
        $this->fallido = $fallido;
        $this->totalIntentos = $this->exitoso + $this->fallido;
       if ($this->exitoso > 0) {
           $resultadoTexto = "Se deben implementar restricciones o politicas de "
                . "seguridad en cuanto a los ataques de fuerza bruta. "
                . "Bloquear temporalmente más de 5 intentos (como máximo) de una misma Dirección IP, "
                . "así como también incorporar códigos captchas para verificar que no sea un robot el que intenta acceder al software.";
       } else {
           $resultadoTexto = "En la prueba de fuerza bruta no se hallaron conindencia pero se le aconseja incorporar protección contra robots, "
                   . "es decir, inluir código de verificación de humanos (captchas) para eviar simultaneas peticiones en el inicio de sesión";
       }
        
        $ObjStoper->Stop();
        $contenido .= PHP_EOL.PHP_EOL;
        $contenido .= "TOTAL DE INTENTOS: " . $this->totalIntentos  . PHP_EOL;
        $contenido .= "INTENTOS EXITOSOS: " . $this->exitoso . " (" . substr($nros_exitosos, 0, -1) .")". PHP_EOL;
        $contenido .= "INTENTOS FALLIDOS: " . $this->fallido . PHP_EOL.PHP_EOL;
        
        $contenido .= "Tiempo de Ejecución: " . $ObjStoper->showResult() . " segundos";
        $contenido .= PHP_EOL.PHP_EOL;
        $rutaDeArchivo = "files/tests-results/brute-force/prueba-fuerza-bruta_fecha_" 
                        . date("d-m-Y") . "_hora_" . date("H-i-s") . ".log";
        //$_SESSION["fuerza_bruta"]["ruta_de_archivo"] = $rutaDeArchivo;       
        $contenido .= "RESULTADO: " . $resultadoTexto;
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
     * Métod que muestra el resultado de prueba. Requiere ejeceutar el método previo.
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
