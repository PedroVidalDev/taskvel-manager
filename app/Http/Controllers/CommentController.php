<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CommentService;

class CommentController extends Controller {

    public function __construct(private readonly CommentService $service) {}

    public function index(): SuccessResponse {
        $comments = $this->service->index();

        return new SuccessResponse($comments);
    }

    public function show(int $id): SuccessResponse {
        $comment = $this->service->show($id);

        return new SuccessResponse($comment);
    }

    public function store(CommentRequest $request): CreatedResponse {
        $comment = $this->service->store($request->validated());

        return new CreatedResponse($comment);
    }

    public function update(int $id, UpdateCommentRequest $data): SuccessResponse {
        $comment = $this->service->update($id, $data->validated());

        return new SuccessResponse($comment);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }
}
