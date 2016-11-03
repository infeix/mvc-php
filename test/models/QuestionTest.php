<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class QuestionTest extends TestCase {
    
    public function setUp()
    {
        Session::init();
        $this->host = "localhost";
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "de-DE";
        $_SERVER["HTTP_HOST"] = $this->host;
        Factory::clean_database();
        Factory::delete_all_of("Answer_label");
        Factory::delete_all_of("Answer");
        Factory::delete_all_of("Question_label");
        Factory::delete_all_of("Question");
        $this->question = Factory::create("Question");
        $this->question_l = Factory::create("Question_label", 
                                            array('lang' => 'DE', 
                                                  'question' => $this->question));
        $this->answer1 = Factory::create("Answer", 
                                         array("question" => $this->question));
        $this->answer1_l = Factory::create("Answer_label", 
                                           array('lang' => 'DE', 
                                                 'answer' => $this->answer1));
        $this->answer2 = Factory::create("Answer", 
                                         array("question" => $this->question));
        $this->answer2_l = Factory::create("Answer_label", 
                                           array('lang' => 'DE', 
                                                 'answer' => $this->answer2));
        $this->result = Factory::create("Result", 
                                        array('answer' => $this->answer1));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_render()
    {
        $this->expectOutputRegex("/{$this->question_l->label}/");
        $this->question->render();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_render_sort()
    {
        $this->question->input_type = 'sortablelist';
        $this->question->delete();
        $this->question->save(true);
        $this->expectOutputRegex("/{$this->question_l->label}/");
        $this->question->render();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_render_its_results()
    {
        $this->expectOutputRegex("/{$this->answer1_l->label}/");
        $this->question->render_its_results(count(Result::scope()->all()));
        $this->expectOutputRegex("/100%/");
        $this->question->render_its_results(count(Result::scope()->all()));
    }    
}

