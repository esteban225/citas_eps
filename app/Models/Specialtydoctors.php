<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialtydoctors extends Model
{
    use HasFactory;
    protected $fillable = [
        'specialty', // Description of the specialty
        'doctors_id', // Foreign key to the doctors table
    ];

    public function doctors()
    {
        return $this->hasMany(Doctors::class, 'specialty_id');
    }

}
