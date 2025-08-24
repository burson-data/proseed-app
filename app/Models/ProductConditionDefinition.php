<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductConditionDefinition extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'name', 'type', 'options'];
    protected $casts = ['options' => 'array'];
}