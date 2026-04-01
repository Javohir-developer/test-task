<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('storage_id')->constrained('storages')->cascadeOnDelete();
            $table->integer('qty');
            $table->integer('available_qty')->default(0);
            $table->decimal('price', 12, 2); // Purchase price
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_items');
    }
};
