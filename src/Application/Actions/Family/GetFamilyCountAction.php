<?php

declare(strict_types=1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GetFamilyCountAction
{
    private FamilyFinder $familyFinder;

    public function __construct(FamilyFinder $familyFinder)
    {
        $this->familyFinder = $familyFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $where = $request->getAttribute('where');
        if(!$where){
            $where = '';
        }
        $familyCount = $this->familyFinder->getFamilyCount($where);

        $response->getBody()->write(json_encode($familyCount));
        return $response->withHeader('Content-Type', 'application/json');
    }
}