<?php

namespace Tests\Unit;

use App\Http\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

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

    private array $userMock = [
        'id' => 1,
        'name' => 'Pedro Vidal',
        'email' => 'pedrovidal@gmail.com'
    ];

    public function test_example(): void {

    }
}
