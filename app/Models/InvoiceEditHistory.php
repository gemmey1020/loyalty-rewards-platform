<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceEditHistory extends Model
{
    protected $fillable = [
        'invoice_id',
        'edited_by',
        'invoice_number',
        'invoice_total',
        'invoice_date',
        'changes',
        'action',
    ];

    protected $casts = [
        'changes' => 'array',
        'invoice_date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
