<!--<div id="pie-de-pagina"><a href="$#" style="color:white;">Contacto</a>Diseño y Desarrollo Por Miguel Salazar, 2015-2016 <BR>Apache PHP MariaDB JS</div>-->
<style>
    #pie-de-pagina a {color:white;}
    #pie-nav{
        list-style:none;
    }
    #pie-de-pagina a:hover {
    color:#fff;
    }
    #pie-nav li {
        float: left;
        width: 20%;
        border:0px solid #222;
    }
    ul.pie-nav{overflow: hidden;}
    
    #pie-de-pagina label{
        width: 15%;
        border:0px solid #000;
    }
</style>
<!--<div id="pie-de-pagina">
    <ul id="pie-nav">
        <li>
            <a href="">Contacto</a>
        </li>
        <li>
            <a href="">Términos y Condiciones</a>
        </li>
        <li>
            <a href="">Donativos</a>
        </li> 
        <li>
            <a href="">Seguridad Informática</a>
        </li>
        <li>
            <a href="">Calidad de Software</a>
        </li>        
    </ul>
</div>-->
<div id="pie-de-pagina">
    <label><a target="_blanck" href="<?php echo URLBASE; ?>sitio/contacto">Contacto</a></label>
    <label><a target="_blanck" href="<?php echo URLBASE; ?>sitio/condiciones">Condiciones</a></label>    
    <label><a target="_blanck" href="<?php echo URLBASE; ?>sitio/calidad-de-software">Calidad de Software</a></label>
    <label><a href="<?php echo URLBASE; ?>acerca-del-producto-software">Acerca de</a></label>
    <label><a target="_blanck" href="<?php echo URLBASE; ?>sitio/ayuda">¿Necesitas Ayuda?</a></label>

</div>
