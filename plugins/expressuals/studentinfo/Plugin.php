<?php namespace Expressuals\StudentInfo;

use System\Classes\PluginBase;
use Rainlab\User\Controllers\Users as UsersController;
use Rainlab\User\Models\User as UserModel;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }


    public function boot(){
        UserModel::extend(function($model){
            $model->addFillable([
                'student_id',
                'semester',
                'gpa'
            ]);

            $model->hasManyThrough['semesters'] = [
                'Expressuals\GpaCalc\Models\Semester',
                'through' => 'Expressuals\GpaCalc\Models\Course'
            ];

            $model->belongsToMany['courses'] = [ 
                'Expressuals\GpaCalc\Models\Course',
                'table' => 'expressuals_gpacalc_grades_users',
                'key' =>'user_id',
                'otherKey' => 'crs_id',
                'order' => 'semester_id'
            ];

            $model->belongsToMany['grades'] = [ 
                'Expressuals\GpaCalc\Models\Grade', 
                'table' => 'expressuals_gpacalc_grades_users',
                'key' =>'user_id',
                'otherKey' => 'grade_id'
            ];


        });

        UsersController::extendFormFields(function($form, $model, $context){
            $form->addTabFields([
                'student_id' => [
    				'label' => 'Student ID',
    				'type' => 'text',
    				'tab' => 'Extra Details'
                ],
                'semester' => [
    				'label' => 'Semester',
    				'type' => 'text',
    				'tab' => 'Extra Details'
                ],
                'gpa' => [
    				'label' => 'GPA',
    				'type' => 'text',
    				'tab' => 'Extra Details'
                ]
            ]);
        });
    }
}
