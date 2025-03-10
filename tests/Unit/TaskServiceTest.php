<?php

namespace Tests\Unit;

use App\Http\Repositories\TaskRepository;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{

    use RefreshDatabase;

    private TaskService $taskService;
    private $taskRepositoryMock;

    protected function setUp(): void {
        parent::setUp();

        $this->taskRepositoryMock = \Mockery::mock(TaskRepository::class);
        $this->taskService = new TaskService($this->taskRepositoryMock);
    }

    private array $taskData = [
        'title' => 'This is a task',
        'description' => 'This is a task description',
        'user_id' => 1,
        'created_at' => '2021-10-10 10:00:00',
        'updated_at' => '2021-10-10 10:00:00'
    ];

    private function createTaskMock() {
        $taskMock = \Mockery::mock(Task::class);
        $taskMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);
        $taskMock->shouldReceive('getAttribute')
            ->with('title')
            ->andReturn($this->taskData['title']);
        $taskMock->shouldReceive('getAttribute')
            ->with('description')
            ->andReturn($this->taskData['description']);
        $taskMock->shouldReceive('getAttribute')
            ->with('user_id')
            ->andReturn($this->taskData['user_id']);
        $taskMock->shouldReceive('getAttribute')
            ->with('created_at')
            ->andReturn($this->taskData['created_at']);
        $taskMock->shouldReceive('getAttribute')
            ->with('updated_at')
            ->andReturn($this->taskData['updated_at']);

        return $taskMock;
    }

    public function test_should_get_all_tasks_by_user(): void {
        $this->taskRepositoryMock->shouldReceive('index')
            ->once()
            ->andReturn(Collection::make([$this->createTaskMock()]));

        $response = $this->taskService->index(1);

        $this->assertEquals(1, $response[0]->id);
        $this->assertEquals($this->taskData["title"], $response[0]->title);
        $this->assertEquals($this->taskData["description"], $response[0]->description);
    }

    public function test_should_get_task_by_id(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $this->taskRepositoryMock->shouldReceive('show')
            ->once()
            ->andReturn($this->createTaskMock());

        $response = $this->taskService->show(1);

        $this->assertEquals(1, $response->id);
        $this->assertEquals($this->taskData["title"], $response->title);
        $this->assertEquals($this->taskData["description"], $response->description);
    }

    public function test_should_not_get_task_by_id(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->show(1);
    }

    public function test_should_get_comments_by_task_id(): void {
        $taskMock = $this->createTaskMock();

        $taskMock->shouldReceive('getAttribute')
            ->with('comments')
            ->andReturn(Collection::make([]));

        $this->taskRepositoryMock->shouldReceive('show')
            ->once()
            ->andReturn($taskMock);

        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $response = $this->taskService->showComments(1);

        $this->assertEquals(0, $response->count());
        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
    }

    public function test_should_not_get_comments_by_task_id(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->showComments(1);
    }

    public function test_should_store_task(): void {
        $this->taskRepositoryMock->shouldReceive('store')
            ->once()
            ->andReturn($this->createTaskMock());

        $response = $this->taskService->store($this->taskData);

        $this->assertEquals(1, $response->id);
        $this->assertEquals($this->taskData["title"], $response->title);
        $this->assertEquals($this->taskData["description"], $response->description);
    }

    public function test_should_store_subtask(): void {
        $mainTaskMock = $this->createTaskMock();
        $subTaskMock = \Mockery::mock(Task::class);
        $subTaskMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(2);
        $subTaskMock->shouldReceive('getAttribute')
            ->with('title')
            ->andReturn($this->taskData['title']);
        $subTaskMock->shouldReceive('getAttribute')
            ->with('description')
            ->andReturn($this->taskData['description']);

        $subtasksRelationMock = \Mockery::mock(BelongsToMany::class);
        $subtasksRelationMock->shouldReceive('attach')
            ->once()
            ->with(2);

        $mainTaskMock->shouldReceive('subtasks')
            ->andReturn($subtasksRelationMock);

        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $this->taskRepositoryMock->shouldReceive('show')
            ->once()
            ->andReturn($mainTaskMock);

        $this->taskRepositoryMock->shouldReceive('store')
            ->once()
            ->with($this->taskData)
            ->andReturn($subTaskMock);

        $response = $this->taskService->storeSubtask(1, $this->taskData);

        $this->assertEquals($this->taskData["title"], $response->title);
        $this->assertEquals($this->taskData["description"], $response->description);
    }

    public function test_should_not_store_subtask(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->storeSubtask(1, $this->taskData);
    }

    public function test_should_get_all_subtasks_by_task_id(): void {
        $taskMock = $this->createTaskMock();

        $taskMock->shouldReceive('getAttribute')
            ->with('subtasks')
            ->andReturn(Collection::make([$this->createTaskMock()]));

        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $this->taskRepositoryMock->shouldReceive('show')
            ->once()
            ->andReturn($taskMock);

        $response = $this->taskService->getAllSubtasks(1);

        $this->assertEquals(1, $response[0]->id);
        $this->assertEquals($this->taskData["title"], $response[0]->title);
        $this->assertEquals($this->taskData["description"], $response[0]->description);
    }

    public function test_should_not_get_all_subtasks_by_task_id(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->getAllSubtasks(1);
    }

    public function test_should_update_task(): void {
        $taskMock = $this->createTaskMock();

        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $this->taskRepositoryMock->shouldReceive('show')
            ->once()
            ->andReturn($taskMock);

        $this->taskRepositoryMock->shouldReceive('update')
            ->once()
            ->with($taskMock, $this->taskData)
            ->andReturn($taskMock);

        $response = $this->taskService->update(1, $this->taskData);

        $this->assertEquals(1, $response->id);
        $this->assertEquals($this->taskData["title"], $response->title);
        $this->assertEquals($this->taskData["description"], $response->description);
    }

    public function test_should_not_update_task(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->update(1, $this->taskData);
    }

    public function test_should_destroy_task(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(true);

        $this->taskRepositoryMock->shouldReceive('destroy')
            ->once()
            ->with(1);

        $this->taskService->destroy(1);

        $this->assertTrue(true);
    }

    public function test_should_not_destroy_task(): void {
        $this->taskRepositoryMock->shouldReceive('existsByColumn')
            ->once()
            ->andReturn(false);

        $this->expectException(EntityNotFoundException::class);

        $this->taskService->destroy(1);
    }


}
