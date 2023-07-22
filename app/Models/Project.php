<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get all of the tasks for the Project
     *
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
}
