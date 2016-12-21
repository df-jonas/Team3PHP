<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationWithAddress extends Model
{
    protected $table = 'Station';

    protected $primaryKey = 'StationID';
    public $timestamps = false;
    public $incrementing = false;

    protected $hidden = ['AddressID'];
    protected $guarded = ['StationID'];
    protected $fillable = ['AddressID', 'Name', 'LastUpdated', 'CoX', 'CoY'];

    protected $appends = ['Address'];

    public function getAddressAttribute()
    {
        return Address::find($this->AddressID);
    }
}
