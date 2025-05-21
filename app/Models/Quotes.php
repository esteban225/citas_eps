<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    use HasFactory;
    protected $fillable = [

        'type', // 'general', 'specialist', etc.
        'date', // Date of the appointment
        'status', // 'pending', 'confirmed', 'canceled'
        'reason', // Reason for the appointment
        'observations', // Observations made by the doctor
        'doctors_id', // Doctor assigned to the appointment
        'userseps_id', // Userseps ID

    ];
    public function userseps()
    {
        return $this->belongsTo(Userseps::class, 'userseps_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctors_id');
    }
}
