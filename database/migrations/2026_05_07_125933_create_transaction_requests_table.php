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
        Schema::create('transaction_requests', function (Blueprint $table) {

            $table->id();

            /*
        |--------------------------------------------------------------------------
        | Request Creator
        |--------------------------------------------------------------------------
        */

            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
        |--------------------------------------------------------------------------
        | Request Information
        |--------------------------------------------------------------------------
        */

            $table->string('request_type');

            $table->string('title');

            $table->text('description')
                ->nullable();

            /*
        |--------------------------------------------------------------------------
        | DSS Input
        |--------------------------------------------------------------------------
        */

            $table->decimal('sales', 12, 2)
                ->nullable();

            $table->integer('quantity')
                ->nullable();

            $table->decimal('discount', 5, 2)
                ->nullable();

            $table->integer('shipping_days')
                ->nullable();

            $table->string('category')
                ->nullable();

            $table->string('segment')
                ->nullable();

            $table->string('region')
                ->nullable();

            $table->string('ship_mode')
                ->nullable();

            /*
        |--------------------------------------------------------------------------
        | DSS Result
        |--------------------------------------------------------------------------
        */

            $table->string('prediction')
                ->nullable();

            $table->decimal('confidence', 5, 2)
                ->nullable();

            /*
        |--------------------------------------------------------------------------
        | Approval Flow
        |--------------------------------------------------------------------------
        */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();

            $table->text('decision_note')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_requests');
    }
};
