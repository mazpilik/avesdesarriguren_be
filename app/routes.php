<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Exception\HttpNotFoundException;

return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->post('/login', function (Request $request, Response $response) {
        //get the user from the request
        $raw_user = $request->getParsedBody();

        // find the user in the database
        $db = $this->get(PDO::class);
        $sth = $db->prepare('SELECT * FROM user WHERE username = :username');
        $sth->execute([
            ':username' => $raw_user['name'],
        ]);
        $user = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        // if the user is found
        if ($user) {
            if(password_verify($raw_user['password'], $user[0]['password'])){
                // generate a token
                $factory = new \PsrJwt\Factory\Jwt();
                $builder = $factory->builder();

                $token = $builder->setSecret('!secRet$1234')
                    ->setPayloadClaim('uid', $user[0]['id'])
                    ->build();

                // return the token
                $payload = json_encode(['token' => $token->getToken()]);

                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $payload = json_encode(['error' => 'wrong credentials']);

                $response->getBody()->write($payload);
                return $response->withStatus(401);
            }
            
        } else {
            // user not found
            $payload = json_encode(['error' => 'User not found']);

            $response->getBody()->write($payload);
            return $response->withStatus(401);
        }
    });

    $app->get('/db-test', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
        $sth = $db->prepare('SELECT * FROM genus');
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/createDefUser', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
        $sth = $db->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
        //hash password with dm5
        $password = password_hash('20#SarAdm22', PASSWORD_DEFAULT);
        $sth->execute([
            ':username' => 'admin',
            ':password' => $password
        ]);
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: make sure this route is defined last
     */
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });;
};
