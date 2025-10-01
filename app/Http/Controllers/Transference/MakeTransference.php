<?php

namespace App\Http\Controllers\Transference;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Http\Resources\TransferenceResource;
use Domain\Transaction\Action\MakeTransference as MakeTransferenceService;
use Domain\Transaction\DTO\MakeTransferenceDTO;

class MakeTransference extends Controller
{
    public function __invoke(
        MakeTransferenceRequest $request,
        MakeTransferenceService $makeTransference
    ): TransferenceResource
    {
        return new TransferenceResource(
            $makeTransference->execute(MakeTransferenceDTO::fromValidatedRequest($request->validated()))
        );
    }
}
