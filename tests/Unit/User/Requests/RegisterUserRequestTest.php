<?php

declare(strict_types=1);

namespace Tests\Unit\User\Requests;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;
use Tests\CleanUpTrait;

class RegisterUserRequestTest extends TestCase
{
    use CleanUpTrait;

    private RegisterUserRequest $registerUserRequest;

    protected function setUp(): void
    {
        $this->registerUserRequest = new RegisterUserRequest();
    }

    public function testRequestCanInitiate(): void
    {
        $this->assertInstanceOf(RegisterUserRequest::class, $this->registerUserRequest);
        $this->assertInstanceOf(FormRequest::class, $this->registerUserRequest);
    }

    public function testShouldReturnRules(): void
    {
        $rules = $this->registerUserRequest->rules();

        $this->assertCount(4, $rules);

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('confirm_password', $rules);

        $this->assertContains('required', $rules['name']);
        $this->assertContains('string', $rules['name']);
        $this->assertContains('min:5', $rules['name']);
        $this->assertContains('max:40', $rules['name']);

        $this->assertContains('required', $rules['email']);
        $this->assertContains('email', $rules['email']);

        $this->assertContains('required', $rules['password']);
        $this->assertContains('string', $rules['password']);
        $this->assertContains('min:6', $rules['password']);
        $this->assertContains('max:20', $rules['password']);

        $this->assertContains('required', $rules['confirm_password']);
        $this->assertContains('same:password', $rules['confirm_password']);
        $this->assertContains('string', $rules['confirm_password']);
        $this->assertContains('min:6', $rules['confirm_password']);
        $this->assertContains('max:20', $rules['confirm_password']);
    }
}
