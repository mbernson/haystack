<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('application_id');
            $table->integer('incident_id')->nullable();
            $table->string('type');

            $table->string('title')->nullable();

            $table->timestamp('created_at');

            $table->foreign(['incident_id', 'application_id'])
                ->references(['id', 'application_id'])->on('incidents');
            $table->index('application_id');
        });
        /*
        Schema::create('exceptions', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('application_id');
            $table->string('title');
            $table->string('code');
            $table->string('file');
            $table->integer('line');
            
            $table->primary(['application_id', 'id']);
        });

        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
