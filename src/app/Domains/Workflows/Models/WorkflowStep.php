<?php

namespace App\Domains\Workflows\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowStep extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['workflow_id', 'type', 'settings', 'order'];

    protected $casts = [

    ];
}
