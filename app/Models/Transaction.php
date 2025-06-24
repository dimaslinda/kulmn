<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\BranchScope; // Pastikan ini diimpor

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Menerapkan Global Scope untuk filtering berdasarkan cabang user
    protected static function booted(): void
    {
        static::addGlobalScope(new BranchScope);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}