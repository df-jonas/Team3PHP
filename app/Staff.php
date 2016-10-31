<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'nmbs_mysql';

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'staff';

    /**
     * Edit default primary key
     *
     * @var string
     */
    protected $primaryKey = 'staffID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'adressID', 'stationID', 'firstName', 'lastName', 'userName', 'password', 'rights'
    ];
}
