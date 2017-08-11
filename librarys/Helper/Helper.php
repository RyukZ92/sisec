<?php
/**
 * @Descripcion: Librería básica que sive como asisnte para funciones básicas en una aplicación en PHP.
 * Funciones comunes como validar campos, generar cadenas aleatorias, convertir fechas, filtrar campos, ente otras
 * @Version: 2.0
 * @Licencia: BSD
 * @Autor: Miguel Salazar
 * @E-Mail: miguelangel.bux@gmail.com
 * 
 */
class Helper
    {
        /**
         *
         * Método para comprobar un array con campos facíos 
         * @param type $datos array
         * @return boolean
         */
        public static function validarCamposVacios($datos)
            {
                $vacio = false;
                foreach ($datos as $valor) {
                    if(empty($valor))
                        $vacio = true;
                }
                return $vacio;
            }
            
        /**
         * Método para validar el formato de contraseña.
         * 
         * @param type $contrasenia string Formato: Longitud de 8 caracteres alfanuméricos (mayúsucas y minúculas)
         * @return boolean
         */
        public static function validarContrasenia($contrasenia)
            {
                if(!preg_match('`[a-z]`',$contrasenia) 
                    || !preg_match('`[A-Z]`',$contrasenia) 
                    || !preg_match('`[0-9]`',$contrasenia) 
                    || strlen($contrasenia)<8) {
                    return true;
                } else {
                    return false;
                }
            }
        /**
         * Genera una contraseña aleatoria ajustada al formado de la función anterior
         * @param type $longitudPass int
         * @return type string (cadena aleatoria)
         */
        public static function generarContraseniaAleatorio($longitudPass = 10) 
          {
            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $longitudCadena = strlen($cadena);

            $pass = "";

            for($i=1; $i <= $longitudPass; $i++) {
                $pos = rand(0, $longitudCadena - 1);                
                $pass .= substr($cadena, $pos, 1);
            }
            return $pass;
        }
        /**
         * Genera una seria de números aleatorios
         * @param type $longitud int Tamaño del número a retornar
         * @return type interger
         */
        public static function generarNumerosAleatorios($longitud = 6) 
          {
            
            $cadena = "1234567890";            
            $longitudCadena = strlen($cadena);            
            $salida = "";
            
            for($i=1; $i <= $longitud; $i++) {                
                $pos = rand(0, $longitudCadena - 1);      
                
                $salida .= substr($cadena, $pos, 1);
            }
            return $salida;
        }
        /**
         * Método para general letras aleatorias
         * @param type $longitud int (tamaño que desea la cadena)
         * @return type string
         */
        public static function generarLetrasAleatorias($longitud = 10) 
          {
            
            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";            
            $longitudCadena = strlen($cadena);            
            $salida = "";
            
            for($i=1; $i <= $longitud; $i++) {               
                $pos = rand(0, $longitudCadena - 1);                
                $salida .= substr($cadena, $pos, 1);
            }
            return $salida;
        }
        /**
         * Método que genera una seria de caracteres alfanumerisoc aleatorios
         * @param type $longitud int - tamaño de la cadena
         * @return type string
         */
        public static function generarCaracteresAleatorios($longitud = 10) 
          {
            
            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";            
            $longitudCadena = strlen($cadena);            
            $salida = "";       

            
            for($i=1; $i <= $longitud; $i++) {                
                $pos = rand(0, $longitudCadena - 1);                
                $salida .= substr($cadena, $pos, 1);
            }
            return $salida;
        }
        /**
         * Método para generar una fecha aleatoria
         * @param type $from date - fecha de inicio (para establecer un rango)
         * @param type $to date - fecha final (para establecer un rango)
         * @return type date
         */
        public static function generarFechaAleatoria($from = "1930/01/01", $to = "1995/12/31") 
                {
            if (!$to) {
                $to = date('U');
            }
            if (!ctype_digit($from)) {
                $from = strtotime($from);
            }
            if (!ctype_digit($to)) {
                $to = strtotime($to);
            }
            return date('d/m/Y', rand($from, $to));
        }
        /**
         * Método para generar una hora aleatoria
         * @param type $from  time - hora de inicio (para establecer un rango)
         * @param type $to time - hora final (para establecer un rango)
         * @return type date
         */
        public static function generarHoraAleatoria($from = 0, $to = null) 
        {
            if (!$to) {
                $to = date('U');
            }
            if (!ctype_digit($from)) {
                $from = strtotime($from);
            }
            if (!ctype_digit($to)) {
                $to = strtotime($to);
            }
            return date('H:i:s', rand($from, $to));
        }
        /**
         * Método que permite filtra un campo de códigos maliciosos (Ej. XSS, SQL)
         * @param type $campo string - es el campo que se desea limpiar
         * @return type string
         */
        public static function limpiarCampo($campo) 
            {
                $campo = htmlspecialchars(trim(addslashes(stripslashes(strip_tags($campo)))));
                $campo = str_replace(chr(160), '', $campo);
                return $campo;
            }
        /**
         * Método que permite validar que el rango de dos fechas sean correctos
         * @param type $fecha1 date
         * @param type $fecha2 date
         * @return boolean
         */
        public static function validarRangoDeFecha($fecha1, $fecha2) 
            {
                if (strtotime($fecha1) > strtotime($fecha2)) {
                    return true;
                } else {
                    return false;
                }
            }
        /**
         * Método que convierte una fecha en formado aaaa-mm-dd a formado dd-mm-aaaa
         * @param type $fecha - fecha en formato aaaa-mm-dd
         * @return type date
         */
        public static function convertirFechaDdMmAaaa($fecha)	
        {
            $nueva_fecha = explode('-', $fecha);
            return $nueva_fecha = $nueva_fecha[2] . "-" . $nueva_fecha[1] . "-" . $nueva_fecha[0];
        }
        /**
         * Método que convierte una fecha en formado dd-mm-aaaa a formado aaa-mm-dd
         * @param type $fecha date - la fecha en formato dd-mm-aaaa que desea convertir
         * @return type date
         */
        public static function convertirFechaAaaaMmDd($fecha) 
        {
            $nueva_fecha = explode('-',$fecha);
            return $nueva_fecha = $nueva_fecha[2] . "." . $nueva_fecha[1] . "-" . $nueva_fecha[0];
        }
        /**
         * Método que calcula y obtiene la edad por medio de una fecha
         * @param type $fecha date - fecha en formato dd-mm-aaaa
         * @return type date
         */
        public static function obtenerEdad($fecha) 
        {
           list($Y,$m,$d) = explode("-", $fecha);
           return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
        }
        /**
         * Convierte una hora en formato 24 horas a formato de 12 horas
         * @param type $hora time - hora en formato de 24 horas
         * @return type time
         */
        public static function convertirHoraA12H($hora)	
        {
            return date("h:i:s A", strtotime($hora));
        }  
        /**
         * Convierte una hora en formato 12 horas a formato de 24 horas
         * @param DateTime $hora time
         * @return type time
         */
        public static function convertirHoraA24H($hora)	
        {
            $hora = new DateTime($hora);
            return $hora->format('H:i:s');
        }
        /**
         * Permite limpiar dos o más espacios en una cadena
         * @param type $cadena string - es la cadena que se desea filtrar
         * @return type string
         */
        public static function limpiarEspacios($cadena)
        {
            $cadena = preg_replace('/\s+/', ' ', $cadena);
            return $cadena;
        }
       /**
        * onvierte segudos a formato de hora - minuto y segundos
        * @param type $segundos float - los segundos que desaean convertirse
        * @return type float
        */
        public static function conversorDeSegundosAHora($segundos) 
        {
            $tiempo["horas"] = floor($segundos / 3600);
            $tiempo["minutos"] = floor(($segundos - ($tiempo["horas"] * 3600)) / 60);
            $tiempo["segundos"] = $segundos - ($tiempo["horas"] * 3600) - ($tiempo["minutos"] * 60);
            return $tiempo;
        }
        /**
         * Permite capturar el navegador donde el cliente ejecuta la aplicación
         * @return string (array) - nombre del navegador, la versión y el sistema operativo
         */
        public static function getBrowser() 
        {
            $browser=array("IE","Opera","Netscape","Firefox","Safari","Chrome", "Trident", "Cungaguaro");
            $os=array("Windows","Mac","Linux"); # definimos unos valores por defecto para el navegador y el sistema operativo
            $info["browser"] = "Desconocido";
            $info["os"] = "Desconocido"; # buscamos el navegador con su sistema operativo

            foreach($browser as $parent) {
                    $s = strpos(strtoupper($_SERVER["HTTP_USER_AGENT"]), strtoupper($parent));
                    $f = $s + strlen($parent);
                    $version = substr($_SERVER["HTTP_USER_AGENT"], $f, 15);
                    $version = preg_replace("/[^0-9,.]/",'',$version);
            if ($s) {
                    $info["browser"] = $parent; 
                    $info["version"] = $version;
            }
            }
            foreach($os as $val) {
            if (strpos(strtoupper($_SERVER["HTTP_USER_AGENT"]), strtoupper($val)) !== false)
                    $info["os"] = $val;
            }
            return $info;
        }
        
        /**
         * 
         * @param type $string (el estring a encriptar)
         * @param type $key (la llave utilizada para encritar)
         * @return type string
         */
        public static function encrypt($string, $key) {
           $result = '';
           $key = md5($key);
           for($i=0; $i<strlen($string); $i++) {
              $char = substr($string, $i, 1);
              $keychar = substr($key, ($i % strlen($key))-1, 1);
              $char = chr(ord($char)+ord($keychar));
              $result.=$char;
           }
           return base64_encode($result);
        }

        /**
         * 
         * @param type $string (el string que se va a desencriptar)
         * @param type $key (la llave que se utilizó para encriptar)
         * @return type string
         */
        public static function decrypt($string, $key) {
           $result = '';
           $key = md5($key);
           $string = base64_decode($string);
           for($i=0; $i<strlen($string); $i++) {
              $char = substr($string, $i, 1);
              $keychar = substr($key, ($i % strlen($key))-1, 1);
              $char = chr(ord($char)-ord($keychar));
              $result.=$char;
           }
           return $result;
        }
        public static function obtenerDatosAleatorios ($datos){
            for($i=0; $i<count($datos); $i++) {
                $indice = $datos[$i]["nombre"];
                $long = $datos[$i]["longitud"];
                
                if ($datos[$i]["tipo"] == "Letras/Números") {
                    $_datos[$indice] = Helper::generarCaracteresAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Letras") {
                    $_datos[$indice] = Helper::generarLetrasAleatorias($long);
                }
                if ($datos[$i]["tipo"] == "Números") {
                    $_datos[$indice] = Helper::generarNumerosAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Cualquier Carácter") {
                    $_datos[$indice] = Helper::generarCaracteresAleatorios($long);
                }
                if ($datos[$i]["tipo"] == "Fecha") {
                    $_datos[$indice] = Helper::generarFechaAleatoria();
                }   
                if ($datos[$i]["tipo"] == "Hora") {
                    $_datos[$indice] = Helper::generarHoraAleatoria();
                }          
            }
            return $_datos;
        }

        /**
         * Otiene el día de la semana en Letras
         * @param type $dia int
         * @return string
         */
        public static function obtenerDiaDeLaSemana($dia)	
        {
            switch ($dia) {
                case 1:
                    return "Lunes";
                break;
                case 2:
                    return "Martes";
                break;
                case 3:
                    return "Miércoles";
                break;            
                case 4:
                    return "Jueves";
                break;         
                case 5:
                    return "Viernes";
                break;
     
                case 6:
                    return "Sábado";
                break;

                case 7:
                    return "Domingo";
                break;
                default:
                    return "Error";
                break;        
            }
        }
        /**
         * Obtiene el mes de año en letra
         * @param type $mes int
         * @return string
         */
        public static function obtenerMesDelAnio($mes) {
            switch ($mes) {
                case 1:
                    return 'Enero';
                break;
                case 2:
                    return 'Febrero';
                break;
                case 3:
                    return 'Marzo';
                break;
                case 4:
                    return 'Abril';
                break;
                case 5:
                    return 'Mayo';
                break;
                case 6:
                    return 'Junio';
                break;
                case 7:
                    return 'Julio';
                break;
                case 8:
                    return 'Agosto';
                break;
                case 9:
                    return 'Septiembre';
                break;
                case 10:
                    return 'Octubre';
                break;
                case 11:
                    return 'Noviembre';
                break;
                case 12:
                    return 'Diciembre';
                break;
            }
        }
    }
?>