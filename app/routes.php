<?php

declare(strict_types=1);

use App\Application\Actions\Bird\CreateBirdAction;
use App\Application\Actions\Bird\DeleteBirdAction;
use App\Application\Actions\Bird\FindBirdByIdAction;
use App\Application\Actions\Bird\FindBirdsSortedAction;
use App\Application\Actions\Bird\GetBirdsCountAction;
use App\Application\Actions\Bird\UpdateBirdAction;
use App\Application\Actions\Family\CreateFamilyAction;
use App\Application\Actions\Family\FamilyDeleteAction;
use App\Application\Actions\Family\FindFamiliesByOrderId;
use App\Application\Actions\Family\FindFamilyByIdAction;
use App\Application\Actions\Family\GetFamilyCountAction;
use App\Application\Actions\Family\ListAllFamilyAction;
use App\Application\Actions\Family\ListFamilySortedAction;
use App\Application\Actions\Family\UpdateFamilyAction;
use App\Application\Actions\Order\CreateOrderAction;
use App\Application\Actions\Order\FindOrderByIdAction;
use App\Application\Actions\Order\GetOrdersCountAction;
use App\Application\Actions\Order\ListAllOrdersAction;
use App\Application\Actions\Order\ListOrdersSortedAction;
use App\Application\Actions\Order\OrderDeleteAction;
use App\Application\Actions\Order\UpdateOrderAction;
use App\Application\Actions\ShFileUpload\ShFileUploadAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Exception\HttpNotFoundException;

CONST JWT_SECRET = '!secRet$1234';

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

    /**
     * orders routes
     */
    $app->group('/orders', function (Group $group) {

        $group->get('/all', ListAllOrdersAction::class);

        $group->get('/sorted/{page}/{limit}/{orderby}/{direction}[/{where}]', ListOrdersSortedAction::class);

        $group->get('/number[/{where}]', GetOrdersCountAction::class);

        $group->get('/{id}', FindOrderByIdAction::class);

        $group->post('', CreateOrderAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

        $group->post('/{id}', UpdateOrderAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));;

        $group->delete('/{id}', OrderDeleteAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

    });

    /**
     * family routes
     */
    $app->group('/family', function (Group $group) {
        
        $group->get('/all', ListAllFamilyAction::class);

        $group->get('/sorted/{page}/{limit}/{orderby}/{direction}[/{where}]', ListFamilySortedAction::class);

        $group->get('/number[/{where}]', GetFamilyCountAction::class);

        $group->get('/{id}', FindFamilyByIdAction::class);

        $group->get('/order/{orderId}', FindFamiliesByOrderId::class);

        $group->post('', CreateFamilyAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

        $group->post('/{id}', UpdateFamilyAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));;

        $group->delete('/{id}', FamilyDeleteAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));
    });

    // bird routes
    $app->group('/birds', function (Group $group) {
        // $group->get('/all', ListAllBirdsAction::class);
        $group->get('/sorted/{lang}/{page}/{limit}/{orderby}/{direction}[/{where}]', FindBirdsSortedAction::class);
        $group->get('/number/{lang}[/{where}]', GetBirdsCountAction::class);
        $group->get('/{id}', FindBirdByIdAction::class);
        $group->post('', CreateBirdAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));
        $group->post('/{id}', UpdateBirdAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));
        $group->delete('/{id}', DeleteBirdAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));
    });

    // birds images upload
    $app->post('/fileupload/bird/{birdId}', ShFileUploadAction::class)->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

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

                $token = $builder->setSecret(JWT_SECRET)
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
