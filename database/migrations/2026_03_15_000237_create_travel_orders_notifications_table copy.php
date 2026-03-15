<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_orders_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['aprovado', 'cancelado']);
            $table->text('message');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('travel_orders_notifications');
    }
};
