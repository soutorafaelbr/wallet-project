<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\User|null $payer
 * @property-read \App\Models\User|null $payee
 */
class Transference extends Model
{
    /** @use HasFactory<\Database\Factories\TransferenceFactory> */
    use HasFactory;

    protected $fillable = ['amount', 'payer_id', 'payee_id'];

    protected $casts = [
        'amount' => 'float',
        'payer_id' => 'int',
        'payee_id' => 'int',
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}
