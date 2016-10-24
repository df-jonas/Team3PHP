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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Staff';

    /**
     * Edit Table priamryKey.
     *
     * @var string
     */
    protected $primaryKey = 'StaffID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
