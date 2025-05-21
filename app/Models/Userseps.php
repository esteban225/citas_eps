<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userseps extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'identificationType',
        'identificationNumber',
        'phone',
        'status',
        'role',
        'address',
        'healthcenters_id'
    ];

    public function healthcenter()
    {
        return $this->belongsTo(Healthcenters::class, 'healthcenters_id');
    }
    public function quotes()
    {
        return $this->hasMany(Quotes::class, 'userseps_id');
    }
}
