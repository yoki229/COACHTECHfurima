<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->unique()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->unique()->cascadeOnDelete();
            $table->timestamps();

            // 複合ユニーク制約
            $table->unique(['user_id', 'product_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
