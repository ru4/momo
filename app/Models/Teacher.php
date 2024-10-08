<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Teacher extends Model
{
    use HasFactory;

    protected $perPage = 10;
    protected $fillable = [
        'name',
        'photo',
        'phone_number',
        'bio'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    // Many-to-Many Relationship with School
    public function schools()
    {
        return $this->belongsToMany(School::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($teacher) {
            // Delete the associated user if exists
            $teacher->user->delete();

        });
    }

    // Scope a query to search for teachers name.
    public function scopeSearch(Builder $query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
