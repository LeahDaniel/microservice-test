<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function incrementCount(string $route)
    {
        $counter = self::firstOrCreate([
            'route_name' => $route
        ]);

        $counter->increment('count');
    }
}
