<?php

namespace App\Domain\Transaction\Enum;

enum TransactionTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}
