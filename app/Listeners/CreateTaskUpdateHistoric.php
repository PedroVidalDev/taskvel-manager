<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\TaskUpdateHistoric;

class CreateTaskUpdateHistoric {
    public function __construct() {
        //
    }

    public function handle(TaskUpdated $event): void {
        TaskUpdateHistoric::create([
            'task_id' => $event->task->id,
        ]);
    }
}
