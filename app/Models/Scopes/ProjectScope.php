<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProjectScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Jika ada proyek yang aktif di session
        if (session()->has('current_project_id')) {
            // Terapkan filter WHERE project_id secara otomatis
            $builder->where('project_id', session('current_project_id'));
        }
    }
}