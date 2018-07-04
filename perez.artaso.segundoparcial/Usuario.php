<?php
use \Firebase\JWT\JWT;
class Usuario
{
    public $nombre;
    public $clave;
    public $nivel;

    public static function crearUsuario($nom, $cla, $niv)
    {
            if($niv != "admin" && $niv != "usuario")
            {
                $niv = "usuario";
            }

            $BDCon = BDCon::NuevaBDCon();
            
            $consulta = $BDCon->RetornarConsulta("INSERT INTO usuarios (nombre, clave, nivel)"
                                                        . "VALUES(?, ?, ?)");
            
            $encPass = password_hash($cla, PASSWORD_DEFAULT);
            $consulta->bindValue(1, $nom, PDO::PARAM_STR);
            $consulta->bindValue(2, $cla, PDO::PARAM_STR);
            $consulta->bindValue(3, $niv, PDO::PARAM_STR);
                  
    
            $consulta->execute();
    }

    public static function Login($nom, $pass)
    {
        $retValue = 0;
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("SELECT * FROM usuarios WHERE nombre LIKE ?");        
        $consulta->bindValue(1, $nom, PDO::PARAM_STR);
        $consulta->execute();
        $usuario = $consulta->fetchAll();
        if(count($usuario) == 0)
        {
            $retValue = -1;
        }
        else
        {
            if($pass == $usuario[0]["clave"])
            {
                
                $ahora = new DateTime();
                $futuro = new DateTime("+30 minutes");
                $payload = [
                    "iat"=> $ahora->getTimeStamp(),
                    "exp"=> $futuro->getTimeStamp(),
                    "sub"=> "localhost",                    
                    "usr"=> $usuario[0]["nombre"],
                    "lvl"=> $usuario[0]["nivel"]
                ];
                $secret = "123456";
                $token = JWT::encode($payload, $secret, "HS256");
                $retValue = json_encode($token);    
                         

            }
        }
        
        return $retValue;
        
    }

    public static function listarCompras($usr){
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("SELECT * FROM ventas WHERE usuario LIKE ?");        
        $consulta->bindValue(1, $usr, PDO::PARAM_STR);
        $consulta->execute();
        $compras = $consulta->fetchAll();
        if($compras != FALSE)
        {
               $retStr = '<table style="border: 1px solid black;
               border-collapse: collapse;width:100%">
               <tr>                 
                 <th>Marca</th>                                
               </tr>';

               foreach($compras as $bici)
               {
                   $retStr = $retStr.'<tr style="text-align: center;">
                   <td>'.$bici["bici"].'</td>                                     
                 </tr>';
               }               
        }

            return $retStr;
    }

    public static function listarComprasCompleta(){
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("SELECT * FROM ventas");                
        $consulta->execute();
        $compras = $consulta->fetchAll();
        if($compras != FALSE)
        {
               $retStr = '<table style="border: 1px solid black;
               border-collapse: collapse;width:100%">
               <tr>    
                 <th>Usuario</th>              
                 <th>Marca</th>                                
               </tr>';

               foreach($compras as $bici)
               {
                   $retStr = $retStr.'<tr style="text-align: center;">
                   <td>'.$bici["usuario"].'</td>   
                   <td>'.$bici["bici"].'</td>                                     
                 </tr>';
               }               
        }

            return $retStr;
    }

    public static function validarAdmin($token)
    {
        try {
            
            $payload = JWT::decode($token, "123456", ['HS256']);
            if($payload->lvl != "admin")
            {
                throw new Exception('Permiso Denegado');
            }
           
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function validarUsuario($token)
    {
        try {
            
            $payload = JWT::decode($token, "123456", ['HS256']);
            if($payload->lvl != "usuario")
            {
                throw new Exception('Permiso Denegado');
            }
           
           
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function obtenerUsuario($token)
    {
        try {
            
            $payload = JWT::decode($token, "123456", ['HS256']);
            return $payload->usr;
           
           
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function obtenerId($token)
    {
        try {
            
            $payload = JWT::decode($token, "123456", ['HS256']);
            return $payload->id;
           
           
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
}