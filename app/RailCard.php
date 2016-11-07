<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RailCard extends Model
{
    protected $connection = 'nmbs_mysql';
    protected $table = 'RailCard';

    protected $primaryKey = 'RailCardID';
    public $timestamps = false;


    protected $guarded = ['RailCardID'];

    protected $appends = ['Subscriptions'];

    public function getSubscriptionsAttribute()
    {
        return Subscription::find($this->RailCardID);
    }

}
