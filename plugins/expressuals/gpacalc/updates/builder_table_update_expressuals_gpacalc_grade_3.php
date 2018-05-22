<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcGrade3 extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->string('slug', 10)->change();
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_grade', function($table)
        {
            $table->string('slug', 3)->change();
        });
    }
}
