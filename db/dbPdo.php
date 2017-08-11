<?php
/**
 * ===============================================
 * @Descripcion: Libreria de conexion a base de datos PDO
 * @Adaptada: Miguel Salazar, 2016
 * @compartida : Andres Guzman
 * @Licencia: Libre uso GNU-GPL
 * @Version 2.0
 * ===============================================
 */
$configDB = parse_ini_file("config/config.db.ini", true);

define(DB_DNS, $configDB["db.config.manager"]
                . ":host=" . $configDB["db.config.host"] . ";"
                . "dbname=" . $configDB["db.config.dbname"]);
define(DB_USERNAME, $configDB["db.config.username"]);
define(DB_PASSWORD, $configDB["db.config.password"]);


class DbPdo
{
    private $pdo;
    private $dns = DB_DNS;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;

    public function connectPdo()
    {        
        try {
         $this->pdo = new PDO($this->dns, $this->username, $this->password);
         $this->pdo->exec("SET CHARACTER SET utf8");
         return $this->pdo;
         
        } catch(PDOException $e){
         echo '<B>¡Error!</b> no se puede conectar a la base de datos.<b><br>Detalle:</b> ' . $e->getMessage();
         die();
        }
    }
   public function disconnectPdo() {
      $this->pdo = null;
   }
}


/*

class DbPdo {
      
    private $_dbh;
    private $_username = DB_USERNAME;
    private $_password = DB_PASSWORD;
    private $_dns = DB_DNS;

    private static $_instance = null;
    
      public static function getInstance() {
        if (!(self::$_instance instanceof DbPdo)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __construct() {
        try {
            $this->_dbh = new PDO($this->_dns, $this->_username, $this->_password);
            $this->_dbh->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            echo "Error al conectar DB!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getConn() {
        if ($this->_dbh == null) {
            self::getInstance();
        }

        return $this->_dbh;
    }

    public function isConn() {
        return ((bool) ($this->_dbh instanceof PDO));
    }

    public function closeConn() {
        $this->_dbh = null;
    }

    public function __destruct() {
        $this->closeConn();
    }	
 
}
*/
