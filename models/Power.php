<?php

class Power {

    function __construct() {
        if(isset($_SESSION['current_user']))
        {
            $this->current_user = User::scope()->find_by(User::get_pk(), $_SESSION['current_user']);
        }
    }
    
    function set_user($user)
    {
        $_SESSION['current_user'] = Model::get_pk_value($user);
        $this->current_user = $user;
    }
    
    function got_user()
    {
        return isset($this->current_user);
    }
    
    function user()
    {
        if(isset($this->current_user))
        {
            return $this->current_user;
        }
        return NULL;
    }
    
    function questions_for_creating_a_result()
    {
        if(isset($this->current_user))
        {
            $result_scope = Finished::scope()->where(array('user' => $this->current_user));
            $not_finished = !$result_scope->exists();
            if($not_finished)
            {
                return Question::scope();
            }    
        }
        return false;
    }
    
    
    function questions_for_showing_results()
    {
        if(isset($this->current_user))
        {
            $result_scope = Finished::scope()->where(array('user' => $this->current_user));
            $finished = $result_scope->exists();
            if($finished)
            {
                return Question::scope();
            }  
        }        
        return false;
    }
}