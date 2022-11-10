<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessExecutionTaskTable extends Model
{
    use HasFactory;
    protected $fillable = ['string', 'task_id', 'group_id'];
    
    public function groupTask()
    {
        return $this->belongsTo(GroupTask::class);
    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
