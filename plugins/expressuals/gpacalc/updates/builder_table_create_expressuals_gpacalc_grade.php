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
            $table->integer('semester_id')->nullable();
            $table->string('course_name');
            $table->string('course_hours');
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_grade');
    }
}
