<?php
    
    namespace Forum\Form;
    
    use Zend\Form\Form;
    
    class PostForm extends Form
    {
        public function __construct($name = null)
        {
            // we want to ignore the name passed
            parent::__construct('post');
            
            $this->add(array(
                             'name' => 'thread_id',
                             'type' => 'Hidden',
                             ));
            
            $this->add(array(
                             'name' => 'post_id',
                             'type' => 'Hidden',
                             ));
            
            $this->add(array(
                             'name' => 'user',
                             'type' => 'Hidden',
                             ));
            
            $this->add(array(
                             'name' => 'content',
                             'type' => 'Text',
                             'options' => array(
                                                'label' => 'Add content',
                                                ),
                             ));
            $this->add(array(
                             'name' => 'submit',
                             'type' => 'Submit',
                             'attributes' => array(
                                                   'value' => 'Submit',
                                                   'id' => 'submitbutton',
                                                   ),
                             ));
        }
    }