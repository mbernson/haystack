<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('application_id');
            $table->string('title');
            
            // 0       Emergency: system is unusable
            // 1       Alert: action must be taken immediately
            // 2       Critical: critical conditions
            // 3       Error: error conditions
            // 4       Warning: warning conditions
            // 5       Notice: normal but significant condition
            // 6       Informational: informational messages
            // 7       Debug: debug-level messages
            /*
            $table->enum('level', [
                'emergency',
                'alert',
                'critical',
                'error',
                'warning',
                'notice',
                'info',
                'debug'
            ]);
            */
            
            $table->enum('status', [
                'open',
                'resolved',
                'closed'
            ]);
            $table->integer('occurences')->default(1);
            
            $table->timestamps();

            $table->primary(['application_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('incidents');
    }
}
