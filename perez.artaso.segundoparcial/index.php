<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
use Slim\Http\UploadedFile as UploadedFile;
require 'vendor/autoload.php';
include 'BDCon.php';
include 'Usuario.php';
include 'Bicicleta.php';
include 'Log.php';


$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$validarAdmin = function($request, $response, $next)
{
    $token = $request->getHeader("Authorization");    
    Usuario::validarAdmin($token[0]);
    $response = $next($request, $response);

    return $response;
};

$validarUsuario = function($request, $response, $next)
{
    $token = $request->getHeader("Authorization");    
    Usuario::validarUsuario($token[0]);
    $response = $next($request, $response);

    return $response;
};

$log = function($request, $response, $next)
{
    if($request->hasHeader("Authorization"))
    {
        $token = $request->getHeader("Authorization");   
        $id = Usuario::obtenerUsuario($token[0]);
    }
    else
    {
        $id = "No Logueado";
    }

    $metodo = $request->getMethod();
    $uri = $request->getUri();    
    Log::cargarLog($id, $metodo, $uri->getPath());
    $response = $next($request, $response);
    return $response;
};

$app->post('/crearUsuario', function(Request $request, Response $response){
    
            $data = $request->getParsedBody();
            
            if($data != null)
            {
                Usuario::crearUsuario($data["nombre"], $data["clave"], $data["nivel"]);
                $response->getBody()->write("¡ Usuario Dado De Alta Con Exito !");
            }
            else
            {
                $response->getBody()->write("Error Al Ingresar Parametros");
            }
    
    
})->add($log);

$app->post('/login', function(Request $request, Response $response){
    $data = $request->getParsedBody();
    
    

    if(($token = Usuario::Login($data["nombre"], $data["clave"]))===-1)
    {
        return $response->getBody()->write("Usuario Inexistente");
    }
    else if($token===0)
    {
        return $response->getBody()->write("Contraseña Incorrecta");
    }
    else
    {

        $response->getBody()->write("Token: ". $token);        
        return $response;
    }    

})->add($log);

$app->get("/bicicletas", function(Request $request, Response $response){
    return $response->getBody()->write(Bicicleta::ListarBicis());
})->add($log);

//Emprolijar
$app->post('/bicicletas', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['foto'];
    $directory = "./";
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedFile($directory, $uploadedFile);
        $response->write('uploaded ' . $directory . "/" .$filename . '<br/>');
    }
    $ext = Bicicleta::ObtenerExtension($filename);
    $nombreBici = $directory.$data["marca"].".".date("YmdHis").".".$ext;
    rename($directory . $filename, $nombreBici);
    if($data != null)
    {       
        Bicicleta::BiciCarga($data["marca"], $data["precio"], $nombreBici);
        $response->getBody()->write("¡ Bici Cargada Con Exito !");
    }
    else
    {
        $response->getBody()->write("Error Al Ingresar Parametros");
    }

    return $response;
})->add($validarAdmin)->add($log);

$app->get("/ventas", function (Request $request, Response $response){
    $token = $request->getHeader("Authorization");
    $usuario = Usuario::obtenerUsuario($token[0]);
    return $response->getBody()->write(Usuario::listarCompras($usuario));
})->add($validarUsuario)->add($log);

$app->post("/ventas", function (Request $request, Response $response){
    return $response->getBody()->write(Usuario::listarComprasCompleta());
})->add($validarAdmin)->add($log);

/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploaded file uploaded file to move
 * @return string filename of moved file
 */
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(8); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->run();