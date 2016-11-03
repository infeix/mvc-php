<?php

class UserController extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function login()
    {
        if(!$this->power->got_user())
        {
            $user_scope = User::scope()->where(array('Email' => $this->get_param('Email'), 'Password' => $this->get_param('Password')));
            if($user_scope->exists())
            {
                $user = $user_scope->first();
                $this->power->set_user($user);
            }
            else {
                $this->show_msg(i18n::get("authentication_failed"));
                return $this->redirect_to('Welcome');
            }
        }
        
        if($this->power->questions_for_creating_a_result())
        {
            return $this->redirect_to('Question');
        }
        else
        {
            return $this->redirect_to('Result');
        }
    }
    
    public function logout()
    {
        session_reset();
        session_destroy();
        return $this->redirect_to('Welcome');
    }

}