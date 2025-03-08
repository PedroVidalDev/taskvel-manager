<?php

namespace Tests\Unit;

use App\Http\Repositories\CommentRepository;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use PHPUnit\Framework\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommentService $service;
    private $commentRepositoryMock;

    protected function setUp(): void {
        parent::setUp();

        $this->commentRepositoryMock = \Mockery::mock(CommentRepository::class);
        $this->service = new CommentService($this->commentRepositoryMock);
    }

    private array $commentData = [
        'id' => 1,
        'content' => 'This is a comment',
        'user_id' => 1,
        'task_id' => 1,
        'created_at' => '2021-10-10 10:00:00',
        'updated_at' => '2021-10-10 10:00:00'
    ];

    private function createCommentMock() {
        $commentMock = \Mockery::mock(Comment::class);
        $commentMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($this->commentData['id']);
        $commentMock->shouldReceive('getAttribute')
            ->with('content')
            ->andReturn($this->commentData['content']);
        $commentMock->shouldReceive('getAttribute')
            ->with('user_id')
            ->andReturn($this->commentData['user_id']);
        $commentMock->shouldReceive('getAttribute')
            ->with('task_id')
            ->andReturn($this->commentData['task_id']);

        return $commentMock;
    }

    public function test_should_show_one_comment(): void {
        $commentMock = $this->createCommentMock();

        $this->commentRepositoryMock->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($commentMock);

        $this->commentRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', $this->commentData['id'])
            ->andReturn(true);

        $comment = $this->service->show(1);

        $this->assertEquals($this->commentData['id'], $comment->id);
        $this->assertEquals($this->commentData['content'], $comment->content);
        $this->assertEquals($this->commentData['user_id'], $comment->user_id);
        $this->assertEquals($this->commentData['task_id'], $comment->task_id);
    }

    public function test_should_fail_in_show_one_comment(): void {
        $this->commentRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', $this->commentData['id'])
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);
        $this->service->show(1);
    }

    public function test_should_delete_one_comment(): void {
        $this->commentRepositoryMock->shouldReceive('destroy')
            ->once()
            ->with(1);

        $this->commentRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', $this->commentData['id'])
            ->andReturn(true);

        $this->service->destroy(1);

        $this->expectNotToPerformAssertions();
    }

    public function test_should_fail_in_delete_one_comment(): void {
        $this->commentRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->with('id', $this->commentData['id'])
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);
        $this->service->destroy(1);
    }

    public function test_should_create_one_comment() {
        $commentMock = $this->createCommentMock();

        $this->commentRepositoryMock
            ->shouldReceive('store')
            ->once()
            ->with($this->commentData)
            ->andReturn($commentMock);

        $result = $this->service->store($this->commentData);

        $this->assertEquals($this->commentData['id'], $result->id);
        $this->assertEquals($this->commentData['content'], $result->content);
        $this->assertEquals($this->commentData['user_id'], $result->user_id);
        $this->assertEquals($this->commentData['task_id'], $result->task_id);

        $this->assertInstanceOf(CommentResource::class, $result);
    }

    public function test_should_update_one_comment() {
        $commentMock = $this->createCommentMock();

        $this->commentRepositoryMock
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($commentMock);

        $this->commentRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($commentMock, $this->commentData)
            ->andReturn($commentMock);

        $result = $this->service->update(1, $this->commentData);

        $this->assertEquals($this->commentData['id'], $result->id);
        $this->assertEquals($this->commentData['content'], $result->content);
        $this->assertEquals($this->commentData['user_id'], $result->user_id);
        $this->assertEquals($this->commentData['task_id'], $result->task_id);
    }
}
