<?php

class ResultController extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->set_layout('WelcomeTemplate');
    }

    public function index() {
        $this->find_resources();
        return $this->render('Result/index');        
    }
    
    public function create() {
        $results_json = $this->get_param("Results");
        $results = json_decode($results_json);
        try
        {
            $questions_scope = Question::scope()->query();
            foreach($results as $obj)
            {
                $question = $this->save_result_by_obj($obj);
                unset($questions_scope->elements[$question]);
            }
            $finished = new Finished();
            $finished->user = $this->power->user();
            $finished->save(true);
            if(count($questions_scope->all()) > 0)
            {
                throw new Exception();
            }
        } 
        catch (Exception $ex) 
        {
            // rollback
            $results = Result::scope()->where(array('user' => $this->power->user()))->all();
            $finished = Finished::scope()->where(array('user' => $this->power->user()))->all();
            foreach($results as $result)
            {
                $result->delete();
            }
            foreach($finished as $result)
            {
                $result->delete();
            }
            $this->show_msg(i18n::get("could_not_save"));
            return $this->redirect_to('question/index');  
        }
        
        return $this->redirect_to('result/index');        
    }
    
    /**
     * Saves an odject as a result.
     * Throws Exeption it not possible.
     * 
     * @return primary key of Question
     * 
     * @param type $obj
     */
    function save_result_by_obj($obj) 
    {
        $namepart = explode('-', $obj->name);
        $rating = 1;
        if(isset($namepart[2]) && is_numeric($namepart[2]))
        {
            $rating = $namepart[2];
        }
        while($rating > 0)
        {
            $result = new Result();
            $result->answer_id = $obj->value;        
            //$result->user_id = $this->power->user();
            $result->save(true);
            $rating -= 1;
        }
        return $namepart[1];
    }

    public function find_resources() {
        $this->questions_scope = $this->power->questions_for_showing_results();
        if($this->questions_scope === false)
        {
            $this->redirect_to('questions');
            return;
        }
        $finished_count = count(Finished::scope()->all());
        $questions = $this->questions_scope->join("Question_label", SCOPE_CHILD)
                                                ->where(array("Question_label.lang" => i18n::lang()));
        $answers = $questions->scope_of_its("Answer")
                             ->join("Answer_label", SCOPE_CHILD)
                             ->where(array("Answer_label.lang" => i18n::lang()));
        
        $answers_and_results = $answers->has_manny_of('Result');
        $questions_answers_and_results = $questions->has_manny_of($answers_and_results)->all();
        $this->set_resource('Results', $questions_answers_and_results);
        $this->set_resource('Finished', $finished_count);
    }
}

