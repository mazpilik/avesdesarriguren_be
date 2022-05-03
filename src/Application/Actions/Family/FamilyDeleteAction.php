<?php

declare (strict_types = 1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyDeleter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FamilyDeleteAction
{
    private FamilyDeleter $familyDeleter;

    public function __construct(FamilyDeleter $familyDeleter)
    {
        $this->familyDeleter = $familyDeleter;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $id = (int)$request->getAttribute('id');

        $resMessage = $this->familyDeleter->delete($id);

        $response->getBody()->write(json_encode($resMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}