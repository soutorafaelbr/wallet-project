<?php

namespace Domain\Transaction\Exception;

use Illuminate\Http\JsonResponse;

class InsufficientFunds extends \Exception
{
    public function __construct()
    {
        $this->message = 'Insufficient funds';
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message], JsonResponse::HTTP_FORBIDDEN);
    }
}
