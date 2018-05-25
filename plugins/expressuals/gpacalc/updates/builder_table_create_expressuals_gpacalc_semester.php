<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateExpressualsGpacalcSemester extends Migration
{
    public function up()
    {
        Schema::create('expressuals_gpacalc_semester', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('level');
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_semester');
    }
}
