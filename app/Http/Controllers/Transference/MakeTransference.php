<?php

namespace App\Http\Controllers\Transference;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MakeTransference extends Controller
{
    public function __invoke(MakeTransferenceRequest $request): JsonResponse
    {
        $transaction = Transaction::query()->create($request->validated());

        return response()->json($transaction->toArray(), JsonResponse::HTTP_CREATED);
    }
}
