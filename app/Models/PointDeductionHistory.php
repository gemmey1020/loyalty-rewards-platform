<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointDeductionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'user_point_id',
        'points_deducted',
        'reason',
        'deducted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function userPoint()
    {
        return $this->belongsTo(UserPoint::class);
    }

    public function deductedBy()
    {
        return $this->belongsTo(User::class, 'deducted_by');
    }
}
