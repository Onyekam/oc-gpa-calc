<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcCourses extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_courses', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_courses', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
