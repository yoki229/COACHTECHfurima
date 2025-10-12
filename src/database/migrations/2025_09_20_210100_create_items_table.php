<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{

    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            $table->string('name', 255);
            $table->string('image', 255);
            $table->text('description');
            $table->integer('price');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::dropIfExists('items');
    }
}
