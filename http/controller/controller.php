<?php
/**
 * =============================================================================
 * @Descripcion: Controlador principal que gestiona y procesa todas las peticiones 
 * del cliente vía GET
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Abril, 2016
 * @Version: 3.5
 * @Licencia: BSD
 * @E-Mail: miguelangel.bux@gmail.com
 * =============================================================================
 */
$sesion = $_SESSION["sesion_" . $_SESSION["usuario"]];
$vista = strtolower(addslashes($_GET["vista"]));
$opcion = strtolower(addslashes($_GET["opcion"]));


$urlActual = URLBASE . $vista . "/" . $opcion; /*Se utiliza para evitar problemas con las url en el uso de las vistas*/

    if(!$sesion
        && $vista != "recuperar-contrasenia"
        && $vista != "recuperacion-por-pregunta-y-respuesta-secreta"
        && $vista != "recuperacion-por-email"
        && $vista != "condiciones-de-uso"
        && $vista != "faq"
        && $vista != "contacto"
        && $vista != "acerca-de"
        && $vista != "ayuda"
        || $vista == "iniciar-sesion") {       
        require "application/controllers/inicioDeSesionController.php";  
    } else if(!$sesion && $vista == "recuperar-contrasenia") {
            $tema = array(
            "actual" => "Soporte de Usuarios",
            "icono"  => "life-saver",
            "vista"  => "Recuperación de Contraseña"
            );
        require "application/controllers/recuperarContraseniaController.php";
    } else if(!$sesion && $vista == "recuperacion-por-pregunta-y-respuesta-secreta") {
            $tema = array(
            "actual" => "Soporte de Usuarios",
            "icono"  => "life-saver",
            "vista"  => "<strong>Recuperación de Contraseña:</strong> Pregunta y Respuesta Secreta"
            );
        require "application/controllers/recuperarContraseniaPorPreguntaYRespuestaSecretaController.php";
    } else if(!$sesion && $vista == "recuperacion-por-email") {
            $tema = array(
            "actual" => "Soporte de Usuarios",
            "icono"  => "life-saver",
            "vista"  => "<strong>Recuperación de Contraseña:</strong> Correo Electrónico"
            );
        require "application/controllers/recuperarContraseniaPorEmailController.php";
    } else if (!$sesion && $vista == "acerca-de") {
                $tema = array(
                            "actual" => "Acerca de",
                            "icono"  => "info-circle",
                            "vista"  => "Acerca del Producto Software"
                        );                    
                    require "application/controllers/acercaDelProductoController.php";

    } else if (!$sesion && $vista ==  "condiciones-de-uso") {
                $tema = array(
                            "actual" => "Condiciones de Uso",
                            "icono"  => "question-circle",
                            "vista"  => "Condiciones de Uso de SISEC"
                        );
                    
                    require "application/controllers/terminosController.php";
    } else if (!$sesion && $vista == "faq") {
                $tema = array(
                            "actual" => "FAQ",
                            "icono"  => "question-circle",
                            "vista"  => "Preguntas y Respuestas Frecuentes"
                        );                    
                    require "application/controllers/faqController.php";
    } else if (!$sesion && $vista == "contacto") {
                $tema = array(
                            "actual" => "Contacto",
                            "icono"  => "pencil",
                            "vista"  => "Información de Contacto"
                        );                    
                    require "application/controllers/contactoController.php";
    } else if (!$sesion && $vista == "ayuda") {
                $tema = array(
                            "actual" => "Ayuda",
                            "icono"  => "book",
                            "vista"  => "Manual de Ayuda"
                        );
                    
                    require "application/controllers/ayudaController.php";
    } else {
        if($sesion) {
            
            switch ($vista) {
                    ##
                    ## Módulos de Configuración del Software en Evaluación
                    ##
                case "configurar":
                    $tema = array(
                            "actual" => "Configuraciones",
                            "icono"  => "cogs"
                            );
                        switch ($opcion) {
                            case "evaluacion":
                                $tema["vista"] = "Selección de la Configuración";
                                require "application/controllers/seleccionarConfiguracionDeSistemaEnEvaluacionController.php";
                            break;
                            case "software":
                                $tema["vista"] = "Configuración del Software";
                                require "application/controllers/configurarSistemaEnEvaluacionController.php";
                            break;
                            #
                            # Configuración de pruebas de seguridad
                            #
                            case "fuerza-bruta":
                                $tema["vista"] = "Configuración de la Prueba de Fuerza Bruta";
                                require "application/controllers/configurarFuerzaBrutaController.php";
                            break;
                            case "inyeccion-sql":
                                $tema["vista"] = "Configuración de la Prueba de Inyección SQL";
                                require "application/controllers/configurarInyeccionSqlController.php";
                            break;
                            case "inyeccion-xss":
                                $tema["vista"] = "Configuración de la Prueba de Inyección XSS";
                                require "application/controllers/configurarInyeccionXssController.php";
                            break;
                            #
                            # Configuración de pruebas de rendimiento
                            #
                            case "prueba-de-carga":
                                $tema["vista"] = "Configuración de la Prueba de Carga de Datos";
                                require "application/controllers/configurarPruebaDeCargaController.php";
                            break;
                            case "prueba-de-sesion":
                                $tema["vista"] = "Configuración de la Prueba de Carga de Sesiones";
                                require "application/controllers/configurarPruebaDeSesionController.php";
                            break;                    
                        }
                break;
                case "usuario":
                    $tema = array(
                            "actual" => "Usuarios",
                            "icono"  => "users"
                            );
                    
                    switch ($opcion) {
                        case "nuevo":
                            $tema["vista"] = "Nuevo Usuario";
                            require "application/controllers/registrarUsuarioController.php";
                        break;
                        case "listado":
                            $tema["vista"] = "Listado de Usuarios";
                            require "application/controllers/consultarUsuarioController.php";
                        break;
                        case "detalle":
                            $tema["vista"] = "Detalle del Usuario";
                            require "application/controllers/detalleDelUsuarioController.php";
                        break;                    
                        case "actualizacion":
                            $tema["vista"] = "Edición de Usuario";
                            require "application/controllers/editarUsuarioController.php";
                        break;
                        case "eliminacion":
                            $tema["vista"] = "Enviar  Usuario a la Papelera";
                            require "application/controllers/eliminarUsuarioController.php";
                        break;
                        case "restauracion":
                            $tema["vista"] = "Restauración de Usuario";
                            require "application/controllers/restaurarUsuarioController.php";
                        break;
                        case "papelera":
                            $tema["vista"] = "Papelera de Reciclaje de Usuarios";
                            require "application/controllers/papeleraUsuarioController.php";
                        break;
                        case "cuenta":
                            $tema["vista"] = "Mis Datos de Usuario";
                            require "application/controllers/miCuentaController.php";
                        break;
                        case "actualizacion-de-mis-datos":
                            $tema["vista"] = "Actualización de Mis Datos de Usuario";
                            require "application/controllers/actualizarMisDatosController.php";
                        break; 
                    }
                break;            
                    #
                    # Opciones de recuperación de contraseña
                    #
                case "recuperacion":
                    $tema = "Recuperación de Contraseña";
                    switch ($opcion) {
                        case "contrasenia":
                            require "application/controllers/recuperarContraseniaController.php";
                        break;
                        case "pregunta-y-respuesta-secreta":                            
                            require "application/controllers/recuperarContraseniaPorPreguntaYRespuestaSecretaController.php";
                        break;
                        case "email":
                            require "application/controllers/recuperarContraseniaPorEmailController.php";
                        break;

                    }
                break;
                    #
                    # Módulos del historial de usuario en genral
                    #
                case "historial";
                        $tema = array(
                            "actual" => "Historial de Usuarios",
                            "icono"  => "history"
                            );
                    switch ($opcion) {
                        case "usuarios":
                            $tema["vista"] = "Listado del Historial de Usuarios";
                            require "application/controllers/consultarHistorialDeUsuarioController.php";
                        break;
                        case "usuario":
                            $tema["vista"] = "Mi Historial";
                            require "application/controllers/consultarMiHistorialDeUsuarioController.php";
                        break;
                        case "usuario-por-fecha":
                            $tema["vista"] = "Mi Historial";
                            require "application/controllers/consultarMiHistorialDeUsuarioPorFechaController.php";
                        break;
                        case "usuarios-por-fecha":
                            $tema["vista"] = "Listado del Historial de Usuarios";
                            require "application/controllers/consultarHistorialDeUsuarioPorFechaController.php";
                        break;
                    }
                break;
                    #
                    # Módulo del ciclo de evaluación
                    #
                case "evaluacion";
                        $tema = array(
                            "actual" => "Ciclo de Evaluación",
                            "icono"  => "circle-o-notch"
                            );                    
                    switch ($opcion) {
                        case "nuevo-ciclo":                           
                            $tema["vista"] = "Nuevo Ciclo de Evaluación";
                            require "application/controllers/nuevoCicloDeEvaluacionController.php";
                        break;
                        case "listado":                           
                            $tema["vista"] = "Listado de los Ciclos de Evaluaciones";
                            require "application/controllers/listarCicloDeEvaluacionController.php";                            
                        break;
                        case "detalle":                           
                            require "application/controllers/detalleDelCicloDeEvaluacionController.php";
                        break;
                    }
                break;
                    #
                    # Módulo de las pruebas de seguridad del softwarew
                    #
                case "seguridad";
                    $tema = array(
                            "actual" => "Seguridad",
                            "icono"  => "shield"
                            );
                    ## 
                    ## Fuerza Bruta
                    ##
                    switch ($opcion) {
                        case "fuerza-bruta":
                            $tema["vista"] = "<strong>Fuerza Bruta:</strong> Carga de Contraseñas";
                            require "application/controllers/cargarFuerzaBrutaController.php";
                        break; 
                        case "confirmacion-de-prueba-de-fuerza-bruta":
                            $tema["vista"] = "<strong>Fuerza Bruta:</strong> Confirmación de la Prueba";
                            require "application/controllers/confirmacionDePruebaDeFuerzaBrutaController.php";
                        break;
                        case "ataque-de-fuerza-bruta":
                            $tema["vista"] = "<strong>Fuerza Bruta:</strong> Resumen de los Resultados de la Prueba";
                            require "application/controllers/generarAtaqueDefuerzaBrutaController.php";
                        break;
                    
                   
                        ##
                        ## Inyección SQL
                        ##
                        case "inyeccion-sql":
                              $tema["vista"] = "<strong>Inyección SQL:</strong> Carga de Inyecciones SQL";
                              require "application/controllers/cargarInyeccionSqlController.php";
                         break;
                         case "confirmacion-de-prueba-de-inyeccion-sql":
                              $tema["vista"] = "<strong>Inyección SQL:</strong> Confirmación de la Prueba";
                              require "application/controllers/confirmacionDePruebaDeInyeccionSqlController.php";
                         break;
                         case "inyectar-sql":
                              $tema["vista"] = "<strong>Inyección SQL:</strong> Resumen de los Resultados de la Prueba";
                              require "application/controllers/inyectarSqlController.php";
                         break;
                         case "resultado-inyeccion-sql":
                             $tema["vista"] = "<strong>Inyección SQL:</strong> Resumen de los Resultados de la Prueba";
                              require "application/views/inyeccionSql/resultadoInyeccionSqlView.phtml";
                         break;
                        ##
                        ## Inyección XSS
                        ##
                        case "inyeccion-xss":
                             $tema["vista"] = "<strong>Inyección XSS:</strong> Carga de Inyecciones XSS";
                              require "application/controllers/cargarInyeccionXssController.php";
                         break;
                         case "confirmacion-de-prueba-de-inyeccion-xss":
                             $tema["vista"] = "<strong>Inyección XSS:</strong> Confirmación de la Prueba";
                              require "application/controllers/confirmacionDePruebaDeInyeccionXssController.php";
                         break;
                         case "inyectar-xss":
                              $tema["vista"] = "<strong>Inyección XSS:</strong> Resumen de los Resultados de la Prueba";
                              require "application/controllers/inyectarXssController.php";
                         break; 
                         case "resultado-inyeccion-sql":
                              $tema["vista"] = "<strong>Inyección XSS:</strong> Resumen de los Resultados de la Prueba";
                              require "application/views/inyeccionSql/resultadoInyeccionSqlView.phtml";
                         break;
                    }
                break;
                /**
                 * Módulos de Pruebas de Rendimiento
                 */
                case "rendimiento";
                    $tema = array(
                            "actual" => "Seguridad",
                            "icono"  => "shield"
                            );
                    switch ($opcion) {
                        #
                        # Prueba de Carga de Datos
                        #
                        case "prueba-de-carga": 
                              $tema["vista"] = "<strong>Carga de Datos:</strong> Preperación de la Prueba";
                              require "application/controllers/prepararPruebaDeCargaController.php";
                        break;
                        case "confirmacion-de-prueba-de-carga":
                              $tema["vista"] = "<strong>Carga de Datos:</strong> Confirmación de la Prueba";
                              require "application/controllers/confirmacionDePruebaDeCargaController.php";
                        break; 
                        case "ejecutar-prueba-de-carga":
                              $tema["vista"] = "<strong>Carga de Datos:</strong> Resultados de la Prueba";
                              require "application/controllers/ejecutarPruebaDeCargaController.php";
                        break;
                        #
                        # Prueba de Carga de Sesión
                        #
                        case "prueba-de-sesion": 
                              $tema["vista"] = "<strong>Carga de Sesiones:</strong> Preperación de la Prueba";
                              require "application/controllers/prepararPruebaDeSesionController.php";
                        break;
                        case "confirmacion-de-prueba-de-sesion":
                              $tema["vista"] = "<strong>Carga de Sesiones:</strong> Confirmación de la Prueba";
                              require "application/controllers/confirmacionDePruebaDeSesionController.php";
                        break;
                        case "ejecutar-prueba-de-sesion":
                              $tema["vista"] = "<strong>Carga de Sesiones:</strong> Resultados de la Prueba";
                              require "application/controllers/ejecutarPruebaDeSesionController.php";
                        break;
                        case "resultado-de-prueba-de-sesion":
                              $tema["vista"] = "<strong>Prueba de Sesión:</strong> Resultados de la Prueba";
                              require "application/controllers/ejecutarPruebaDeCargaController.php";
                        break;
                    }
                break;
                /**
                 * Módulos de herramientas adicionales
                 */
                case "adicionales";
                    $tema = array(
                            "actual" => "Herramientas Extras",
                            "icono"  => "wrench"
                            );
                    switch ($opcion) {       
                        case "cantidad-de-usuarios":
                             $tema["vista"] = "<strong>Diccionario de Usuarios:</strong> Preperación del Diccionario";
                             require "application/controllers/cantidadDeUsuariosController.php";
                        break;
                        case "multi-usuarios-parte-1":    
                            
                              require "application/controllers/configurarMultiUsuariosParte1Controller.php";
                        break;
                        case "multi-usuarios-parte-2":                             
                              require "application/controllers/configurarMultiUsuariosParte2Controller.php";
                        break;
                        case "confirmacion-de-diccionario-de-usuarios":
                            $tema["vista"] = "<strong>Diccionario de Usuarios:</strong> Confirmación de la Creación del Diccionario de Usuario";
                              require "application/controllers/confirmacionDeDiccionarioDeUsuariosController.php";
                        break; 
                        case "crear-diccionario-de-usuarios":
                             $tema["vista"] = "<strong>Diccionario de Usuarios:</strong> Resultado Final";
                              require "application/controllers/crearDiccionarioDeUsuariosController.php";
                        break;
                    }
                break;
                    ##
                    ## Módulos de Resultados de las Pruebas
                    ##
                case "resultado":
                    $tema = array(
                            "actual" => "Resultados",
                            "icono"  => "file-text"
                            );
                        switch ($opcion) {
                        case "listado-de-pruebas-realizadas":
                            $tema["vista"] = "Listado de las Pruebas Realizadas";
                            require "application/controllers/listarPruebasDeSeguridadController.php";
                        break;
                        case "listado-de-resultados-por-ciclos":
                            $tema["vista"] = "Listados de Resultados por Ciclos";
                            require "application/controllers/listarResultadosPorCiclosController.php";
                        break;
                        case "informe-de-calidad":                         
                            #$tema["vista"] = "Configuración del Software";
                            require "application/controllers/informeDeCalidadController.php";
                        break;
                        #
                        # Configuración de pruebas de seguridad
                        #
                        case "fuerza-bruta":
                            $tema["vista"] = "Configuración de la Prueba de Fuerza Bruta";
                            require "application/controllers/configurarFuerzaBrutaController.php";
                        break;
                        case "inyeccion-sql":
                            $tema["vista"] = "Configuración de la Prueba de Inyección SQL";
                            require "application/controllers/configurarInyeccionSqlController.php";
                        break;
                        case "inyeccion-xss":
                            $tema["vista"] = "Configuración de la Prueba de Inyección XSS";
                            require "application/controllers/configurarInyeccionXssController.php";
                        break;
                        #
                        # Configuración de pruebas de rendimiento
                        #
                        case "prueba-de-carga":
                            $tema["vista"] = "Configuración de la Prueba de Carga de Datos";
                            require "application/controllers/configurarPruebaDeCargaController.php";
                        break;
                        case "prueba-de-sesion":
                            $tema["vista"] = "Configuración de la Prueba de Carga de Sesiones";
                            require "application/controllers/configurarPruebaDeSesionController.php";
                        break;                    
                        }
                break;
                 
               ##
               ## Módulos Informátivos o Referenciales del software
               ##
                case "inicio":     
                    $tema["vista"] = "<strong>Bienvenido</strong>";
                    require "application/controllers/inicioController.php";
                break;            
                case "acerca-de":
                $tema = array(
                            "actual" => "Acerca de",
                            "icono"  => "info-circle",
                            "vista"  => "Acerca del Producto Software"
                        );
                    
                    require "application/controllers/acercaDelProductoController.php";
                break;
                case "condiciones-de-uso":
                $tema = array(
                            "actual" => "Condiciones de Uso",
                            "icono"  => "question-circle",
                            "vista"  => "Condiciones de Uso de SISEC"
                        );
                    
                    require "application/controllers/terminosController.php";
                break;
                case "faq":
                $tema = array(
                            "actual" => "FAQ",
                            "icono"  => "question-circle",
                            "vista"  => "Preguntas y Respuestas Frecuentes"
                        );                    
                    require "application/controllers/faqController.php";
                break;
                case "contacto":
                $tema = array(
                            "actual" => "Contacto",
                            "icono"  => "pencil",
                            "vista"  => "Información de Contacto"
                        );                    
                    require "application/controllers/contactoController.php";
                break;
                case "ayuda":
                $tema = array(
                            "actual" => "Ayuda",
                            "icono"  => "book",
                            "vista"  => "Manual de Ayuda"
                        );
                    
                    require "application/controllers/ayudaController.php";
                break;            
                case "cerrar":
                    require "application/controllers/cerrarSesionController.php";
                break;
                default:  
                    if (!$vista){
                        $tema["vista"] = "Bienvenido";
                        require "application/controllers/inicioController.php";
                    } else {
                        require "application/controllers/errorController.php";
                        //print "<center><BR><BR><H2><font color=blue;>¡LO SENTIMOS!<BR>Página no encontrada en nuestro servidor.</font></H2></center>";
                    }
            } 
        }else {
                require "application/controllers/inicioDeSesionController.php";
            }
    }
