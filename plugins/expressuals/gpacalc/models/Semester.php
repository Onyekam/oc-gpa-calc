<?php namespace Expressuals\GpaCalc\Models;

use Model;

/**
 * Model
 */
class Semester extends Model
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
    public $table = 'expressuals_gpacalc_semester';

    public $belongsToMany = [
        'users' => [
            'Rainlab\User\Models\User',
            'table' => 'expressuals_gpacalc_grades_users',
            'order' => 'name',
            'key'      => 'user_id',
            'otherKey' => 'sem_id' 
        ]
    ];

    // public $belongsTo = [
    //     'user' => 'Rainlab\User\Models\User'
    // ];

    public $hasMany = [
        'courses' => 'Expressuals\GpaCalc\Models\Course'
    ];

    // public $hasMany = [
    //     'grades' => 'Expressuals\GpaCalc\Models\Grade'
    // ];
}
