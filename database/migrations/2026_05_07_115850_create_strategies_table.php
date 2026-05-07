<?php

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
        Schema::create('strategies', function (Blueprint $table) {

            $table->id();

            $table->string('target_role');

            $table->string('title');

            $table->text('recommendation');

            $table->string('prediction')->nullable();

            $table->decimal('confidence', 5, 2)
                ->nullable();

            $table->json('payload')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategies');
    }
};
