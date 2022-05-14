<?php

declare (strict_types = 1);

namespace App\Application\Actions\News;

// get response and request from Slim
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\News\Service\NewsFinder;

final class FindNewsByIdAction
{
    private NewsFinder $newsFinder;

    public function __construct(NewsFinder $newsFinder)
    {
        $this->newsFinder = $newsFinder;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {

        try {
            $id = (int) $args['id'];
            $news = $this->newsFinder->findByid($id);

            $response->getBody()->write(json_encode($news));

            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Throwable $th) {
            // get status code
            $status_code = $th->getCode();
            $error_message = $th->getMessage();

            $response->getBody()->write(json_encode($error_message));
            $response->withHeader('Content-Type', 'application/json');
            return $response->withStatus(500);
        }
    }
}