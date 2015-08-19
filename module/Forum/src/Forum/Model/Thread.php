<?php

    namespace Forum\Model;
    
    class Thread{
    
        public $id;          //thread ID
        public $title;       //thread title - put OP/date underneath
       // public $replies;     //number of replies
       // public $lastpost;    //time of last post & name of poster
        public $inputFilter;
        
        public function exchangeArray($data)
        {
            $this->id  = (!empty($data['id'])) ? $data['id'] : null;
            $this->title = (!empty($data['title'])) ? $data['title'] : null;
           // $this->replies  = (!empty($data['replies'])) ? $data['replies'] : null;
           // $this->lastpost = (!empty($data['lastpost'])) ? $lastpost['lastpost'} : null;
        }
        
        public function setInputFilter(InputFilterInterface $inputFilter)
        {
            throw new \Exception("Not used");
        }
        
        public function getInputFilter()
        {
            //...
        }
        
        
    }