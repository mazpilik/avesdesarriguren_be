<?php

declare (strict_types = 1);

namespace App\Application\Actions\ShFileUpload;

// response request
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use BirdCreatorRepository
use App\Domain\Bird\Service\BirdCreator;

final class ShFileUploadAction
{
    private BirdCreator $birdCreator;
    private string $responseMessage;

    public function __construct(BirdCreator $birdCreator)
    {
        $this->birdCreator = $birdCreator;
        $this->responseMessage = 'SUCCESS_FILE_UPLOAD';
    }
    
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            // get bird id from parameters
            $bird_id = (int) $args['birdId'];

            // check if bird id exists
            if($bird_id) {
                // check if bird exists
                if($this->birdCreator->checkBirdExists($bird_id)) {
                    // get files from request
                    $uploadedFiles = $request->getUploadedFiles();
                    
                    // upload more all files
                    foreach ($uploadedFiles as $uploadedFile) {
                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            $filename = $uploadedFile->getClientFilename();
                            
                            // move file to public/uploads
                            $uploadedFile->moveTo('./images/birds/' . $filename);
                            $this->responseMessage = 'SUCCESS_FILE_UPLOAD';

                            // bind file to bird in the database
                            $this->birdCreator->bindImage($bird_id, $filename);

                        } else {
                            $this->responseMessage = 'ERROR_FILE_UPLOAD';
                        } 
                    } 
                } else {
                    // bird does not exist  
                    throw new \Exception('ERROR_NOT_EXISTING_RECORD');
                }
            } else {
                throw new \Exception('ERROR_WRONG_PARAMETERS');
            }
        } catch (\Throwable $th) {
            // check sql error code
            if($th->getCode() === '23000') {
                // overwritte response body
                $this->responseMessage = 'ERROR_DUPLICATE_RECORD';
            } else {
                $this->responseMessage = $th->getMessage();
            }
            $response->getBody()->write(json_encode($this->responseMessage));
            $response->withHeader('Content-Type', 'application/json');
            return $response->withStatus(500);
        }
        $response->getBody()->write(json_encode($this->responseMessage));
        $response->withHeader('Content-Type', 'application/json');
        return $response;
    }
}