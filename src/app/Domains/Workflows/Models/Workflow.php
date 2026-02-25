<?php

namespace App\Domains\Workflows\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workflow extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'user_id', 'status'];

    protected $casts = [

    ];
}
