<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcGrade2 extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->string('letter_grade', 2);
            $table->string('marks', 10);
            $table->string('grade_point', 5);
            $table->string('slug', 3)->change();
            $table->dropColumn('semester_id');
            $table->dropColumn('course_name');
            $table->dropColumn('course_hours');
            $table->dropColumn('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->dropColumn('letter_grade');
            $table->dropColumn('marks');
            $table->dropColumn('grade_point');
            $table->string('slug', 191)->change();
            $table->integer('semester_id')->nullable();
            $table->string('course_name', 191);
            $table->string('course_hours', 191);
            $table->integer('user_id');
        });
    }
}
