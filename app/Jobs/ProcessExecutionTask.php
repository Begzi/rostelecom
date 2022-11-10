<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\ProcessExecutionTaskTable;

class ProcessExecutionTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 100;
    private $task;
    private $group_id;
    public function __construct(Task $task, $group_id)
    {
        $this->task = $task;
        $this->group_id = $group_id;
    }

    public function handle()
    {
        $processTasks = ProcessExecutionTaskTable::where('task_id', $this->task->id)->get();
        if (count($processTasks) > 1){
            return $this->fail();
        }
        elseif (count($processTasks) == 0){
            ProcessExecutionTaskTable::create([
                'string' => hash($this->task->algoritm_hash, $this->task->string), 
                'task_id' => $this->task->id,
                'group_id' => $this->group_id
            ]);
            return $this->release($this->task->frequency);
        }        
        if ($processTasks[0]->repeated < $this->task->repeat){
            $processTasks[0]->repeated  = $processTasks[0]->repeated + 1;
            $processTasks[0]->string  = hash($this->task->algoritm_hash,
             $this->task->string . $this->task->sol);
            $processTasks[0]->save();
            return $this->release($this->task->frequency);
        }
    }
}
