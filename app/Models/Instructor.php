<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    // protected $with=['courses'];
    // protected $with = ['courses'];

    protected $fillable = [
      'name',
      'age'
    ];

    public function courses() {
      return $this->hasMany(Course::class, 'instructor_id');
    }
}
