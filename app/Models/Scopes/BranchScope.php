<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Hanya terapkan scope jika user login dan bukan admin
        if (Auth::check() && Auth::user()->email !== 'admin@admin.com') {
            $builder->where('branch_id', Auth::user()->branch_id);
        }
    }
}
