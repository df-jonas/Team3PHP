<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeTicket extends Model
{
    protected $table = 'TypeTicket';

    protected $primaryKey = 'TypeTicketID';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = ['TypeTicketID'];
    protected $fillable = ['Name', 'Price', 'ComfortClass', 'LastUpdated'];

}
