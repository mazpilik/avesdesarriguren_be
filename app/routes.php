<?php

declare(strict_types=1);

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
        
        /**
         * get all orders without pagination
         */
        $group->get('/all', function (Request $request, Response $response) {
            $db = $this->get(PDO::class);
            $sth = $db->prepare('SELECT * FROM orders');
            $sth->execute();
            $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($orders));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * sort orders by id or by name and paginated
         * 
         * @param int $page
         * @param int $limit
         * @param string $orderBy
         * @param string $direction
         * @param string $where
         * @return Response
         */
        $group->get('/sorted/{page}/{limit}/{orderby}/{direction}[/{where}]', function (Request $request, Response $response) {
            $page = $request->getAttribute('page');
            $limit = $request->getAttribute('limit');
            $orderby = $request->getAttribute('orderby') === 'date' ? 'id' : 'name';
            $direction = $request->getAttribute('direction');
            $where = $request->getAttribute('where');

            $where_condition = '';
            $where_value = '';
            $offset = ($page - 1) * $limit;

            if($where !== 'EMPTY_WHERE') {
                $where_value = "%$where%";
                $where_condition = ' WHERE name LIKE :name';
            }

            $db = $this->get(PDO::class);
            $sth = $db->prepare('SELECT * FROM orders'.$where_condition.' ORDER BY :orderBy :direction LIMIT :limit OFFSET :offset');
            $sth->bindParam(':orderBy', $orderby, PDO::PARAM_STR);
            $sth->bindParam(':direction', $direction, PDO::PARAM_STR);
            $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
            $sth->bindParam(':offset', $offset, PDO::PARAM_INT);
            if($where !== 'EMPTY_WHERE') {
                $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
            }
            $sth->execute();
            $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($orders));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * get count of orders
         */
        $group->get('/number[/{where}]', function (Request $request, Response $response) {
            $where = $request->getAttribute('where');
            $where_condition = '';

            $db = $this->get(PDO::class);

            if(!empty($where)){
                $where_value = "%$where%";
                $where_condition = ' WHERE name LIKE :name';
            }

            $sth = $db->prepare('SELECT COUNT(*) FROM orders'.$where_condition);
            
            if(!empty($where)){
                $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
            }
            
            $sth->execute();
            $number = $sth->fetchColumn();

            $response->getBody()->write(json_encode($number));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * get order by id
         * 
         * @param int $id
         * @return Response
         */
        $group->get('/{id}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $db = $this->get(PDO::class);
            $sth = $db->prepare('SELECT * FROM orders WHERE id = :id');
            $sth->bindParam('id', $id);
            $sth->execute();
            $order = $sth->fetch(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($order));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * update order
         * 
         * @param int $id
         * @return Response
         */
        $group->post('/{id}', function (Request $request, Response $response) {
            try {
                $request_body = $request->getParsedBody();
                $id = $request->getAttribute('id');
                $name = $request_body['name'];
                $db = $this->get(PDO::class);
                $sth = $db->prepare('UPDATE orders SET name = :name WHERE id = :id');
                $sth->bindParam('id', $id);
                $sth->bindParam('name', $name);
                $sth->execute();

                $response->getBody()->write(json_encode($request_body));
                return $response->withHeader('Content-Type', 'application/json');
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode($e->getMessage()));
                return $response->withHeader('Content-Type', 'application/json');
            }
        })->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));;

        /**
         * create order
         */
        $group->post('',function (Request $request, Response $response) {
            $db = $this->get(PDO::class);
        
            // get name from request body
            $request_body = $request->getParsedBody();

            try {
                // insert name to orders table and return id
                $sth = $db->prepare('INSERT INTO orders (name) VALUES (:name)');
                $sth->bindParam('name', $request_body['name']);
                $sth->execute();
                $response->getBody()->write(json_encode('CREATE_SUCCESS'));
                return $response->withHeader('Content-Type', 'application/json');
            } catch (PDOException $e) {
                // get error status code
                $status = $e->getCode();
                if($status == 23000) {
                    $response->getBody()->write(json_encode('CREATE_ERROR_DUPLICATED'));
                    return $response->withHeader('Content-Type', 'application/json');
                }
                // devolver error
                $response->getBody()->write(json_encode('CREATE_ERROR'));
                return $response->withStatus(500);
            }
        })->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

        /**
         * delete order
         * 
         * @param int $id
         * @return Response
         */
        $group->delete('/{id}', function (Request $request, Response $response) {
            try {
                $id = $request->getAttribute('id');
                $db = $this->get(PDO::class);
                $sth = $db->prepare('DELETE FROM orders WHERE id = :id');
                $sth->bindParam('id', $id);
                $sth->execute();
    
                $response->getBody()->write(json_encode('DELETE_SUCCESS'));
                return $response->withHeader('Content-Type', 'application/json');
            } catch (PDOException $e) {
                $response-getBody()->write(json_encode('DELETE_ERROR'));
                return $response->withStatus(500);
            }
        })->add(\PsrJwt\Factory\JwtMiddleware::html(JWT_SECRET, 'jwt', 'Authorization Failed'));

        // find order by name like
        $group->get('/find/{name}', function (Request $request, Response $response) {
            $name = $request->getAttribute('name');
            $wildCard = '%'.$name.'%';
            $db = $this->get(PDO::class);
            $sth = $db->prepare('SELECT * FROM orders WHERE name LIKE :name');
            $sth->bindParam('name', $wildCard);
            $sth->execute();
            $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($orders));
            return $response->withHeader('Content-Type', 'application/json');
        });
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
