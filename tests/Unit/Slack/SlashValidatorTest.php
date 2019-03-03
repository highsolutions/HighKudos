<?php

namespace Tests\Unit;

use App\Services\Slack\SlashCommandValidator;
use Tests\TestCase;

class SlashValidatorTest extends TestCase
{

    protected function assertPositiveValidation($message)
    {
        $this->assertTrue(SlashCommandValidator::check($message));
    }

    protected function assertFalseValidation($message)
    {
        $this->assertFalse(SlashCommandValidator::check($message));
    }

    /**
     * @test
     */
    public function simple_proper_message()
    {
        $message = 'dla <@U025D6EPH|adam> za super integrację ze slackiem #zaangażowanie';
        $this->assertPositiveValidation($message);
    }

    /**
     * @test
     */
    public function message_with_two_receivers()
    {
        $message = 'dla <@U025D6EPH|adam> <@U025D6EPH|adam2> za super integrację ze slackiem #zaangażowanie';
        $this->assertPositiveValidation($message);
    }

    /**
     * @test
     */
    public function message_with_two_values()
    {
        $message = 'dla <@U025D6EPH|adam> za super integrację ze slackiem #zaangażowanie #rozwoj';
        $this->assertPositiveValidation($message);
    }

    /**
     * @test
     */
    public function message_without_values()
    {
        $message = 'dla <@U025D6EPH|adam> za super integrację ze slackiem';
        $this->assertPositiveValidation($message);
    }

    /**
     * @test
     */
    public function message_without_receiver()
    {
        $message = 'dla Adama za super integrację ze slackiem';
        $this->assertFalseValidation($message);
    }

    /**
     * @test
     */
    public function message_without_message()
    {
        $message = 'dla Adama za #zaangażowanie';
        $this->assertFalseValidation($message);
    }

    /**
     * @test
     */
    public function message_with_wrong_pattern()
    {
        $message = 'za super ciasto';
        $this->assertFalseValidation($message);
    }
    
}
