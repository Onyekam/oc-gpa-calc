<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateExpressualsGpacalcGradesUsers extends Migration
{
    public function up()
    {
        Schema::create('expressuals_gpacalc_grades_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('grade_id');
            $table->integer('user_id');
            $table->primary(['grade_id','user_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('expressuals_gpacalc_grades_users');
    }
}
