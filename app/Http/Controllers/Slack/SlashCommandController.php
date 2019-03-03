<?php

namespace App\Http\Controllers\Slack;

use App\Http\Controllers\Controller;
use App\Services\Slack\SlashCommandReceiver;

class SlashCommandController extends Controller
{

    public function index()
    {
        return SlashCommandReceiver::get(request()->all());
    }

}
