<?php

class WelcomeController extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->set_layout('WelcomeTemplate');
    }

    public function index()
    {
        return $this->render('Welcome/index');
    }

}
