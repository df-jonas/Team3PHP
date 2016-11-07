<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $connection = 'nmbs_mysql';
    protected $table = 'Discount';

    protected $primaryKey = 'DiscountID';
    public $timestamps = false;


    protected $guarded = ['ObjectID'];
    protected $fillable = ['Name', 'Amount'];

    protected $appends = ['Station'];
}
