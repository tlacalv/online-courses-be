<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $with = ['instructor', 'students'];
    protected $fillable = [
      'name',
      'date',
      'duration',
      'instructor_id'
    ];

    public function instructor() {
      return $this->belongsTo(Instructor::class);
    }
    public function students() {
      return $this->belongsToMany(Student::class);
    }
}
