<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function incrementCount()
    {
        $counter = self::firstOrCreate();

        $counter->increment('count');

        return $counter;
    }
}
