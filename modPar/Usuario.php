<?php
use \Firebase\JWT\JWT;
class Usuario
{
    public $id;
    public $nombre;
    public $pass;

    public static function desinfectar_usuario($param)
    {
        $ret_array = [];
        if(!is_array($param))
        {
            return null;
        }
        else
        {            
            $ret_array['nombre'] = filter_var($param['nombre'], FILTER_SANITIZE_STRING);
            $ret_array['pass'] = filter_var($param['pass'], FILTER_SANITIZE_STRING);
            return $ret_array;
        }
    }

    public static function AltaUsuario($nom, $pass)
    {
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("INSERT INTO usuarios (nombre, pass)"
                                                    . "VALUES(?, ?)");
        
        $encPass = password_hash($pass, PASSWORD_DEFAULT);
        $consulta->bindValue(1, $nom, PDO::PARAM_STR);
        $consulta->bindValue(2, $encPass, PDO::PARAM_STR);
              

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
            if(password_verify($pass, $usuario[0]["pass"]))
            {
                $ahora = new DateTime();
                $futuro = new DateTime("+2 minutes");
                $payload = [
                    "iat"=> $ahora->getTimeStamp(),
                    "exp"=> $futuro->getTimeStamp(),
                    "sub"=> "localhost"
                ];
                $secret = "123456";
                $token = JWT::encode($payload, $secret, "HS256");
                $retValue = json_encode($token);             

            }
        }
        
        return $retValue;
        
    }

    public static function validarToken($token)
    {
        try {
            
            $payload = JWT::decode($token, "123456", ['HS256']);
           
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

   
}