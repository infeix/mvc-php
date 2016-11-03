<?php

class ErrorController extends Controller
{

    function __construct() {
        parent::__construct();
    }

    public function index($msg)
    {
        $this->show_msg($msg);
        return $this->redirect_to('Welcome/index');
    }

}
