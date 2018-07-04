<?php

class Bicicleta
{
    public $id;   
    public $marca;
    public $precio;    
    public $foto;

    public function __construct($pId, $pMar, $pPre, $pFot)
    {
        $this->id = $pId;
        $this->marca = $pMar;
        $this->precio = $pPre;
        $this->foto = $pFot;
    }
   

    public static function BiciCarga($marca, $precio, $foto)
    {
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("INSERT INTO bicis (Marca, Precio, Foto)"
                                                    . "VALUES(?, ?, ?)");
        
        
        
        $consulta->bindValue(1, $marca, PDO::PARAM_STR);
        $consulta->bindValue(2, $precio, PDO::PARAM_STR);        
        $consulta->bindValue(3, $foto, PDO::PARAM_STR);
       

        $consulta->execute();   
    }
    
    public static function ListarBicis()
    {
        $DBCon = BDCon::NuevaBDCon();

        $consulta = $DBCon->RetornarConsulta("SELECT * FROM bicis");
        $consulta->execute();
        $bicisArray = $consulta->fetchAll();

        $cierreTabla = '</table>';

        if($bicisArray != FALSE)
        {
               $retStr = '<table style="border: 1px solid black;
               border-collapse: collapse;width:100%">
               <tr>
                 <th>Id</th>
                 <th>Marca</th>
                 <th>Precio</th>
                 <th>Foto</th>                 
               </tr>';

               foreach($bicisArray as $bici)
               {
                   $retStr = $retStr.'<tr style="text-align: center;">
                   <td>'.$bici["id"].'</td>
                   <td>'.$bici["marca"].'</td>
                   <td>'.$bici["precio"].'</td>
                   <td>'.$bici["foto"].'</td>                   
                 </tr>';
               }               
        }

            return $retStr . $cierreTabla;

    }

    public static function ObtenerExtension($nombreArchivo)
    {
        $divNombre = explode(".", $nombreArchivo);
        $divNombre = array_reverse($divNombre);
        return trim($divNombre[0]);
    }

    public static function BorrarMedia($id)
    {
        $DBCon = BDCon::NuevaBDCon();        
        
            $existe = $DBCon->RetornarConsulta("SELECT * FROM medias WHERE ID = ?");
            $existe->bindValue(1, $id, PDO::PARAM_INT);
            $existe->execute();
            if(count($existe->fetchAll())===0)
            {
                echo "Producto Inexistente";
                exit;
            }
            $consulta = $DBCon->RetornarConsulta("DELETE FROM medias WHERE ID = ?");            
            $consulta->bindValue(1, $id, PDO::PARAM_INT);
            $consulta->execute();
        
        echo "Accion Realizada Con Exito";        
    }


}