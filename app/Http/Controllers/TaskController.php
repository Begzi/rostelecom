<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessExecutionTask;
use App\Models\Task;
use App\Models\GroupTask;
use App\Models\ProcessExecutionTaskTable;
require "function/Function.php";

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::all();
        // ProcessExecutionTask::dispatch();  
        return $task[0];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!(isset($request['string'])) or !(isset($request['repeat'])) or 
        !(isset($request['frequency'])) or !(isset($request['algoritm_hash'])) or 
        !(isset($request['sol'])) ){
            return ['error' => 'Не валидные данные str: string, algoritm_hash, sol; int: repeat(кол-во повторений), frequency(в секундах)'];
        }
        $valid = validateTaks($request['string'], $request['repeat'], $request['frequency'], $request['algoritm_hash'], $request['sol'] );
        if ($valid == 1){
            return (['error' => 'string, algoritm_hash, sol, repeat, frequency not NULL']);
        }
        elseif($valid == 2){
            return (['error' => 'repeat, frequency must be INT and > 0']);
        }
        elseif($valid == 3){
            return (['error' => 'algoritm_hash must be from next array', 'array' => hash_algos()]);
        }
        $task = Task::create($request->all()); 
        ProcessExecutionTask::dispatch($task, 0);  
        return ['success' => 'Успешно добалвенно'];
    }
    public function storeGroup(Request $request)
    {
        if (!(isset($request['array'])) or !(isset($request['name']))){
            return (['error' => 'arrayTasks, name must be']);
        }
        $arrayTasks = $request['array'];
        $gruopTask = GroupTask::create(['name' => $request['name']]);
        foreach ($arrayTasks as $key => $value) {
            if (!(isset($value['string'])) or !(isset($value['repeat'])) or 
            !(isset($value['frequency'])) or !(isset($value['algoritm_hash'])) or 
            !(isset($value['sol'])) ){
                return ['error' => 'Не валидные данные str: string, algoritm_hash, sol; int: repeat(кол-во повторений), frequency(в секундах)'];
            }
            $valid = validateTaks($value['string'], $value['repeat'], $value['frequency'], $value['algoritm_hash'], $value['sol'] );
            if ($valid == 1){
                return (['error' => 'string, algoritm_hash, sol, repeat, frequency not NULL']);
            }
            elseif($valid == 2){
                return (['error' => 'repeat, frequency must be INT and > 0']);
            }
            elseif($valid == 3){
                return (['error' => 'algoritm_hash must be from next array', 'array' => hash_algos()]);
            }
            elseif ($valid != 0){
                return ['error' => 'Не валидные данные str: string, algoritm_hash, sol; int: repeat(кол-во повторений), frequency(в секундах)'];
            }
        }
        foreach ($arrayTasks as $value) {
            $task = Task::create($value); 
            ProcessExecutionTask::dispatch($task, $gruopTask->id);  
        }
        return ['success' => 'Успешно добалвенно'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('processTask')->find($id);
        if ($task == null){
            return ['error' => 'DB have not this ID'];
        }
        elseif ($task->processTask->repeated == $task->repeat){
            return ['info' => 'Finished', 'string' => $task->processTask->string];
        }
        return ['info' => 'Processing', 'string' => $task->processTask->string, 'repeated' => $task->processTask->repeated]; 
    }
    public function showGroup($id)
    {
        $groupTask = GroupTask::find($id);
        if ($groupTask == null){
            return ['error' => 'DB have not this ID'];
        }
        $tasks = Task::with('processTask')->get();
        $newTasks = [];
        foreach ($tasks as $value) {
            if ($value->processTask->group_id == $groupTask->id){
                array_push($newTasks, $value);
            }
        }
        if (count($newTasks) == 0){
            return ['error' => 'DB have not tasks for this ID group'];
        }
        $data = ['name' => $groupTask->name];
        foreach ($newTasks as $task) {
            if ($task->processTask->repeated == $task->repeat){
                array_push($data, ['info' => 'Finished', 'string' => $task->processTask->string]);
            }
            if ($task->processTask->repeated < $task->repeat){
                array_push($data, ['info' => 'Processing', 'string' => $task->processTask->string, 'repeated' => $task->processTask->repeated]);
            }
        }
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stop($id)
    {
        $task = Task::find($id);
        if ($task == null){
            return ['error' => 'DB have not this ID'];
        }
        $processTask = ProcessExecutionTaskTable::where('task_id', $id)->first();
        if ($processTask == null){
            return ['error' => 'DB have not this ID'];
        }
        $processTask->repeated = $task->repeat;
        $processTask->save();
        return ['success' => 'Успешно остановленно'];
    }
    public function stopGroup($id)
    {
        $groupTask = GroupTask::find($id);
        if ($groupTask == null){
            return ['error' => 'DB have not this ID'];
        }
        $tasks = Task::with('processTask')->get();
        $newTasks = [];
        foreach ($tasks as $value) {
            if ($value->processTask->group_id == $groupTask->id){
                array_push($newTasks, $value);
            }
        }
        if (count($newTasks) == 0){
            return ['error' => 'DB have not tasks for this ID group'];
        }
        foreach ($newTasks as $task) {
            if ($task->processTask->repeated < $task->repeat){
                ProcessExecutionTaskTable::where(['id' => $task->processTask->id])->update(['repeated' => $task->repeat ]);
                // return $task->processTask->id;
            }
        }
        return ['success' => 'Успешно остановленно'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
