<?php

namespace Domain\Wallet\Exception;

use Exception;
use Illuminate\Http\JsonResponse;

class CompanyCannotTransferFunds extends Exception
{
    public function __construct()
    {
        $this->message = 'Company cannot transfer funds';
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json(
            ['message' => $this->getMessage()], JsonResponse::HTTP_FORBIDDEN
        );
    }
}
