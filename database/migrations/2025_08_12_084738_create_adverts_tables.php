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
        Schema::create('advert_adverts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('title');
            $table->integer('price');
            $table->text('address');
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('advert_categories')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::create('advert_advert_values', function (Blueprint $table) {
            $table->unsignedBigInteger('advert_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');
            $table->primary(['advert_id', 'attribute_id']);

            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('advert_attributes')->onDelete('cascade');
        });

        Schema::create('advert_advert_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advert_id');
            $table->string('file');

            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_adverts');
        Schema::dropIfExists('advert_advert_values');
        Schema::dropIfExists('advert_advert_photos');
    }
};
