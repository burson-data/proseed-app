<?php

namespace App\Models;

use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'partner_id',
        'partner_name',
        'pic_name',
        'email',
        'phone_number',
        'address',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ProjectScope);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all of the transactions for the Partner.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}