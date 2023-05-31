<?php

namespace App\Repositories\User;

use App\BaseAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


/**
 * Description of Useraccess
 *
 * @author Noman
 */
class Useraccess extends BaseAccess
{

    private $_user;

    public function __construct()
    {
        $this->_user = new User();
    }

    public function userRegister($data)
    {
        $this->_user->email = isset($data['email']) ? $data['email'] : "";
        $this->_user->password = md5($data['password']);

        $this->_user->save();
        $lastInsertid = $this->_user->id;
        return [
            'return' => 'success',
            'user' => $this->_user,
            'email' => $this->_user->email,
            'id' => $lastInsertid
        ];
    }
}
