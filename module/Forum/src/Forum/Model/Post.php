<?php
    
    namespace Forum\Model;
    
    class Post{
        
        public $post_id;            //  post ID
        public $thread_id;           //   thread ID
        public $content;            //  post content
        public $user;               // user of post
        //public $time;
        
        public function exchangeArray($data)
        {
            $this->post_id  = (!empty($data['post_id'])) ? $data['post_id'] : null;
            $this->thread_id = (!empty($data['thread_id'])) ? $data['thread_id'] : null;
            $this->content = (!empty($data['content'])) ? $data['content'] : null;
            $this->user = (!empty($data['user'])) ? $data['user'] : null;
        }
    }