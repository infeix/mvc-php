<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class SessionTest extends TestCase {

    public function setUp()
    {
        session_start();
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_init() {
        // prepare
        $session = new Session();
        $session->test = "pustekuchen";
        $session->mega = "man";
        $_SESSION['s'] = serialize($session);
        // act
        Session::init();
        // check
        $this->assertEquals('pustekuchen', Session::get('test'));
        $this->assertEquals('man', Session::$current->mega);
        $this->assertEquals('pustekuchen', Session::$last->test);
        $this->assertEquals('man', Session::$last->mega);
        $this->assertEquals($_SESSION['last_s'], serialize($session));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_add() {
        
        // prepare
        $session = new Session();
        $session->test = "pustekuchen";
        $session->mega = "man";
        $_SESSION['s'] = serialize($session);
        Session::init();
        // act
        Session::add('mega', 'super');
        // check
        $this->assertEquals('pustekuchen', Session::$current->test);
        $this->assertEquals('mansuper', Session::$current->mega);
        $this->assertEquals('pustekuchen', Session::$last->test);
        $this->assertEquals('man', Session::$last->mega);
        
        $session->mega .= 'super';
        $this->assertEquals($_SESSION['s'], serialize($session));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_set() {
        
        // prepare
        $session = new Session();
        $session->test = "pustekuchen";
        $session->mega = "man";
        $_SESSION['s'] = serialize($session);
        Session::init();
        // act
        Session::set('mega', 'super');
        // check
        $this->assertEquals('pustekuchen', Session::$current->test);
        $this->assertEquals('super', Session::$current->mega);
        $this->assertEquals('pustekuchen', Session::$last->test);
        $this->assertEquals('man', Session::$last->mega);
        
        $session->mega = 'super';
        $this->assertEquals($_SESSION['s'], serialize($session));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_get() {
        // prepare
        $session = new Session();
        $session->test = "pustekuchen";
        $session->mega = "man";
        $_SESSION['s'] = serialize($session);
        Session::init();
        // act &
        // check
        $this->assertEquals('pustekuchen', Session::get('test'));
        $this->assertEquals('man', Session::get('mega'));
        $this->assertEquals('pustekuchen', Session::get('test', true));
        $this->assertEquals('man', Session::get('mega', true));
        $this->assertEquals(NULL, Session::get('sgfsegsg', true));
        $this->assertEquals(NULL, Session::get('uziouioljn'));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_remove() {
        // prepare
        $session = new Session();
        $session->test = "pustekuchen";
        $session->mega = "man";
        $_SESSION['s'] = serialize($session);
        Session::init();
        // act
        Session::remove('mega');
        // check
        $this->assertEquals('pustekuchen', Session::get('test'));
        $this->assertEquals('pustekuchen', Session::get('test', true));
        $this->assertEquals('man', Session::get('mega', true));
        
        unset($session->mega);
        $this->assertEquals($_SESSION['s'], serialize($session));
    }
    
    
    
}
