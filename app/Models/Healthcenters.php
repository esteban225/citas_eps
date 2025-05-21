<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Healthcenters extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'address',
        'phone',
        'email',
        'type',
        'status',
    ];

    public function userseps()
    {
        return $this->hasMany(userseps::class, 'healthcenter_id');
    }

}


