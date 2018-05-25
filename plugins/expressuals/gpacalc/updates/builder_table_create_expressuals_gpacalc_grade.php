<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateExpressualsGpacalcGrade extends Migration
{
    public function up()
    {
        Schema::create('expressuals_gpacalc_grade', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('letter_grade', 2);
            $table->string('marks', 10);
            $table->string('grade_point', 5);
            $table->string('slug', 10);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_grade');
    }
}
