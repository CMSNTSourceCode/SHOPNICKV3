<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('colla_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained();
      $table->string('username');
      $table->string('type');
      $table->integer('amount');
      $table->string('status');
      $table->string('reference')->nullable();
      $table->string('description');
      $table->integer('balance_before');
      $table->integer('balance_after');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('colla_transactions');
  }
};
