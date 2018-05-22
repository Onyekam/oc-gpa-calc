<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcGradesUsers3 extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_grades_users', function($table)
        {
            $table->renameColumn('course_id', 'crs_id');
            $table->primary(['grade_id','user_id','crs_id']);
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_grades_users', function($table)
        {
            $table->dropPrimary(['grade_id','user_id','crs_id']);
            $table->renameColumn('crs_id', 'course_id');
        });
    }
}
