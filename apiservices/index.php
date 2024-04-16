<?
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';
require 'includes/Operaciones.php';
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);


/*************************************************************
PLATAFORMA EMPRESARIOS
***************************************************************/
$app->post('/loginempresa',function(Request $request, Response $response){
$request_data = $request->getParsedBody();
$usuario = $request_data["usuario"];
$password = $request_data["password"];
$db = new Operaciones;
$res= $db->loginempresa($usuario,$password);
$response->write(json_encode($res));
		return $response
		->withHeader('Content-type','application/json')
		->withStatus(200);

});

$app->get('/getPatentesEmpresa',function(Request $request, Response $response){
$request_data = $request->getParsedBody();
$id = $request_data["id"];
$db = new Operaciones;
$res= $db->getPatentesEmpresa($id);
$response->write(json_encode($res));
		return $response
		->withHeader('Content-type','application/json')
		->withStatus(200);

});

/*******************************************************
APP PLAZA TERMINAL
********************************************************/
$app->post('/loginApp',function(Request $request, Response $response){
$request_data = $request->getParsedBody();
$usuario = $request_data["usuario"];
$password = $request_data["password"];
$db = new Operaciones;
$res= $db->loginApp($usuario,$password);
$response->write(json_encode($res));
		return $response
		->withHeader('Content-type','application/json')
		->withStatus(200);

});

$app->get('/validarTarjeta/{codigo}',function(Request $request, Response $response, $args){
$codigo= $request->getAttribute('codigo');
$db = new Operaciones;
$res= $db->validarTarjeta($codigo);
$response->write(json_encode($res));
return $response
->withHeader('Content-type','application/json')
->withStatus(200);
});



$app->run(); 

?>