<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class AuthenticationAPITest extends TestCase
{
    use RefreshDatabase;

    protected function actingAuthenticationUser(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['MyApp']
        );
    }

    public function testCanAuthenticateUser(): void
    {
        $this->actingAuthenticationUser();

        //authentication api
        $response = $this->getJson("api/articles");

        $response->assertStatus(200);
    }

    public function testCanHandleUnautheicateUser(): void
    {
        $response = $this->getJson("api/articles");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }
}