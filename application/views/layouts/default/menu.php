<?php
require dirname(__FILE__) . "/seguridad.php";
?>
<div id='cssmenu-left'>
<ul>
   <!--<li><a href='#'><span>Inicio</span></a></li>-->

    <li class='active has-sub'></span><a href='#' <?php echo $estiloUsuario; ?>><span>Gestión de Usuarios</span></a>
    <?php if ($nivelDeUsuario == "Administrador"): ?>
    <ul>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>usuario/registrar'><span class="glyphicon glyphicon-plus-sign"></span> <span>Registrar</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>usuario/consultar'><span class="glyphicon glyphicon-search"></span> <span>Consultar</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>usuario/papelera'><span class="glyphicon glyphicon-trash"></span> <span>Papelera</span></a></li>
    </ul>
     <?php endif; ?>   
   </li>
   
   <li class='active has-sub'><a href='#' <?php echo $estiloEvaluacion; ?>><span>Gestión de Evaluación</span></a>
    <?php if ($nivelDeUsuario == "Evaluador"): ?>
    <ul>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>evaluacion/nueva'><span class="glyphicon glyphicon-plus-sign"></span> <span>Nueva Evaluación</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>consultar-evaluaciones'><span class="glyphicon glyphicon-search"></span> <span>Consultar</span></a></li>
    </ul>
     <?php endif; ?>   
   </li>
   
    <li class='active has-sub'><a href='#' <?php echo $estiloSeguridad; ?>><span>Evaluación de Seguridad</span></a>
    <?php if ($nivelDeUsuario == "Evaluador"): ?>
      <ul>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seguridad/fuerza-bruta'><img src="<?php echo URLBASE; ?>public/img/brutal-force.png" class="icono-menu"><span>Fuerza Bruta</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seguridad/inyeccion-sql'><img src="<?php echo URLBASE; ?>public/img/injection-sql.png" class="icono-menu"><span>Inyección SQL</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seguridad/inyeccion-xss'><img src="<?php echo URLBASE; ?>public/img/injection-xss.png" class="icono-menu"><span>Inyección XSS</span></a>

    </li>
      </ul>
    <?php endif; ?>   
    </li>
  
   <li class='active has-sub'><a href='#' <?php echo $estiloRendimiento; ?>><span>Evaluación de Rendimiento</span></a>
    <?php if ($nivelDeUsuario == "Evaluador"): ?> 
    <ul>
        <li class='has-sub'><a href='#'><img src="<?php echo URLBASE; ?>public/img/data_upload.png" class="icono-menu"><span>Carga</span></a>
            <ul>
              <li class='has-sub'><a href='<?php echo URLBASE; ?>rendimiento/prueba-de-carga'><img src="<?php echo URLBASE; ?>public/img/data_upload.png" class="icono-menu"><span>De Datos</span></a></li>
              <li class='has-sub'><a href='<?php echo URLBASE; ?>seguridad/inyeccion-sql'><img src="<?php echo URLBASE; ?>public/img/injection-sql.png" class="icono-menu"><span>De Sesiones</span></a></li>              
            </ul>
        </li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>rendimiento/prueba-de-estres'><img src="<?php echo URLBASE; ?>public/img/database.png" class="icono-menu"><span>Estrés</span></a></li>
    </ul>
     <?php endif; ?>
   </li>     
   
   <li class='active has-sub'><a href='#' <?php echo $estiloPruebas; ?>><span>Resultados de Pruebas</span></a>
    <?php if ($nivelDeUsuario == "Evaluador"): ?> 
    <ul>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seleccionar-reporte-por-caso'><span class="glyphicon glyphicon-search"></span> <span>Tipos de Pruebas</span></a>
            <ul>
                    <li><a href="#">Seguridad</a></li>
                    <li><a href="#">Rendimiento</a></li>
            </ul>
        </li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seleccionar-reporte-final'><img src="<?php echo URLBASE; ?>public/img/pdf.png" class="icono-menu"><span>Reporte Final</span></a></li>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seleccionar-informe-de-calidad'><img src="<?php echo URLBASE; ?>public/img/Adobe_PDF_file_icon_16x16.png" class="icono-menu"><span>Informe de Calidad</span></a></li>
    </ul>
     <?php endif; ?>
   </li>   
   
   <li class='active has-sub'><a href='#' <?php echo $estiloRendimiento; ?>><span>Herramientas Adicionales</span></a>
    <?php if ($nivelDeUsuario == "Evaluador"): ?> 
    <ul>
        <li class='has-sub'><a href='<?php echo URLBASE; ?>adicionales/cantidad-de-usuarios'><span class="glyphicon glyphicon-user"></span> <span>Diccionario de Usuarios</span></a></li>
        <!--<li class='has-sub'><a href='<?php echo URLBASE; ?>rendimiento/prueba-de-carga'><span class="glyphicon glyphicon-user"></span> <span>Generar otros usuarios</span></a></li>-->
    </ul>
     <?php endif; ?>
   </li> 
   
   <li class='active has-sub'><a href='#' <?php echo $estiloHistorial; ?>><span>Historial de Usuarios</span></a>
    <?php if ($nivelDeUsuario == "Administrador"): ?>   
       <ul>      
        <li class='has-sub'><a href='<?php echo URLBASE; ?>historial/usuarios'><span class="glyphicon glyphicon-search"></span> <span>Consultar</span></a></li>
      </ul>
    <?php endif; ?>
   </li>
   <li class='active has-sub'><a href='#' <?php echo $estiloConfiguracion; ?>><span>Configuración</span></a>
     
       <ul>
<?php if ($nivelDeUsuario == "Administrador"): ?>            
        <li class='has-sub'><a href='<?php echo URLBASE; ?>este-sistema'><span class="glyphicon glyphicon-cog"></span> <span>Este Sistema</span></a></li>  
<?php 
       endif;
if ($nivelDeUsuario == "Evaluador"): 
?>         
        <li class='has-sub'><a href='<?php echo URLBASE; ?>seleccionar-configuracion-del-sistema-en-evaluacion'><span class="glyphicon glyphicon-cog"></span> <span>Sistema en Evaluación</span></a></li>
      </ul>
    <?php endif; ?>
   </li>   
</ul>
</div>
