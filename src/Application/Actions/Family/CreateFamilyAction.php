<?php

declare (strict_types = 1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyCreator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateFamilyAction
{
    private FamilyCreator $familyCreator;

    public function __construct(FamilyCreator $familyCreator)
    {
        $this->familyCreator = $familyCreator;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $order_id = (int) $data['orderId'];
            $name = $data['name'];

            $return_message = $this->familyCreator->create($order_id,$name);
            
            $response->getBody()->write(json_encode('SUCCESS_FAMILY_CREATION'));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            // get status code
            $status_code = $th->getCode();
            $error_message = 'ERROR_FAMILY_CREATION';
            if($status_code == 23000){
                $error_message = 'ERROR_DUPLICATE_ENTRY';
            }

            $response->getBody()->write(json_encode($error_message));
            $response->withHeader('Content-Type', 'application/json');
            return $response->withStatus(500);
        }
        
    }
}