<?php

class Media
{
    public $ID;
    public $color;
    public $marca;
    public $precio;
    public $talle;
    public $foto;

    public function __construct($pId, $pCol, $pMar, $pPre, $pTal, $pFot)
    {
        $this->ID = $pId;
        $this->color = $pCol;
        $this->marca = $pMar;
        $this->precio = $pPre;
        $this->talle = $pTal;
        $this->foto = $pFot;
    }

    public static function desinfectar_media($param)
    {
        $ret_array = [];
        if(!is_array($param))
        {
            return null;
        }
        else
        {
            
            $ret_array['Color'] = filter_var($param['Color'], FILTER_SANITIZE_STRING);
            $ret_array['Marca'] = filter_var($param['Marca'], FILTER_SANITIZE_STRING);
            $ret_array['Precio'] = filter_var($param['Precio'], FILTER_SANITIZE_STRING);
            $ret_array['Talle'] = filter_var($param['Talle'], FILTER_SANITIZE_STRING);
            $ret_array['Foto'] = filter_var($param['Foto'], FILTER_SANITIZE_STRING);

            return $ret_array;
        }
    }

    public static function MediaCarga($color, $marca, $precio, $talle, $foto)
    {
        $BDCon = BDCon::NuevaBDCon();
        
        $consulta = $BDCon->RetornarConsulta("INSERT INTO Medias (Color, Marca, Precio, Talle, Foto)"
                                                    . "VALUES(?, ?, ?, ?, ?)");
        
        
        $consulta->bindValue(1, $color, PDO::PARAM_STR);
        $consulta->bindValue(2, $marca, PDO::PARAM_STR);
        $consulta->bindValue(3, $precio, PDO::PARAM_STR);
        $consulta->bindValue(4, $talle, PDO::PARAM_STR);
        $consulta->bindValue(5, $foto, PDO::PARAM_STR);
       

        $consulta->execute();   
    }
    
    public static function ListarMedias()
    {
        $DBCon = BDCon::NuevaBDCon();

        $consulta = $DBCon->RetornarConsulta("SELECT * FROM Medias");
        $consulta->execute();
        $mediasArray = $consulta->fetchAll();

        $cierreTabla = '</table>';

        if($mediasArray != FALSE)
        {
               $retStr = '<table style="border: 1px solid black;
               border-collapse: collapse;width:100%">
               <tr>
                 <th>Id</th>
                 <th>Color</th> 
                 <th>Marca</th>
                 <th>Precio</th>
                 <th>Talle</th>
                 <th>Foto</th>                 
               </tr>';

               foreach($mediasArray as $media)
               {
                   $retStr = $retStr.'<tr style="text-align: center;">
                   <td>'.$media["ID"].'</td>
                   <td>'.$media["Color"].'</td>
                   <td>'.$media["Marca"].'</td>
                   <td>'.$media["Precio"].'</td>
                   <td>'.$media["Talle"].'</td>
                   <td>'.$media["Foto"].'</td>                   
                 </tr>';
               }               
        }

            return $retStr . $cierreTabla;

    }


}