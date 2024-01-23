<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'card_number',
    ];


    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(Transaction::class, 'source_id');
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Transaction::class, 'destination_id');
    }
}
