<?php

namespace Tests\Unit;

use App\Http\Repositories\CommentRepository;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    private array $commentMock = [
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
            ->andReturn($this->commentMock['id']);
        $commentMock->shouldReceive('getAttribute')
            ->with('content')
            ->andReturn($this->commentMock['content']);
        $commentMock->shouldReceive('getAttribute')
            ->with('user_id')
            ->andReturn($this->commentMock['user_id']);
        $commentMock->shouldReceive('getAttribute')
            ->with('task_id')
            ->andReturn($this->commentMock['task_id']);

        return $commentMock;
    }

    public function test_should_show_all_comments(): void {
        $commentMock = $this->createCommentMock();

        $this->commentRepositoryMock->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($commentMock);

        $comment = $this->service->show(1);

        $this->assertEquals($this->commentMock['id'], $comment->id);
        $this->assertEquals($this->commentMock['content'], $comment->content);
        $this->assertEquals($this->commentMock['user_id'], $comment->user_id);
        $this->assertEquals($this->commentMock['task_id'], $comment->task_id);
    }

    public function test_should_delete_one_comment(): void {
        $this->commentRepositoryMock->shouldReceive('destroy')
            ->once()
            ->with(1);

        $this->service->destroy(1);

        $this->expectNotToPerformAssertions();
    }

    public function test_should_create_one_comment() {
        $commentMock = $this->createCommentMock();

        $this->commentRepositoryMock
            ->shouldReceive('store')
            ->once()
            ->with($this->commentMock)
            ->andReturn($commentMock);

        $result = $this->service->store($this->commentMock);

        $this->assertEquals($this->commentMock['id'], $result->id);
        $this->assertEquals($this->commentMock['content'], $result->content);
        $this->assertEquals($this->commentMock['user_id'], $result->user_id);
        $this->assertEquals($this->commentMock['task_id'], $result->task_id);

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
            ->with($commentMock, $this->commentMock)
            ->andReturn($commentMock);

        $result = $this->service->update(1, $this->commentMock);

        $this->assertEquals($this->commentMock['id'], $result->id);
        $this->assertEquals($this->commentMock['content'], $result->content);
        $this->assertEquals($this->commentMock['user_id'], $result->user_id);
        $this->assertEquals($this->commentMock['task_id'], $result->task_id);
    }
}
