<?php namespace Expressuals\GpaCalc\Models;

use Model;

/**
 * Model
 */
class Grade extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'expressuals_gpacalc_grade';

    public $belongsToMany = [
        'users' => [
            'Rainlab\User\Models\User',
            'table' => 'expressuals_gpacalc_grades_users',
            'order' => 'name',
            'key' => 'user_id',
            'other' => 'id'
        ],
        'courses' => [
            'Expressuals\GpaCalc\Models\Course',
            'table' => 'expressuals_gpacalc_grades_users',
            'order' => 'course_name',
            'key'   => 'grade_id',
            'other_key' => 'crs_id' 
        ]
    ];

    public $belongsTo = [
        'semester' => 'Expressuals\GpaCalc\Models\Semester'
    ];
}
