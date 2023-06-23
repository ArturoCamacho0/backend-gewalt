<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Relationships
    public function services()
    {
        return $this->hasMany(Service::class, 'supplier_id');
    }
}
