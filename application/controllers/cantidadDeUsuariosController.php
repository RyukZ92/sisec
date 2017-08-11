<?php
/* @Descripcion: Controlador para guardar la cantidad de usuarios que se desean generar
 * @Author: Miguel Salazar
 * @Fecha: Dic, 2015 - Feb, 2016
 * @Version: 0.1 Beta
 * @Licencia: BSD
 * @E-Mail: miguel__salazar@hotmail.com
 * 
 */

if (isset($_POST["continuar"])) {
    if(!empty($_POST["cantidad"])) {
        $_SESSION["cantidad"] = $_POST["cantidad"];
         header("location:confirmacion-de-diccionario-de-usuarios");
    } else {
        $notificacion = $alerta["campos_vacios"];
    }
}
unset($_SESSION["resultados_de_prueba"]);
require "application/views/adicionales/cantidadDeUsuariosView.phtml";