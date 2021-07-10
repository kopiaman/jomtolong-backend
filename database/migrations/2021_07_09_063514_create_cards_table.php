<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            $table->enum('type', ['donatee', 'helper'])->default('donatee')->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();

            $table->string('tel');
            $table->boolean('tel_is_whatsapp')->default(1);

            $table->string('street');
            $table->string('state')->index();
            $table->string('district')->index();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();


            $table->text('info');
            //donaters or donatess or providers

            $table->json('service')->nullable();
            $table->boolean('is_highlight')->default(0)->index();
            $table->boolean('is_enough')->default(0)->index();



            $table->string('created_by')->nullable();
            $table->string('image')->nullable();
            $table->string('code');
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
        Schema::dropIfExists('cards');
    }
}
