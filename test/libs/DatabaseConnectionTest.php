<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class DatabaseConnectionTest extends TestCase {

    public function test_send_query_empty() {
        $con = new DatabaseConnection();
        $this->setExpectedException(Exception::class);
        $con->send_query('');
    }
    
}
