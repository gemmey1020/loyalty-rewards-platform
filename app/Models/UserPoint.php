<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $fillable = [
        'user_id',
        'point_id',
        'invoice_id',
        'cashback',
        'points',
        'points_deducted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'point_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getNetPointsAttribute()
    {
        return $this->points - $this->points_deducted;
    }

    public function getNetCashbackAttribute()
    {
        if ($this->points <= 0) {
            return 0;
        }

        return ($this->net_points / $this->points) * $this->cashback;
    }
}
