<?php

class ErrorController extends Controller
{

    function __construct() {
        parent::__construct();
        $this->view->set_layout('WelcomeTemplate');
    }

    public function index($msg)
    {
        $this->view->message = isset($msg) ? $msg : '';
        $this->view->render('Welcome/index');
    }

}
