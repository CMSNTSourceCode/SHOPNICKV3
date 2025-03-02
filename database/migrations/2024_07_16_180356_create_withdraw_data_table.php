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
    Schema::create('withdraw_data', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('type');

      $table->integer('after_value');
      $table->integer('before_value');

      $table->string('name');
      $table->string('unit');

      $table->integer('amount');
      $table->integer('var_id');
      $table->integer('inv_id');

      $table->string('status')->default('Pending');
      $table->json('user_inputs');
      $table->string('admin_note')->nullable();

      $table->integer('user_id');
      $table->string('username');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('withdraw_data');
  }
};
