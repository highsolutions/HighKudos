<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class NavigationComposer
{

    /**
     * Create a new general composer.
     *
     * @return void
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            // ...
        ]);
    }

}
