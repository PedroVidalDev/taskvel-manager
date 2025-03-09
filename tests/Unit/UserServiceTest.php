<?php

namespace Tests\Unit;

use App\Http\Repositories\UserRepository;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $service;
    private $userRepositoryMock;

    protected function setUp(): void {
        parent::setUp();

        $this->userRepositoryMock = \Mockery::mock(UserRepository::class);
        $this->service = new UserService($this->userRepositoryMock);
    }

    private array $userData = [
        'name' => 'Pedro Vidal',
        'email' => 'pedrovidal@gmail.com',
        'password' => '123456'
    ];

    private function createUserMock() {
        $userMock = \Mockery::mock(User::class);
        $userMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);
        $userMock->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn($this->userData['name']);
        $userMock->shouldReceive('getAttribute')
            ->with('email')
            ->andReturn($this->userData['email']);
        $userMock->shouldReceive('getAttribute')
            ->with('password')
            ->andReturn('hashed_password');

        return $userMock;
    }

    public function test_should_create_one_user(): void {
        Hash::shouldReceive('make')->andReturn('hashed_password');
        $userMock = $this->createUserMock();

        $this->userRepositoryMock->shouldReceive('createConfirmEmailUrl')
            ->once()
            ->with(\Mockery::any(), \Mockery::any())
            ->andReturnNull();

        $this->userRepositoryMock->shouldReceive('store')
            ->once()
            ->with([
                'name' => $this->userData['name'],
                'email' => $this->userData['email'],
                'password' => 'hashed_password'
            ])
            ->andReturn($userMock);

        $result = $this->service->store($this->userData);

        $this->assertEquals($this->userData['name'], $result->name);
        $this->assertEquals($this->userData['email'], $result->email);
    }

    public function test_should_login(): void {
        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with([
                'email' => $this->userData['email'],
                'password' => $this->userData['password']
            ])
            ->andReturn('token');

        $result = $this->service->login([
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ]);

        $this->assertEquals('token', $result['token']);
    }

    public function test_should_not_login(): void {
        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with([
                'email' => $this->userData['email'],
                'password' => $this->userData['password']
            ])
            ->andReturn(null);

        $this->expectException(JWTException::class);
        $this->expectExceptionMessage("Invalid credentials");

        $this->service->login([
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ]);
    }

    public function test_should_update_a_user(): void {
        Hash::shouldReceive('make')->andReturn('hashed_password');

        $userMock = $this->createUserMock();

        $this->userRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', 1)
            ->andReturn(true);

        $this->userRepositoryMock->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($userMock);

        $this->userRepositoryMock->shouldReceive('update')
            ->once()
            ->with($userMock, [
                'name' => $this->userData['name'],
                'email' => $this->userData['email'],
                'password' => 'hashed_password'
            ])
            ->andReturn($userMock);

        $result = $this->service->update(1, $this->userData);

        $this->assertEquals($this->userData['name'], $result->name);
        $this->assertEquals($this->userData['email'], $result->email);
    }

    public function test_should_not_update_a_user(): void {
        $this->userRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', 1)
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->service->update(1, $this->userData);
    }

    public function test_should_delete_a_user(): void {
        $this->userRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', 1)
            ->andReturn(true);

        $this->userRepositoryMock->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andReturnNull();

        $this->service->destroy(1);

        $this->assertTrue(true);
    }

    public function test_should_not_delete_a_user(): void {
        $this->userRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', 1)
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->service->destroy(1);
    }
}
