<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcGradesUsers extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_grades_users', function($table)
        {
            $table->dropPrimary(['grade_id','user_id']);
            $table->integer('sem_id');
            $table->primary(['grade_id','user_id','sem_id']);
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_grades_users', function($table)
        {
            $table->dropPrimary(['grade_id','user_id','sem_id']);
            $table->dropColumn('sem_id');
            $table->primary(['grade_id','user_id']);
        });
    }
}
