<?php

namespace Tests\Unit;

use App\Support\VnPhone;
use PHPUnit\Framework\TestCase;

class VnPhoneTest extends TestCase
{
    public function test_normalizes_and_validates(): void
    {
        $this->assertTrue(VnPhone::isValidNormalized(VnPhone::normalize('0912345678')));
        $this->assertTrue(VnPhone::isValidNormalized(VnPhone::normalize('+84 912 345 678')));
        $this->assertTrue(VnPhone::isValidNormalized(VnPhone::normalize('84912345678')));
        $this->assertFalse(VnPhone::isValidNormalized(VnPhone::normalize('0212345678')));
        $this->assertFalse(VnPhone::isValidNormalized(VnPhone::normalize('081234567'))); // too short
    }
}
