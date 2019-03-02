<?php

namespace App\Http\Controllers\Slack;

use App\Http\Controllers\Controller;

class SlashCommandController extends Controller
{

    public function index()
    {
        $all = request()->all();
        \Log::info($all);
        dd($all);
    }

}
