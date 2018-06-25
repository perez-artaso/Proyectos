<?php
class BDCon
{
    private static $_objetoBDCon;
    private $_objetoPDO;
 
    private function __construct()
    {
        try {
 
            $this->_objetoPDO = new PDO('mysql:host=localhost;dbname=mediasdb;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
 
            $this->_objetoPDO->exec("SET CHARACTER SET utf8");
 
        } catch (PDOException $e) {
 
            print "Error!!!<br/>" . $e->getMessage();
 
            die();
        }
    }
 
    public function RetornarConsulta($sql)
    {
        return $this->_objetoPDO->prepare($sql);
    }
 
    public static function NuevaBDCon()//singleton
    {
        if (!isset(self::$_objetoBDCon)) {       
            self::$_objetoBDCon = new BDCon(); 
        }
 
        return self::$_objetoBDCon;        
    }
 
    // Evita que el objeto se pueda clonar
    public function __clone()
    {
        trigger_error('La clonaci&oacute;n de este objeto no est&aacute; permitida!!!', E_USER_ERROR);
    }
}
?>