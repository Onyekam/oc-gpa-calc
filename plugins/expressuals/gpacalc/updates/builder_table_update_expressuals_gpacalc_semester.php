<?php namespace Expressuals\GpaCalc\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateExpressualsGpacalcSemester extends Migration
{
    public function up()
    {
        Schema::table('expressuals_gpacalc_semester', function($table)
        {
            $table->string('slug');
            $table->increments('id')->unsigned(false)->change();
            $table->string('title')->change();
            $table->string('level')->change();
        });
    }
    
    public function down()
    {
        Schema::table('expressuals_gpacalc_semester', function($table)
        {
            $table->dropColumn('slug');
            $table->increments('id')->unsigned()->change();
            $table->string('title', 191)->change();
            $table->string('level', 191)->change();
        });
    }
}
