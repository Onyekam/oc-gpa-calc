<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateExpressualsGpacalcPasswords extends Migration
{
    public function up()
    {
        Schema::create('expressuals_gpacalc_passwords', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('password');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_passwords');
    }
}
