<?php namespace Expressuals\StudentInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndex extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('index')->nullable();
           
            

        });
    }

    public function down()
    {
        
       Schema::table('users', function ($table) {
            $table->dropColumn('index');
        });
        
    }

}