<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;

    protected $table = 'breeds';
    protected $fillable = ['name', 'pet_type_id'];

    public function pets()
    {
        return $this->hasMany(Pet::class, 'breed_id');
    }
}

