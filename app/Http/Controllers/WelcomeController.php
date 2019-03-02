<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\WelcomeResponse;

class WelcomeController extends Controller
{

	public function index()
	{
        return new WelcomeResponse;
	}

}
