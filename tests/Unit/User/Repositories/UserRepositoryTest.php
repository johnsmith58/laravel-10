<?php

declare(strict_types=1);

namespace Tests\Unit\User\Repositories;

use Mockery;
use App\Models\User;
use Tests\CleanUpTrait;
use PHPUnit\Framework\TestCase;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRepositoryTest extends TestCase
{
    use CleanUpTrait;
    use DatabaseTransactions;

    private Application $appMock;
    private User $userModelMock;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->appMock = Mockery::mock(Application::class);
        $this->userModelMock = Mockery::mock(User::class);

        $this->setUpAppMockException();

        $this->userRepository = new UserRepository($this->appMock);
    }

    private function setUpAppMockException(): void
    {
        $this->appMock->shouldReceive('make')
            ->with(User::class)
            ->andReturn($this->userModelMock);
    }

    public function testCanRegister(): void
    {
        $this->ensureUserModelMockCallSave();

        $result = $this->userRepository->register($this->userModelMock);

        $this->assertInstanceOf(User::class, $result);
    }

    protected function ensureUserModelMockCallSave(): void
    {
        $this->userModelMock
            ->shouldReceive('save')
            ->once()
            ->andReturn(new User());
    }

    public function testCanFindByEmail(): void
    {
        $this->ensureUserModelMockCallWhere();
        $this->ensureUserModelMockCallFromWhereQueryFirst();

        $result = $this->userRepository->findByEmail('mock@gmail.com');

        $this->assertInstanceOf(User::class, $result);
    }

    protected function ensureUserModelMockCallWhere(): void
    {
        $this->userModelMock
            ->shouldReceive('where')
            ->with('email', 'mock@gmail.com')
            ->andReturnSelf();
    }

    protected function ensureUserModelMockCallFromWhereQueryFirst(): void
    {
        $this->userModelMock
            ->shouldReceive('first')
            ->once()
            ->andReturn(new User());
    }
}
