<?php 

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';
require 'rb.php';

R::setup( 'mysql:host=localhost:8889;dbname=appvue', 'root', 'root' );
// Create Router instance
$router = new \Bramus\Router\Router();

$router -> options('.*', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-width');
    exit();
} );
// Define routes
function jsonResponse($data){
    header('Access-Control-Allow-Origin: *');
    header( "Content-Type: application/json" );
    echo json_encode($data);
}
// This route handling function will only be executed when visiting http(s)://www.example.org/about
$router->get('/', function() {
    $clientes = R::find('clientes');

    jsonResponse(R::exportAll($clientes));
    
});
$router->post('/', function(){
    $data=json_decode(file_get_contents("php://input"), true);
    $client = R::dispense('clientes');
    $client->name=$data['name'];
    $client->lastname=$data['lastname'];
    $client->mail=$data['mail'];
    $id=R::store($client);
    
    jsonResponse(['mensaje' => 'Cliente agregado', 'id'=>$id]);
});

$router->delete('/{id}', function($id) {
    $client=R::trash('clientes', $id);

    jsonResponse(['mensaje' => 'Cliente eliminado']);
    });
// Run it!
$router->run();
?>