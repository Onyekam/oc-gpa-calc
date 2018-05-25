<?php namespace Expressuals\StudentInfo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('student_id')->nullable();
            $table->string('gpa')->nullable();
            

        });
    }

    public function down()
    {
        
       Schema::table('users', function ($table) {
            $table->dropColumn('student_id');
            $table->dropColumn('gpa');
        });
        
    }

}