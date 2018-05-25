<?php namespace Expressuals\GpaCalc\Models;

use Model;

/**
 * Model
 */
class StudentGrade extends Model
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
    public $table = 'expressuals_gpacalc_grades_users';

    // protected $primaryKey = array('grade_id','user_id', 'crs_id');

    public $belongsTo = [
        'user' => [
        'Rainlab\User\Models\User', 
        'table' => 'expressuals_gpacalc_grades_users',
        'key' => 'user_id',
        'other_key' => 'id'
        ],
        'grade' => 'Expressuals\GpaCalc\Models\Grade',
        'course' => [ 
            'Expressuals\GpaCalc\Models\Course', 'key' => 'crs_id']
    ];

    // public $belongsToMany = [
    //     'user' => [
    //     'Rainlab\User\Models\User', 
    //     'table' => 'expressuals_gpacalc_grades_users',
    //     'key' => 'user_id',
    //     'other_key' => 'id'
    //     ],
    //     'grade' => 'Expressuals\GpaCalc\Models\Grade',
    //     'course' => [ 
    //         'Expressuals\GpaCalc\Models\Course', 'key' => 'crs_id']
    //     ];
}
