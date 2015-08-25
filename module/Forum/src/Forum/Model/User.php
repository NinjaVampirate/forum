<?php

namespace Forum\Model;

class User{
    public $user_id;
    public $username;
    public $password;        // maybe a bad idea to have these public?
    public $level;
    //public user_date;
    
    public function exchangeArray($data)
    {
        $this->user_id  = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->level = (!empty($data['level'])) ? $data['level'] : null;
        
        // $this->replies  = (!empty($data['replies'])) ? $data['replies'] : null;
        // $this->lastpost = (!empty($data['lastpost'])) ? $lastpost['lastpost'} : null;
    }
    
}