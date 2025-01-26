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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrower_id');
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
            $table->decimal('application_fee', 15, 2);
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->comment('loan interest rate as a percentage');
            $table->integer('term')->comment('Loan term in months');
            $table->date('start_date');
            $table->date('due_date');
            $table->decimal('amount_to_be_paid', 15, 2)->default(0.00); // Dynamic total with penalties
            $table->decimal('paid_amount', 15, 2)->default(0.00); // Total paid
            $table->decimal('penalty_amount', 15, 2)->default(0.00); // Accumulated penalties
            $table->decimal('daily_penalty_rate', 5, 2)->default(0.05)->comment('Daily penalty rate as a percentage');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'ongoing', 'completed', 'paid'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('borrower_id')->references('id')->on('borrowers')->onDelete('cascade')->onUpdate('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
