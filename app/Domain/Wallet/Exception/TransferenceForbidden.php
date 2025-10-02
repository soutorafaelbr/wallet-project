<?php

namespace Domain\Transference\Exception;

use Illuminate\Http\JsonResponse;

class TransferenceForbidden extends \Exception
{
    public function __construct()
    {
        $this->message = 'Error on authorization';
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => 'Transference forbidden'], JsonResponse::HTTP_FORBIDDEN);
    }
}
