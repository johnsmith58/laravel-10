<?php

declare(strict_types=1);

namespace Tests\Unit\User\Services;

use App\Http\Exceptions\NotFoundException;
use App\Http\Exceptions\UnAuthenticationException;
use Mockery;
use App\Models\User;
use Tests\CleanUpTrait;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use App\Repositories\UserRepositoryInterface;
use App\Services\HashService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserServiceTest extends TestCase
{
    use CleanUpTrait;
    use DatabaseTransactions;

    private UserService $userService;
    private HashService $hashService;

    private UserRepositoryInterface $userRepository;
    private User $userMockModel;

    protected function setUp(): void
    {
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

        $this->hashService = Mockery::mock(HashService::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->hashService
        );

        $this->userMockModel = Mockery::mock(User::class);
    }

    public function testUserServiceCanInitiate(): void
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
    }

    public function testCanRegister(): void
    {
        $this->ensureUserModelMockCallCreateToken();
        $this->ensureUserModelMockCanSetAttributeWithToken();
        $this->ensureUserRepositoryCallRegister();

        $result = $this->userService->register($this->userMockModel);

        $this->assertInstanceOf(User::class, $result);
    }

    protected function ensureUserRepositoryCallRegister(): void
    {
        $this->userRepository
            ->shouldReceive('register')
            ->once()
            ->andReturn($this->userMockModel);
    }

    protected function ensureUserModelMockCallCreateToken(): void
    {
        $stdClass = new stdClass();
        $stdClass->plainTextToken = 'mock_token';

        $this->userMockModel
            ->shouldReceive('createToken')
            ->once()
            ->andReturn($stdClass);
    }

    protected function ensureUserModelMockCanSetAttributeWithToken(): void
    {
        $this->userMockModel
            ->shouldReceive('setAttribute')
            ->with('token', Mockery::type('string'));
    }

    public function testCanLogin(): void
    {
        $loginData = [
            'email' => 'test@gmail.com',
            'password' => 'password'
        ];

        $this->ensureUserModelMockCanGetAttributeWithPassword();
        $this->ensureUserModelMockCallCreateToken();
        $this->ensureUserModelMockCanSetAttributeWithToken();

        $this->ensureUserRepositoryCallFindByEmail(true);

        $this->ensureHashServiceCallHasPasswordCheck(true);

        $result = $this->userService->login($loginData);

        $this->assertInstanceOf(User::class, $result);
    }

    public function testLoginCanHandleNotFound(): void
    {
        $loginData = [
            'email' => 'test@gmail.com',
            'password' => 'password'
        ];

        $this->ensureUserRepositoryCallFindByEmail(false);

        $this->expectException(NotFoundException::class);

        $this->userService->login($loginData);
    }

    public function testLoginCanHandleUnauthentication(): void
    {
        $loginData = [
            'email' => 'test@gmail.com',
            'password' => 'password'
        ];

        $this->ensureUserRepositoryCallFindByEmail(true);

        $this->ensureHashServiceCallHasPasswordCheck(false);

        $this->expectException(UnAuthenticationException::class);

        $this->userService->login($loginData);
    }

    protected function ensureUserRepositoryCallFindByEmail($shouldFound): void
    {
        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->andReturn($shouldFound ? $this->userMockModel : null);
    }

    protected function ensureUserModelMockCanGetAttributeWithPassword(): void
    {
        $this->userMockModel
            ->shouldReceive('getAttribute')
            ->with('password')->andReturn('password');
    }

    protected function ensureHashServiceCallHasPasswordCheck($shouldTrue): void
    {
        $this->hashService
            ->shouldReceive('hashPasswordCheck')
            ->once()
            ->andReturn($shouldTrue ? true : false);
    }
}
