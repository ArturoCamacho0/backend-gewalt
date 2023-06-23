<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'client_id';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'company_id',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'client_services', 'client_id', 'service_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'client_projects', 'client_id', 'project_id');
    }
}
