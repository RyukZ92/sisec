<?php 
$sesion = $_SESSION["sesion_" . $_SESSION["usuario"]];
?>

<div id='cssmenu-top'>
<ul>
	
   <li class='active' ><a href="<?php echo $r = $sesion ?  URLBASE . "inicio":URLBASE; ?>"><span class="glyphicon glyphicon-home"></span> <span>Inicio</span></a></li>

<?php if($sesion): ?>   
   <li class='last' style="float:right;"><a href="<?php echo URLBASE; ?>cerrar"><span class="glyphicon glyphicon-log-out"></span> <span>Cerrar Sesión</span></a></li>
   <li class='last' style="float:right;"><a href="<?php echo URLBASE; ?>usuario/cuenta"><span class="glyphicon glyphicon-user"></span> <span>Mi Cuenta</span></a></li>
   <li class='last' style="float:right;"><a href="<?php echo URLBASE; ?>historial/usuario"><span class="glyphicon glyphicon-list-alt"></span> <span>Mi Historial</span></a></li>      
<?php endif;?>
</ul>
</div>
