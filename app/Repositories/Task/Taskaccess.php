<?php

namespace App\Repositories\Task;

use App\BaseAccess;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;


/**
 * Description of Taskaccess
 *
 * @author Noman
 */
class Taskaccess extends BaseAccess
{

    private $_task;

    public function __construct()
    {
        $this->_task = new Task();
    }

    public function insertTask($data)
    {
        $this->_task->name = isset($data['name']) ? $data['name'] : "";
        $this->_task->save();
        return [
            'id' =>  $this->_task->id,
            'name' => $this->_task->name
        ];
    }


}
