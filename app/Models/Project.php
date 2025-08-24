<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'key_attribute_name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    // Di dalam kelas Project
    public function productAttributes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
    // Di dalam kelas Project
    public function conditionDefinitions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductConditionDefinition::class);
    }
}