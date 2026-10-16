<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetup extends Model
{
    protected $fillable = [
        'name',
        'image_type',
        'image',
        'route',
        'default',
    ];

    public static function getDefaultImage($type)
    {
        return self::where('image_type', $type)
            ->where('default', 1)
            ->select('image')
            ->first();
    }
}
