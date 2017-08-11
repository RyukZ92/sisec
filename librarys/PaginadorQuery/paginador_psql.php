<?php
#Autor: CesarCancino.com

class Paginador
{
	private $_paginacion;
    protected function _getDbh() 
    {
        return DbPdo::getInstance()->getConn();
    }	
    public function __construct()
    {
        $this->_paginacion = array();
    }
    public function getPaginacion()
    {
        if($this->_paginacion)
            return $this->_paginacion;
        else
            return false;
    }
    public function paginar_5($query, $pagina = false, $limite = false)
    {
        if($limite && is_numeric($limite))
            $limite = $limite;
        else
            $limite = 5;
        if($pagina && is_numeric($pagina)) {
            $pagina = $pagina;
            $inicio = ($pagina-1) * $limite;
            }
        else {
            $pagina = 1;
            $inicio = 0;
        }
            //Se consulta todos los registros
            $consulta = $this->_getDbh()->query($query);
            $registros = $consulta->rowCount(); //Se obtiene el total de los registros
            $total = ceil($registros/$limite);
            //Se modifica la consultar, dandole un limite y un inicio de cuando registros me tiene que dar.
            $query .= " LIMIT $limite OFFSET $inicio"; 
            $consulta = $this->_getDbh()->query($query);
            
    	if($consulta) { //Si la consulta es correcta hace lo siguiente:

            $datos = new ArrayObject();
            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $datos->append($reg);
            }
                $paginacion = array();
                $paginacion['actual'] = $pagina;
                $paginacion['total'] = $total;
                if ($pagina > 1) {
                    $paginacion['primero'] = 1;
                    $paginacion['anterior'] = $pagina-1;
                }else {
                    $paginacion['primero'] = '';
                    $paginacion['anterior'] = '';
                }
                if ($pagina < $total) {
                    $paginacion['ultimo'] = $total;
                    $paginacion['siguiente'] = $pagina+1;
                }else {
                    $paginacion['ultimo']='';
                    $paginacion['siguiente']='';				
                }
        
        } else {
           echo "Problemas al obtener registros";
        }   
            $this->_paginacion = $paginacion;
            return $datos;
    }





}
?>
