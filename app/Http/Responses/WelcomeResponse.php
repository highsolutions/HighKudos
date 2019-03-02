<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class WelcomeResponse implements Responsable
{

    public function toResponse($request)
    {
        return view('design.welcome', [
            // ...
        ]);
    }

}
