<?php

namespace App\Traits;

trait CommonResponses
{
    public function resourceNotFound(string $resourceName)
    {
        return $this->sendJsonResponse([
            'message' => "{$resourceName} not found"
        ], 404);
    }

    public function actionNotAllowed()
    {
        return $this->sendJsonResponse([
            'message' => 'Action not allowed'
        ], 403);
    }

    public function successMessage(string $message)
    {
        return $this->sendJsonResponse([
            'message' => $message
        ]);
    }
}
