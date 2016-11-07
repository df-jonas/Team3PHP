<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'nmbs_mysql';
    protected $table = 'Subscription';

    protected $primaryKey = 'SubscriptionID';
    public $timestamps = false;

    protected $hidden = ['RailcardID', 'RouteID', 'DiscountID'];
    protected $guarded = ['SubscriptionID'];
    protected $fillable = ['RailcardID', 'RouteID', 'DiscountID', 'ValidFrom', 'ValidUntil'];

    protected $appends = ['Railcard', 'Route', 'Discount'];

    public function getRailcardAttribute()
    {
        return RailCard::find($this->RailcardID);
    }

    public function getRouteAttribute()
    {
        return Route::find($this->RouteID);
    }

    public function getDiscountAttribute()
    {
        return Discount::find($this->DiscountID);
    }
}
