<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->integer('qty');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_refunds');
    }
};
