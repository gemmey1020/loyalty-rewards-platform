<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'invoice_date',
        'invoice_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userPoints()
    {
        return $this->hasOne(UserPoint::class);
    }

    public function editHistory()
    {
        return $this->hasMany(InvoiceEditHistory::class)->orderBy('created_at', 'desc');
    }
}
