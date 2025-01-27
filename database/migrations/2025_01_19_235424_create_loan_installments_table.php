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
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->integer('installed_amount');
            $table->date('paid_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade')->onUpdate('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
    }
};
