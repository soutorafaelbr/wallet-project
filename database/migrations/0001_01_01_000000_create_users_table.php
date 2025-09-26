<?php

use App\Models\Document;
use App\Models\Wallet;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('wallet_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('type', [UserTypeEnum::PF, UserTypeEnum::PJ])
                ->default('PF');
            $table->foreignIdFor(Document::class);
            $table->foreignIdFor(Wallet::class);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
