<?php

declare (strict_types = 1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyUpdater;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UpdateFamilyAction
{
    private FamilyUpdater $familyUpdater;

    public function __construct(FamilyUpdater $familyUpdater)
    {
        $this->familyUpdater = $familyUpdater;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $request_body = $request->getParsedBody();
        $id = (int)$request->getAttribute('id');
        $order_id = (int)$request_body['orderId'];
        $name = $request_body['name'];

        $resMessage = $this->familyUpdater->update($id, $order_id, $name);

        $response->getBody()->write(json_encode($resMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}