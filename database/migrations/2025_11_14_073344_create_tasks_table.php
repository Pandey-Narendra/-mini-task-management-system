<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 150);
            $table->text('description')->nullable();

            // Enum status: pending, in-progress, completed
            $table->enum('status', ['pending', 'in-progress', 'completed'])
                  ->default('pending');

            $table->date('due_date');

            // User relationship
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
