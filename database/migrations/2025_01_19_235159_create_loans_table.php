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
            $table->integer('application_fee');
            $table->integer('loan_amount');
            $table->decimal('interest_rate', 5, 2)->comment('loan interest rate as a percentage');
            $table->integer('term')->comment('Loan term in months');
            $table->enum('repayment_mode', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->date('start_date');
            $table->date('due_date');
            $table->integer('amount_to_be_paid')->default(0); // Dynamic total with penalties
            $table->integer('paid_amount')->default(0); // Total paid
            $table->integer('penalty_amount')->default(0); // Accumulated penalties
            $table->decimal('daily_penalty_rate', 5, 2)->default(0.05)->comment('Daily penalty rate as a percentage');
            $table->string('sponsor_name');
            $table->string('sponsor_phone')->unique();
            $table->text('collaterals')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'rejected', 'paid', 'overdue'])->default('pending');
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
