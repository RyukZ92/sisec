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

class CargaDeSesion
{    
    private $urlDeSesion;
    private $urlDeInicio;
    private $urlDeSalir;
    private $campoUsuario;
    private $campoContrasenia;
    private $mensajeDeError;
    private $resultado;
    private $campoAdicional;
    private $archivo;
    private $usuario;
    private $nivelDeUsuario;

   
    public function __construct($url, $datos, $mensajeDeError) 
    {
        $this->resultado = array();
    }
    /**
     * 
     * @param type $url (es la url de inicio de sesión
     * @param type $campoUsuario (es el nombre del campo  del html donde se ingresará el usuario)
     * @param type $campoContrasenia (es el nombre del campo del html contraseña donde esta se ingresará)
     * @param type $campoAdicional (son los cmpos adicionales que requiere el formulario de sesuión)
     * @param type $mensajeDeError (el el mensaje de respuesta invalida que da el software al fallar la sesión)
     * @param type $rutaArchivo (es la ruta del archivo
     * @param type $usuario
     * @param type $nivelDeUsuario
     */
    public function setDatosDeSesion($urlDeSesion, $urlDeInicio, $urlDeSalir, $campoUsuario, $campoContrasenia, $campoAdicional, 
                                    $mensajeDeError, $rutaArchivo, $usuario, $nivelDeUsuario)
    {
        $this->urlDeSesion = $urlDeSesion;
        $this->urlDeInicio = $urlDeInicio;
        $this->urlDeSalir = $urlDeSalir;
        $this->campoUsuario = trim($campoUsuario);
        $this->campoContrasenia = trim($campoContrasenia);
        $this->campoAdicional = $campoAdicional;
        $this->mensajeDeError = trim($mensajeDeError);
        $this->archivo = trim($rutaArchivo);       
        $this->usuario = $usuario;
        $this->nivelDeUsuario = $nivelDeUsuario;
        
    }
    /**
     * Método que realiza el efecto de la carga de datos
     * @return type array
     */
    public function cargarSesiones()
    {        
        $objArchivo = new GestorDeArchivos;
        $ObjStoper = new Stoper();
        $ObjStoper->Start();

        $campos = $objArchivo->leer($this->archivo);
        $campo[$this->campoUsuario] = "";
        $campo[$this->campoContrasenia] = "";
        
        $cargasExitosas = 0;
        $totalIntentos = 0;
       
        $objSnoopy = new Snoopy();
       
        $resultadoSesion = $objSnoopy->results;

        $k = 0; ##Se inicializa para indicar el campo valido del array.
        for($i=0;$i<count($campos); $i++) {
            if(!empty($campos[$i])) {
                $camposSubmit[$k] = array($this->campoUsuario => $campos[$i] ,$this->campoContrasenia => $campos[$i+1]);
                
                foreach ($this->campoAdicional as $index => $valor) {
                    $camposSubmit[$k][$index] = $valor;
                }
                $i++;$k++;
            }
        }
        $sesionesExitosas = 0;
        $sesionesConError = 0;
        foreach ($camposSubmit as $submit) {
            $objSnoopy->maxredirs = 1;
            $objSnoopy->setcookies();
            $objSnoopy->submit($this->urlDeSesion, $submit);
            if (!strstr($objSnoopy->results, $this->mensajeDeError)) {
                    $sesionesExitosas++;
                    $this->resultado["resultado_grafico"] .= ""
                            . "<br><div class='table-responsive col-md-12'>". 
                            $objSnoopy->results
                            . "</div>";
                    $objSnoopy->submit($this->urlDeInicio);
                    $objSnoopy->submit($this->urlDeSalir);
                    
            } else {
                $sesionesConError++;
            }
                    
        }
        $ObjStoper->Stop();
        $totalDeUsuarios = count($camposSubmit);
        $contenido = "DETALLES DE LA PRUEBA DE CARGA DE SESIONES" . PHP_EOL.PHP_EOL.PHP_EOL;
        $contenido .= "Fecha y Hora de Creación: " . date("d-m-Y")
                . " " . Helper::convertirHoraA12H(date("H:i:s")) . PHP_EOL;
        $contenido .= "Usuario: " . strtoupper($this->usuario) . " (" . $this->nivelDeUsuario . ")" . PHP_EOL.PHP_EOL;
        $contenido .= "URL de inicio de sesión: " . $this->urlDeSesion . PHP_EOL;
        $contenido .= "Total de usuarios para la prueba: " . $totalDeUsuarios . "\r\n\r\n";
        $contenido .= "RESULTADO: \r\n";
        $contenido .= "Total de usuarios con sesión exitosa: " . $sesionesExitosas ."\r\n";
        $contenido .= "Total de usuarios con sesión sin éxito: $sesionesConError\r\n\r\n";
        if($sesionesExitosas == $totalDeUsuarios) {
            $contenido .= "Todos los usuarios iniciaron una sesión correctamente.\r\n"
                    . "Resumen Final: El sistema es capaz de soportar la carga simultánea de $totalDeUsuarios sesiones.\r\n";            
           $this->resultado["resultado"] = "\"Todos los usuarios iniciaron una sesión correctamente\".<br>"
                    . "<b>Resumen Final</b>: El sistema es capaz de soportar la carga simultánea de $totalDeUsuarios sesiones sin inconveniente alguno, "
                   . "teniendo como resultado un excelente desempeño en esta área evaluada.\r\n";  
        } else {
            $contenido .= "\"Algunos usuarios tuvieron problemas para iniciar sesión\".\r\n"
                    . "Resumen Final: Se encontraron fallas de inicio de sesión simultaneamente en el sistema.\r\n"
                    . "Si usted cree que esto es un error, repita la prueba en otro ciclo de evaluación y revise cuidadosamente "
                    . "el diccionario de datos cargado, es decir, que los usuarios sean validos. Se recomienda usar la opción por defecto.";
        
            $this->resultado["resultado"] = "\"Algunos usuarios tuvieron problemas para iniciar sesión\".<BR>"
                    . "Resumen Final: Se encontraron fallas de inicio de sesión simultaneamente en el sistema.<BR>"
                    . "Si usted cree que esto es un error, repita la prueba en otro ciclo de evaluación y revise cuidadosamente "
                    . "el diccionario de datos cargado, es decir, que los usuarios sean validos. Se recomienda usar la opción por defecto.";
        }
        $rutaDeArchivo = "files/tests-results/login-load/prueba_de_sesiones" . date("d-m-Y") . "_" . date("H-i-s") 
                        . "_prueba-de-carga.log";
        if($objArchivo->escrbir($rutaDeArchivo, $contenido)) {
            $this->resultado["result_prueba"] = 1;
        }
        else {
            $this->resultado["result_prueba"] = 0;
        }
        
        $this->resultado["fecha"] = date("Y-m-d");
        $this->resultado["hora"] = date("H:i:s");
        $this->resultado["tiempo_de_ejecucion"] = $ObjStoper->showResult();
        $this->resultado["sesiones_totales"] = $totalDeUsuarios;
        $this->resultado["sesiones_exitosas"] = $sesionesExitosas;
        $this->resultado["sesiones_fallidas"] = $sesionesConError;
        $this->resultado["ruta_de_archivo"] = URLBASE . $rutaDeArchivo;
        $this->resultado["resultado"] = $this->resultado["resultado"];
        
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
                        <th>Usuarios Totales</th>
                        <td>' . $this->resultado["sesiones_totales"] . '</td>
                    <tr>
                        <th>Sesiones Exitosas</th>
                        <td>'.$this->resultado["sesiones_exitosas"].'</td>
                    </tr>
                        <th>Sesiones Fallidas</th>
                        <td>'.$this->resultado["sesiones_fallidas"].'</td>
                    </tr>';
        $salida .= "    <tr>
                            <th>Tiempo de Ejecución</th>
                            <td>".$this->resultado["tiempo_de_ejecucion"]." segundos.</td>
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

