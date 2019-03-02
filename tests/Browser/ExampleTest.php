<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function has_welcome_page_text_template()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Template');
        });
    }
}
