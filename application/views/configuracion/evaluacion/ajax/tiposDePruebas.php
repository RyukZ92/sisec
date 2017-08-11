<?php
$salida = "";
switch ($_POST["opc"]) {
    case "seguridad":
        $salida .= ' <option value="fuerza_bruta" >Prueba de Fuerza Bruta</option>
                     <option value="sql">Prueba de Inyección SQL</option>
                     <option value="xss" >Prueba de Inyección XSS</option>                      
                   ';
    break;
    case "rendimiento":
        $salida .= '  
                    <option value="carga">Carga de Datos</option>
                    <!--<<option value="sesion" >Carga de Sesiones</option>
                    option value="estres">Prueba de Estrés</option>-->
                   ';
    break;
    default:
        $salida .= '<option value="0">Esperando selección...</option>';
    break;
}
echo $salida;                  
                         
?>