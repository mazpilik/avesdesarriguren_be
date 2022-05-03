<?php

declare (strict_types = 1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ListFamilySortedAction
{
    private FamilyFinder $familyFinder;

    public function __construct(FamilyFinder $familyFinder)
    {
        $this->familyFinder = $familyFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = [];
        $data['page'] = $request->getAttribute('page');
        $data['limit'] = $request->getAttribute('limit');
        $data['orderby'] = $request->getAttribute('orderby') === 'date' ? 'id' : 'name';
        $data['direction'] = $request->getAttribute('direction');
        $data['where'] = $request->getAttribute('where');

        $family = $this->familyFinder->findSorted($data);

        $response->getBody()->write(json_encode($family));
        return $response->withHeader('Content-Type', 'application/json');
    }
}