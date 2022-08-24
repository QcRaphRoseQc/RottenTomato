<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id()->startingValue(101);
           
            $table->string('title');
            
            $table->integer('release_year');
            //length int(3)
            $table->integer('length');
            //description text
            $table->text('description');
            //rating varchar(5)
            $table->string('rating');

            $table->decimal('score', 5, 1)->nullable();


            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');



            $table->string('special_features');
            //image varchar(40)
            $table->string('image');
            //created_at DATETIME
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
        Schema::dropIfExists('films');
    }
}
