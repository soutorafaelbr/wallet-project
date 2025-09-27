<?php

use App\Domain\Transaction\Enum\TransactionTypeEnum;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer_id');
            $table->unsignedBigInteger('payee_id');
            $table->decimal('amount', 8, 2)->default(1);
            $table->enum('type', [TransactionTypeEnum::CREDIT, TransactionTypeEnum::DEBIT]);
            $table->foreignIdFor(User::class, 'payer_id');
            $table->foreignIdFor(User::class, 'payee_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
