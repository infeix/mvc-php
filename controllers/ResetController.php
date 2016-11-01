<?php

class ResetController extends Controller
{
    
    function index($arg)
    {
        if($arg == "secret42")
        {
            require_once 'test/load_things.php';
            Factory::clean_database();
            echo "<br/><br/>AFTER INSTALLATION DELETE THE RESETCONTROLLER!!!<br/><br/> YOU CAN FIND IT UNDER controllers/ResetController.php";
        }
    }    
}
