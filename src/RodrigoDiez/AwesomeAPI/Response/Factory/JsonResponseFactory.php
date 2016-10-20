<?php

namespace RodrigoDiez\AwesomeAPI\Response\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseFactory
{
    public function createSuccessfull($message = 'Success', $data = null)
    {
        return $this->createResponse(200, $message, $data);
    }

    public function createServerError($message = 'Internal server error', $data = null)
    {
        return $this->createResponse(500, $message, $data);
    }

    public function createUnauthorised($message = 'Unauthorised')
    {
        return $this->createResponse(403, $message);
    }

    public function createBadRequest($message = 'Bad request')
    {
        return $this->createResponse(400, $message);
    }

    private function createResponse($status_code, $message, $data = null)
    {
        return new JsonResponse(['message' => $message, 'data' => $data], $status_code);
    }
}
