<?php

class Log
{
    public static function cargarLog($id, $met, $rut)
    {
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("INSERT INTO log (id, metodo, ruta, hora)"
                                                    . "VALUES(?, ?, ?, ?)");
        
        $hora = date("H:i:s");
        $consulta->bindValue(1, $id, PDO::PARAM_STR);
        $consulta->bindValue(2, $met, PDO::PARAM_STR);
        $consulta->bindValue(3, $rut, PDO::PARAM_STR);
        $consulta->bindValue(4, $hora, PDO::PARAM_STR);
              

        $consulta->execute();
    }
}