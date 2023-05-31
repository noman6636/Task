<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Task\Taskaccess;
use Illuminate\Support\Facades\Validator;

class TaskControler extends Controller
{
    private $_request;
    private $_taskobj;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        $this->_taskobj = new Taskaccess();
    }
    public function createTask()
    {
        $validateUser = Validator::make(
            $this->_request->all(),
            [
                'name' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 422);
        }

        $postedData = $this->_request->input();
        try {
            $task = $this->_taskobj->insertTask($postedData);


            return response()->json([
                'status' => true,
                'message' => 'Task Created Successfully',
                'Id' => $task['id'],
                'Name' => $task['name'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function taskList()
    {
        try {
            $tasks = Task::all(['id', 'name']);
            if ($tasks) {
                return response()->json([
                    'status' => true,
                    'message' => 'Task Name found',
                    'task' => $tasks,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Task name not found'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
