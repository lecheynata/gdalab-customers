<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni', 45);
            $table->integer('id_reg');
            $table->integer('id_com');
            $table->string('email', 120)->unique();
            $table->string('name', 45);
            $table->string('last_name', 45);
            $table->string('address', 255)->nullable();
            $table->timestamp('date_reg', $presicion = 0)->default(now());
            $table->enum('status', ['A', 'I', 'trash'])->default('A');

            $table->index('dni');
            $table->primary(['dni', 'id_reg', 'id_com']);

            // $table->foreign('id_com')->references('id_com')->on('communes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
