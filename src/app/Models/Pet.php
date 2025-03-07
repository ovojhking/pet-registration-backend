<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PetType;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'pet_type_id', 'breed_id', 'date_of_birth', 'is_age_estimated', 'gender', 'is_dangerous'
    ];

    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }
}
