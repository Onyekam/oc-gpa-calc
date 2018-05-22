<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcGrade extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->string('slug');
            $table->increments('id')->unsigned(false)->change();
            $table->string('course_name')->change();
            $table->string('course_hours')->change();
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->dropColumn('slug');
            $table->increments('id')->unsigned()->change();
            $table->string('course_name', 191)->change();
            $table->string('course_hours', 191)->change();
        });
    }
}
