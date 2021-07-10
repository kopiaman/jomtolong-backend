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
            $table->string('slug')->nullable();

            $table->string('tel');
            $table->boolean('tel_is_whatsapp')->default(1);

            $table->string('street');
            $table->string('state');
            $table->string('district');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();


            $table->text('info');
            //donaters or donatess or providers
            $table->enum('type', ['donatee', 'helper'])->default('donatee');
            $table->json('service')->nullable();

            $table->boolean('is_highlight')->default(0);
            $table->boolean('is_enough')->default(0);

            $table->enum('status', ['pending', 'approved', 'blocked'])->default('pending');

            $table->string('created_by')->nullable();

            $table->string('image')->nullable();

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
