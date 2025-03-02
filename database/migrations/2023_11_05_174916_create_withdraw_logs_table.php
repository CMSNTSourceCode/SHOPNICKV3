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
    Schema::create('withdraw_logs', function (Blueprint $table) {
      $table->id();
      $table->string('unit');
      $table->string('value');
      $table->string('status')->default('Pending');
      $table->string('user_note')->nullable();
      $table->string('admin_note')->nullable();
      $table->foreignId('user_id')->constrained('users');
      $table->string('username');
      $table->double('current_balance', 20, 2)->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('withdraw_logs');
  }
};
