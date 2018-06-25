<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';
include 'Media.php';
include 'BDCon.php';
include 'Usuario.php';
use \Firebase\JWT\JWT;

$app = new \Slim\App;

$validarToken = function($request, $response, $next){
    if(($request->hasHeader("Authorization")) === false)
    {
        $response->getBody()->write("Acceso Denegado");
    }
    else
    {
        $token = $request->getHeader("Authorization");
        Usuario::validarToken($token[0]);
        $response = $next($request, $response);
    }

    return $response;
};

#Cargar Una Media A La Base De Datos-----------------------------------------------------------------------------------------------------------
$app->post('/AltaMedias', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $data_medias = Media::desinfectar_media($data);
    if($data_medias != null)
    {
        Media::MediaCarga($data_medias["Color"], $data_medias["Marca"], $data_medias["Precio"], $data_medias["Talle"], $data_medias["Foto"]);
        $response->getBody()->write("¡ Media Cargada Con Exito !");
    }
    else
    {
        $response->getBody()->write("Error Al Ingresar Parametros");
    }

    return $response;
});
#----------------------------------------------------------------------------------------------------------------------------------------------

$app->get('/ListarMedias', function(Request $request, Response $response){

    return $response->getBody()->write(Media::ListarMedias());

});

$app->post('/AltaUsuario', function(Request $request, Response $response){

        $data = $request->getParsedBody();
        $data_limpia = Usuario::desinfectar_usuario($data);
        if($data_limpia != null)
        {
            Usuario::AltaUsuario($data_limpia["nombre"], $data_limpia["pass"]);
            $response->getBody()->write("¡ Usuario Dado De Alta Con Exito !");
        }
        else
        {
            $response->getBody()->write("Error Al Ingresar Parametros");
        }


})->add($validarToken);

$app->post('/Login', function(Request $request, Response $response){
    $data = $request->getParsedBody();
    $data_limpia = Usuario::desinfectar_usuario($data);
    
    if(($token = Usuario::Login($data_limpia["nombre"], $data_limpia["pass"]))===-1)
    {
        return $response->getBody()->write("Usuario Inexistente");
    }
    else if($token===0)
    {
        return $response->getBody()->write("Contraseña Incorrecta");
    }
    else
    {
        $response->getBody()->write("Logueado Con Exito");        
        return $response->withHeader("Authorization", $token);
    }    

});



$app->run();


?>