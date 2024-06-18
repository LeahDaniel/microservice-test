<?php

namespace App\Http\Controllers;

use App\Models\Counter;

abstract class CounterController
{
    public function getCount(string $routeName)
    {
        return Counter::firstWhere('route_name', $routeName)->count;
    }
}
