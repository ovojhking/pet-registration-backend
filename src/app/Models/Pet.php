<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'breed', 'date_of_birth', 'gender', 'is_age_estimated', 'is_dangerous'
    ];
}
