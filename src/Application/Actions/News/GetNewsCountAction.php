<?php

declare (strict_types = 1);

namespace App\Application\Actions\News;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\News\Service\NewsFinder;

final class GetNewsCountAction
{
    private NewsFinder $newsFinder;

    public function __construct(NewsFinder $newsFinder)
    {
        $this->newsFinder = $newsFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $lang = $request->getAttribute('lang');
        $where = '';
        if ($request->getAttribute('where') !== null) {
          $where = $request->getAttribute('where');
        }

        
        $count = $this->newsFinder->getNewsCount($lang, $where);

        $response->getBody()->write(json_encode($count));
        return $response->withHeader('Content-Type', 'application/json');

    }
}