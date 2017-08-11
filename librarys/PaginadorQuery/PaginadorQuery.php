<?php
# @Descripción: Librería de Paginación en MySQL y PDO
# @Autor: CesarCancino.com
# @Adaptado por: Miguel Salazar, 2015-2016

class PaginadorQuery
{
    private $pdo;
    private $_paginacion;
    protected function getConnect() 
    {
        $this->pdo = new DbPdo();
        return $this->pdo->connectPdo();
    }
    protected function getDisconnect() 
    {
        return $this->pdo->disconnectPdo();        
    }
    
    public function __construct()
    {
        $this->_paginacion = array();
    }
    /**
     * 
     * @return boolean
     */
    public function getPaginacion()
    {
        if($this->_paginacion)
            return $this->_paginacion;
        else
            return false;
    }
    /**
     * Paginador
     * @param type $query string
     * @param type $pagina int
     * @param type $limite int
     * @return \ArrayObject
     */
    public function paginar($query, $pagina = false, $limite = false)
    {
        if($limite && is_numeric($limite))
            $limite = $limite;
        else
            $limite = 10;
        if($pagina && is_numeric($pagina)) {
            $pagina = $pagina;
            $inicio = ($pagina-1) * $limite;
            }
        else {
            $pagina = 1;
            $inicio = 0;
        }
            //Se consulta todos los registros
            $consulta = $this->getConnect()->query($query);
            $registros = $consulta->rowCount(); //Se obtiene el total de los registros
            $total = ceil($registros/$limite);
            //Se modifica la consultar, dandole un limite y un inicio de cuando registros me tiene que dar.
            $query .= " LIMIT $inicio, $limite"; 
            $consulta=$this->getConnect()->query($query);
            //$consulta=Conectar::conexion()->query($query);

    #	if($consulta) //Si la consulta es correcta hace lo siguiente:

            $datos = new ArrayObject();
            while ($reg=$consulta->fetch(PDO::FETCH_ASSOC)) {
                $datos->append($reg);
            }
                $paginacion=array();
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
                    $paginacion['ultimo'] = '';
                    $paginacion['siguiente'] = '';				
                }

            #else
            #	echo "Problemas al traer registros";
            $this->_paginacion = $paginacion;
            $this->getDisconnect();
            return $datos;
    }
}
?>
