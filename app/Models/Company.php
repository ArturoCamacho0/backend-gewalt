<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    // Relationships
    public function clients()
    {
        return $this->hasMany(Client::class, 'company_id');
    }
}
