<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function is_welcome_page_working()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
}
