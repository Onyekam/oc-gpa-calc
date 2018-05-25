<?php namespace Expressuals\GpaCalc\Models;

use Model;

/**
 * Model
 */
class Course extends Model
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
    public $table = 'expressuals_gpacalc_courses';

    public $belongsTo = [
        'semester' => 'Expressuals\GpaCalc\Models\Semester'
    ];
    
     public $belongsToMany = [
        'users' => [
            'Rainlab\User\Models\User',
            'table' => 'expressuals_gpacalc_grades_users',
            'order' => 'name',
            'key'   => 'user_id',
            'other_key' => 'id'

        ],
        'grades' => [
            'Expressuals\GpaCalc\Models\Grade',
            'table' => 'expressuals_gpacalc_grades_users',
            'order' => 'letter_grade',
            'key'   => 'crs_id',
            'other_key' => 'grade_id' 
        ]
    ];
}
