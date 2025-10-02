<?php

namespace Domain\Wallet\Exception;

use Illuminate\Http\JsonResponse;

class OperationFailed extends \Exception
{
    public function __construct()
    {
        $this->message = 'Operation failed';
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message], JsonResponse::HTTP_FORBIDDEN);
    }
}
