<?php

namespace App\Http\Controllers\Transference;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transference\MakeTransferenceRequest;
use App\Http\Resources\TransferenceResource;
use Domain\Wallet\DTO\MakeTransferenceDTO;
use Domain\Wallet\Service\MakeTransference as MakeTransferenceService;

class MakeTransference extends Controller
{
    public function __invoke(
        MakeTransferenceRequest $request,
        MakeTransferenceService $makeTransference
    ): TransferenceResource {
        return new TransferenceResource(
            $makeTransference->execute(MakeTransferenceDTO::fromValidatedRequest($request->validated()))
        );
    }
}
