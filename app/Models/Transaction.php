<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    const string SUCCESS = 'success';
    const string FAILED = 'failed';
    const string PENDING = 'pending';
    const int FEE = 500;
    const int MAXIMUM_SWAP = 50000000;
    const int MINIMUM_SWAP = 1000;

    const array STATUS = [
        self::SUCCESS,
        self::FAILED,
        self::PENDING,
    ];

    protected $fillable = [
        'amount',
        'fee',
        'source_id',
        'destination_id',
        'status',
        'uuid'
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(CardNumber::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(CardNumber::class);
    }

}
