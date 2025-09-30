<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = ['amount', 'payer_id', 'payee_id'];

    protected $casts = [
        'amount' => 'float',
        'payer_id' => 'int',
        'payee_id' => 'int',
    ];
}
