<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtasks_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_task_id');
            $table->unsignedBigInteger('sub_task_id');
            $table->timestamps();

            $table->foreign('main_task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('sub_task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtasks_relation');
    }
};
