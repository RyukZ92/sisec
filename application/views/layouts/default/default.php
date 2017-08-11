<!DOCTYPE html>
<html>
    <head>
        
        <title>SISEC</title>
        <meta charset='UTF-8'/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <?php 
        
        require "application/views/layouts/default/css.php"; 
        require "application/views/layouts/default/scripts.php";        
        ?>
        
    </head>
    <body id="body">
        <?php
        
        # Mensajes o alertas generales del sistema
        #require_once "application/views/mensajes/mensajes.php";
        
        
        $sesion = $_SESSION["sesion_" . $_SESSION["usuario"]];
        ?>
        
        <!-- Configuración del servidor del lado del cliente -->
        <input type="hidden" id="URLBASE" value="<?php echo URLBASE; ?>">
        
     <?php $tam = ($sesion) ?"auto":"85%"; ?>
    <div class="cuerpo" style="min-height:<?php echo $tam; ?>">  
        <?php include "application/views/layouts/default/cabecera.php"; ?>
        <?php include "application/views/layouts/default/menu-top.php"; ?>
        

        <div id="left">
            <?php 
            if ($sesion) {
            include "application/views/layouts/default/usuario.php";
            include "application/views/layouts/default/menu.php"; 
 
            $estilo = ($sesion) ? "vista" : "vista-sesion";
            }
        ?>
        </div>
        
            <div id="<?php echo $estilo; ?>">  
                
                <?php include "http/controller/indexController.php"; ?>
            </div>    
        
       
    </div>
     <?php include "application/views/layouts/default/pie-de-pagina.php";?>    
    
    </body>
</html>
