<?php

declare (strict_types = 1);

namespace App\Application\Actions\News;

// response request
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use NewsCreatorRepository
use App\Domain\News\Service\NewsCreator;

final class NewsFileUploadAction
{
    private NewsCreator $newsCreator;
    private string $responseMessage;

    public function __construct(NewsCreator $newsCreator)
    {
        $this->newsCreator = $newsCreator;
        $this->responseMessage = 'SUCCESS_FILE_UPLOAD';
    }
    
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            // get news id from parameters
            $news_id = (int) $args['newsId'];

            // check if news id exists
            if($news_id) {
                // check if news exists
                if($this->newsCreator->checkNewsExists($news_id)) {
                    // get files from request
                    $uploadedFiles = $request->getUploadedFiles();
                    
                    // upload more all files
                    foreach ($uploadedFiles as $uploadedFile) {
                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            $filename = $uploadedFile->getClientFilename();
                            
                            // move file to public/uploads
                            $uploadedFile->moveTo('./images/news/' . $filename);
                            $this->responseMessage = 'SUCCESS_FILE_UPLOAD';

                            // bind file to news in the database
                            $this->newsCreator->bindImage($news_id, $filename);

                        } else {
                            $this->responseMessage = 'ERROR_FILE_UPLOAD';
                        } 
                    } 
                } else {
                    // news does not exist  
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