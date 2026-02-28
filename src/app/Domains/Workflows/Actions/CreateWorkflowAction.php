<?php

namespace App\Domains\Workflows\Actions;

use App\Domains\Workflows\DTOs\WorkflowData;
use App\Domains\Workflows\Models\Workflow;
use Illuminate\Support\Facades\DB;

class CreateWorkflowAction
{
    public function execute(WorkflowData $data, int $userId): Workflow
    {
        return DB::transaction(function () use ($data, $userId) {
            return Workflow::create([
                'name' => $data->name,
                'user_id' => $userId,
            ]);
        });
    }
}