<?php namespace Expressuals\GpaCalc\Models;

use Model;

/**
 * Model
 */
class Password extends Model
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
    public $table = 'expressuals_gpacalc_passwords';

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];
}
