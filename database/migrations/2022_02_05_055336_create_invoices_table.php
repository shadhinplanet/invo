<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique();
            $table->foreignId('client_id')->constrained('clients','id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id');
            $table->string('amount');
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('email_sent', ['yes', 'no'])->default('no');
            $table->string('download_url');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
