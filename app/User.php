<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Password', 'remember_token', 'Api_token',
    ];

    protected $connection = 'nmbs_mysql';

    protected $table = 'Staff';

    protected $primaryKey = 'StaffID';

    public $timestamps = false;

    protected $fillable = ['AdressID', 'StationID', 'FirstName', 'LastName', 'UserName', 'Password', 'Rights', 'BirthDate', 'Email', 'Api_token'];
    protected $guarded = ['StaffID'];

    public function getAuthPassword()
    {
        return $this->Password;
    }
}
