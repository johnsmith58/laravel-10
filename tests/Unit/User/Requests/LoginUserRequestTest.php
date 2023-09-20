<?php

declare(strict_types=1);

namespace Tests\Unit\User\Requests;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;
use Tests\CleanUpTrait;

class LoginUserRequestTest extends TestCase
{
    use CleanUpTrait;

    private LoginUserRequest $loginUserRequest;

    protected function setUp(): void
    {
        $this->loginUserRequest = new LoginUserRequest();
    }

    public function testRequestCanInitiate(): void
    {
        $this->assertInstanceOf(LoginUserRequest::class, $this->loginUserRequest);
        $this->assertInstanceOf(FormRequest::class, $this->loginUserRequest);
    }

    public function testShouldReturnRules(): void
    {
        $rules = $this->loginUserRequest->rules();

        $this->assertCount(2, $rules);

        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $this->assertContains('required', $rules['email']);
        $this->assertContains('email', $rules['email']);

        $this->assertContains('required', $rules['password']);
        $this->assertContains('string', $rules['password']);
        $this->assertContains('min:6', $rules['password']);
        $this->assertContains('max:20', $rules['password']);
    }
}
