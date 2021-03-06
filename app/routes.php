<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        var_dump($request);
        $response->getBody()->write('Géniaaal!');
        return $response;
    });
    
    //afficher tous les vins
    $app->get('/api/wines', function(Request $request, Response $response) {
        //Récupérer les données de la BD
        //$data = include('public/wines.json');
        
        
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = 'SELECT * FROM wine';
            
            //Envoyer la requête
            $statement = $pdo->query($query);

            //Extraire les données
            $wines = $statement->fetchAll(PDO::FETCH_CLASS);
        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    //Chercher un vin par son nom
    $app->get('/api/wines/search/{name}', function(Request $request, Response $response, array $args){ 
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = "SELECT * FROM wine WHERE name LIKE '%".$args['name']."%'";
            
            //Envoyer la requête
            $statement = $pdo->query($query);

            //Extraire les données
            $wines = $statement->fetchAll(PDO::FETCH_CLASS);
        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    //Chercher un vin par son id 
     $app->get('/api/wines/{id}', function(Request $request, Response $response, array $args){ 
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = "SELECT * FROM wine WHERE id='{$args['id']}'";
            
            //Envoyer la requête
            $statement = $pdo->query($query);

            //Extraire les données
            $wines = $statement->fetchAll(PDO::FETCH_CLASS);
        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    $app->get('/api/wines/{id}', function(Request $request, Response $response, array $args){ 
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = "INSERT INTO ´wine´"
                    ."(´id´, ´name´, ´year´, ´grapes´, ´country´,´region´,´description´,´picture´"
                    ."VALUES (null,:name,:year,:country,:region,:description,:picture";
            
            //Envoyer la requête
            $statement = $pdo->prepare($query);
            
            //Executer la requete préparée
            $result = $statement->execute([
               ':name' => $wine['name'],
                ':year' => $wine['year'],
                ':grapes' => $wine['grapes'],
                ':country' => $wine['country'],
                ':region' => $wine['region'],
                ':description' => $wine['description'],
                ':picture' => $wine['picture'],
            ]);

            //Extraire les données
            $wines = $statement->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    //supprimer un vin par son id
    $app->delete('/api/wines/{id}', function(Request $request, Response $response, array $args){ 
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = "DELETE FROM wine WHERE id='{$args['id']}'";
            
            $nbRows = $pdo->exec($query);
            
            $wines = $nbRows>0 ? true:[
                [
                    "error" => "aucun enregistrement n'a été supprimé",
                ]
            ];

        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    //Ajouter un vin dans la bdd
    $app->post('/api/wines', function(Request $request, Response $response) {
        //Récupérer les données de la BD
        //$data = include('public/wines.json');
        
        
        //Se connecter au serveur de db
        try{
            
            $pdo = new PDO('mysql:host=localhost; dbname=cellar', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

            //Préparer la requête
            $query = 'SELECT * FROM wine';
            
            //Envoyer la requête
            $statement = $pdo->query($query);

            //Extraire les données
            $wines = $statement->fetchAll(PDO::FETCH_CLASS);
        } catch(PDOException $e){
            $wines = [
                [
                    "error" => "probleme de bdd",
                    "errorCode" => $e->getCode(),
                    "errorMsg" => $e->getMessage(),
                ]              
            ];
        }
        
        //Convertir les données en JSON
        $data = json_encode($wines);           //var_dump($wines); die;
        
        $response->getBody()->write($data);
        return $response
                ->withHeader('content-type', 'application/json')
                ->withHeader('charset', 'utf-8');
    });
    
    

    $app->group('/users', function (Group $group) {
        $group->get('/', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    
};

