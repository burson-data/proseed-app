<?php

namespace App\Models;

use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'product_id',
        'product_name',
        'key_attribute_value',
        'status',
        'attributes',
        'conditions',
        'product_image',
        'current_transaction_id',
        'is_active',
    ];

    protected $casts = [
        'attributes' => 'array',
        'conditions' => 'array',
        'is_archived' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ProjectScope);
    }

    /**
     * Get the product's dynamic display name.
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->product_name} ({$this->key_attribute_value})",
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
