<?php

declare (strict_types = 1);

namespace App\Application\Actions\ShFileUpload;

// response request
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ShFileUploadAction
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        foreach ($uploadedFiles as $uploadedFile) {
          if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
              $filename = $uploadedFile->getClientFilename();
              // move file to public/uploads
              $uploadedFile->moveTo('./images/' . $filename);
              $response->getBody()->write(json_encode(['File uploaded successfully']));
          } else {
              $response->getBody()->write(json_encode(['File upload failed']));
          } 
        }
        $response->withHeader('Content-Type', 'application/json');
        return $response;
    }
}