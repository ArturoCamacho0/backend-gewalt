<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'user_id',
        'status'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_projects', 'project_id', 'client_id');
    }
}
