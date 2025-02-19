<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CommentService;

class CommentController extends Controller {

    public function __construct(private readonly CommentService $service) {}

    public function show(int $id): SuccessResponse {
        $comment = $this->service->show($id);

        return new SuccessResponse($comment);
    }

    public function store(CommentRequest $request): CreatedResponse {
        $comment = $this->service->store($request->validated());

        return new CreatedResponse($comment);
    }

    public function update(int $id, CommentRequest $data): SuccessResponse {
        $comment = $this->service->update($id, $data->validated());

        return new SuccessResponse($comment);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }
}
