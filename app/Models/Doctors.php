<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    use HasFactory;
    protected $fillable = [
        'identificationType', // Type of identification (e.g., 'CC', 'TI', 'CE', etc.)
        'identificationNumber', // Unique identification number
        'license_number', // Medical license number
        'address', // Address of the doctor
        'name', // Name of the doctor
        'specialty', // specialty of the doctor (e.g., 'cardiology', 'pediatrics', etc.)
        'phone', // Phone number of the doctor
        'email', // Email of the doctor
        'status', // Status of the doctor (e.g., 'active', 'inactive')
    ];
    public function Specialtydoctors()
    {
        return $this->belongsTo(Specialtydoctors::class, 'specialty_id');
    }

    public function quotes()
    {
        return $this->hasMany(Quotes::class, 'doctors_id');
    }
}
