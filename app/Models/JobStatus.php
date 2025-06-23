<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    const STATUS_PENDING   = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED    = 'FAILED';

    protected $fillable = ['user_id', 'job_id', 'status', 'message'];

}
