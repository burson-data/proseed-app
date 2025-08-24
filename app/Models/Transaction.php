<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\ProjectScope;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', // <-- Ditambahkan
        'transaction_id',
        'product_id',
        'partner_id',
        'user_id',
        'borrow_date',
        'estimated_return_date',
        'actual_return_date',
        'status',
        'purpose',
        'notes',
        'return_notes',
        'news_links',
        'is_unreturned',
        'loan_receipt_status',
        'loan_receipt_path',
        'loan_upload_token',
        'return_receipt_status',
        'return_receipt_path',
        'return_upload_token',
        'return_conditions',
    ];


    protected static function booted(): void
    {
        static::addGlobalScope(new ProjectScope);
    }

    protected $casts = [
    // ... cast lain ...
    'return_conditions' => 'array', // <-- Tambahkan ini
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function consultants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class);
    }
    
}