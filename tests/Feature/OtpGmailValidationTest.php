<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OtpGmailValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_otp_start_rejects_non_gmail(): void
    {
        Queue::fake();

        $response = $this->from(route('apply.start'))->post(route('apply.otp.store'), [
            'email' => 'user@yahoo.com',
            'phone' => '0912345678',
            'desired_amount' => 10_000_000,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_otp_start_accepts_gmail(): void
    {
        Queue::fake();

        $response = $this->from(route('apply.start'))->post(route('apply.otp.store'), [
            'email' => 'someone+tag@gmail.com',
            'phone' => '0912345678',
            'desired_amount' => 10_000_000,
        ]);

        $response->assertRedirect(route('apply.verify.show'));
        $response->assertSessionHas('otp_email', 'someone+tag@gmail.com');
    }
}
