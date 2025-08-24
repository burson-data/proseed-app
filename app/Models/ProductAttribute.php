<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'type', 'is_required', 'options'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];
}