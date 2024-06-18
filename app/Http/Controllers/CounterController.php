<?php

namespace App\Http\Controllers;

use App\Models\Counter;


class CounterController extends Controller
{
    public function getCount()
    {
        $counter = Counter::incrementCount();
        return $counter->count;
    }
}
