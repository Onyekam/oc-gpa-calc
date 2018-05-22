<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateExpressualsGpacalcCourses extends Migration
{
    public function up()
    {
        Schema::create('expressuals_gpacalc_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('course_name');
            $table->string('course_hours');
            $table->integer('semester_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_courses');
    }
}
