<?php
/**
 * =================================================
 * @Descripcion: Modelo de las Pruebas de Cargas
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Febrero, 2016
 * @Version: 0.1 Beta
 * @Licencia: Libre uso GNU-GPL 
 * @E-Mail: miguel__salazar@hotmail.com
 * ==================================================
 * 
 */

set_time_limit(900);
require_once "librarys/Snoopy-2.0.0/Snoopy.class.php";
require_once "librarys/Stoper/class.stoper.php";
require_once "librarys/Helper/Helper.php";

class DiccionarioDeUsuarios
{    
    private $url;
    private $urlParte1;
    private $urlParte2;
    private $datosDeSesion;
    private $datosParte1;
    private $datosParte2;
    private $mensajeDeExito;    
    private $resultado;
    private $cantidad;
    private $campoAdicionalParte1;
    private $campoAdicionalParte2;
    
    public function __construct() {
        $this->resultado = array();
    }

    public function setDatosDeSesion($url, $datos, $mensajeDeError) 
    {
        $this->url = $url;
        $this->datosDeSesion = $datos;     
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
    public function setDatosDeCarga($urlParte1, $urlParte2, 
                                    $datosParte1, $datosParte2,
                                    $campoAdicionalParte1,
                                    $campoAdicionalParte2,
                                    $mensajeDeExito, $usuario, 
                                    $nivelDeUsuario, $cantidad)
    {
        $this->urlParte1 = $urlParte1;
        $this->urlParte2 = $urlParte2;
        $this->datosParte1 = $datosParte1;
        $this->datosParte2 = $datosParte2;
        $this->campoAdicionalParte1 = $campoAdicionalParte1;
        $this->campoAdicionalParte2 = $campoAdicionalParte2;
        $this->mensajeDeExito = $mensajeDeExito;
        $this->cantidad = $cantidad;     
        $this->usuario = $usuario;
        $this->nivelDeUsua1rio = $nivelDeUsuario;        
    }
    /**
     * Método que realiza el efecto de la carga de datos
     * @return type array
     */
    public function CrearMultiplesUsuarios()
    {        
        $objArchivo = new GestorDeArchivos();
        $ObjStoper = new Stoper();
        $ObjStoper->Start(); 
        
        $cargasExitosas = 0;
        $totalIntentos = 0;       
        $rutaDeArchivo = "files/downloads/diccionario-de-usuarios/diccionario_de_usuarios_" 
                . date("d-m-Y") . "_" . date("H-i-s") . ".txt";     
        #echo $objSnoopy->results;
        while($cargasExitosas < $this->cantidad) {
            $objSnoopy = new Snoopy();
            $objSnoopy->maxredirs = 1;
            $objSnoopy->submit($this->url, $this->datosDeSesion);
            $objSnoopy->setcookies();
            $resultadoSesion = $objSnoopy->results;
            
            $datosParte1 = $this->datosParte1;
            
            $_datosParte1 = Helper::obtenerDatosAleatorios($datosParte1);
            foreach($this->campoAdicionalParte1 as $llave => $valor) {
               $_datosParte1[$llave] = $valor;
            }
             
            $objSnoopy->submit($this->urlParte1, $_datosParte1);
            
            $datosParte2 = $this->datosParte2;               
            $_datosParte2 = Helper::obtenerDatosAleatorios($datosParte2);
            foreach($this->campoAdicionalParte2 as $llave => $valor) {
               $_datosParte2[$llave] = $valor;
            }               
            
            $objSnoopy->submit($this->urlParte2, $_datosParte2);
            $datosUsuario = array("dniEmpleado" => $_datosParte1["dni"], 
                                  "tipoUsuario" => "Administrador", 
                                  "submit"      => "Registrar");
            $objSnoopy->submit("http://localhost/sirah/registrar-usuario", $datosUsuario);
            $objSnoopy->submit("http://localhost/sirah/salir");
            //$objSnoopy->submit("http://localhost/sirah/registrarse");
            
            $contrasenia = Helper::generarContraseniaAleatorio(8);
            $email = Helper::generarLetrasAleatorias(12);
            $usuario = Helper::generarLetrasAleatorias(10);            
            

            
            #echo $result;
            $datosDeRegistro = array("dniEmpleado"      => $_datosParte1["dni"],
                                     "usuario"          => $usuario,
                                     "contrasenia"      => $contrasenia,
                                     "contrasenia2"     => $contrasenia,
                                     "email"            => $email . "@mail.com",
                                     "email2"           => $email . "@mail.com",
                                     "preguntaSecreta"  => Helper::generarLetrasAleatorias(8),
                                     "respuestaSecreta" => Helper::generarLetrasAleatorias(8),
                                     "submit"          => "Registrar",
                                     "enviar"          => "enviar");
            $objSnoopy->submit("http://localhost/sirah/registrarse", $datosDeRegistro);
            #echo $objSnoopy->results;
            $contenido = $usuario . PHP_EOL;
            $contenido .= $contrasenia . PHP_EOL;
            $contenido .= PHP_EOL;
                if (!strstr($resultadoSesion, $this->mensajeDeError)) {
                    $resultado = $objSnoopy->results;
                    if (strstr($resultado, $this->mensajeDeExito)) {                        
                        $cargasExitosas++;

                        $result = $objArchivo->agregar($rutaDeArchivo, $contenido);
                        if ($result) {            
                            $this->resultado["guardar"][] = true;

                        } else {
                            $this->resultado["guardar"][] = false;
                        }
                            
                    } else {                      
                        die("<br><center><h4>ERROR: Configuración erronea.</h4></center>");                        
                    }
                } else {
                    die("<BR><center><h4>ERROR: Datos de sesión invalidos.</h4></center>");
                }       
            
        }       
        $ObjStoper->Stop();       
        
        $tiempoDeEjecucion = $ObjStoper->showResult();
        
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

        $this->resultado["tiempo_de_ejecucion"] = $tiempoDeEjecucion;
        $this->resultado["total_creado"] = $this->cantidad;
        $this->resultado["ruta_de_archivo"] = URLBASE . $rutaDeArchivo;
        $this->resultado["ruta_raiz_de_archivo"] = RUTARAIZ . trim($rutaDeArchivo);
        return $this->resultado;
    }
    /**
     * Método para mostrar resultados la la prueba. Requeriere el llamo previo de la función anterior
     * @return string
     */
    public function mostrar()
    {
        if ($this->resultado["total_creado"] == 1) {
            $total = $this->resultado["total_creado"] . " usuario creado";
        } else {
            $total = $this->resultado["total_creado"] . " usuarios creados";
        }
        $salida ='
           
                
                    <tr>
                        <th>Usuarios Creados</th>
                        <th>Tiempo de Ejecución</th>                        
                    </tr>
               
                ';
        $salida .= "<tr>"
                    . "<td>"
                    . $total
                    . "</td>"
                    . "<td>"
                    . $this->resultado["tiempo_de_ejecucion"]
                    . "</td>"
                . "</tr>";                 
        $salida .= "
                
            ";
        return $salida;
    }
    
}

