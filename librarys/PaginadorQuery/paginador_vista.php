        <ul class="pagination">
            <!--<?php   if($params['primero']):
                    $primero = URLBASE . $_GET["vista"] . "/pagina/" . $params['primero'];
            ?>
                    <li>
                        <a href="<?php echo $primero; ?>">Primera</a>
                    </li>    
                    <?php   else: ?>
                    <li class='disabled'>
                            <a>Primera</a>
                    
                    </li>
            <?php   endif; ?>-->    
            <?php   if($params['anterior']):	
                        $anterior = URLBASE . $_GET["vista"] . "/pagina/" . $params['anterior'];
            ?> 
                        <li>

                            <a href="<?php echo $anterior ?>">
                            <!--<input type="button" value="&larr; Anterior"  class="btn-enabled" />-->
                                Anterior
                            </a>
                        </li>    
            <?php   else: ?>
                        <li class='disabled' >
                            <a>Anterior</a>
            <?php   endif; ?>
            </li>
            
            <?php   
            $limite = 5;
                    for ($pag = 1; $pag <= $params['total']; $pag++):  
                        $p = $pag;
                        if ($params['actual'] == $pag):
            ?>
            <li  class="active">
                <a href="<?php echo $pag; ?>"><?php echo $pag; ?></a>
            </li>
            <?php       else:              
                                
            ?>

                            <li>
                                <a href="<?php echo $p; ?>"><?php echo $p; ?></a>
                            </li>

            <?php       
                        endif;
                    endfor; 
            ?>
            <?php   if($params['siguiente']):
                        $siguiente = URLBASE . $_GET["vista"] . "/pagina/" . $params['siguiente'];
            ?>
                        <li>

                            <a href="<?php echo $siguiente ?>">
                                Siguiente
                            </a>
                        </li>
            <?php   else: ?>
                        <li class='disabled' >
                            <a>Siguiente</a>
            <?php   endif; ?>
                        </li>
            <!--<?php   if($params['ultimo']):
                    $ultimo = URLBASE . $_GET["vista"] . "/pagina/" . $params['ultimo'];
            ?>
                    <li>

                        <a class='parametro' href="<?php echo $ultimo; ?>">

                            Último
                        </a>
                    </li>
            <?php   else: ?>
            <li class='disabled' >
                <a>Último</a>
            <?php   endif; ?>-->
            </li>
        </ul>
