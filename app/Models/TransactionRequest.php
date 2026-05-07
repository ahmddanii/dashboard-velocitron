<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRequest extends Model
{
    protected $fillable = [

        'requester_id',

        'request_type',

        'title',

        'description',

        'sales',

        'quantity',

        'discount',

        'shipping_days',

        'category',

        'segment',

        'region',

        'ship_mode',

        'prediction',

        'confidence',

        'status',

        'approved_by',

        'approved_at',

        'decision_note',
    ];

    protected $casts = [

        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
