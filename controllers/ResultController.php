<?php

class ResultController extends Controller {

    function __construct() {
        parent::__construct();
        $this->questions_scope = $this->power->questions_for_showing_results();
        $this->view->set_layout('WelcomeTemplate');
    }

    public function index() {
        if($this->find_resources())
        {
            return $this->render('Result/index');
        }
        return "REDIRECT";
    }
    
    public function create() {
        $results_json = $this->get_param("Results");
        $results = json_decode($results_json);
        $saved_results = array();
        try
        {
            $questions_scope = Question::scope()->query();
            foreach($results as $obj)
            {
                $details = $this->json_obj_details($obj);
                // details:
                // first element is the question id
                // second element is the rating of the answer
                $result = $this->save_result_by_obj($obj, $details[1]);
                array_push($saved_results, $result);
                unset($questions_scope->elements[$details[0]]);
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
            $finished = Finished::scope()->where(array('user' => $this->power->user()))->all();
            foreach($saved_results as $result)
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
     * The details are returned as an array.
     * details array:
     * - first element is the question id,
     * - second element is the rating of the answer
     * 
     * @param string $obj
     * @return array()
     */
    function json_obj_details($obj) {
        $namepart = explode('-', $obj->name);
        $rating = 1;
        if(isset($namepart[2]) && is_numeric($namepart[2]))
        {
            $rating = $namepart[2];
        }
        return array($namepart[1], $rating);
    }
    
    /**
     * Saves an odject as a result.
     * Throws Exeption it not possible.
     * 
     * @return primary key of Question
     * 
     * @param type $obj
     */
    function save_result_by_obj($obj, $rating = 1) 
    {
        while($rating > 0)
        {
            $result = new Result();
            $result->answer_id = $obj->value;        
            //$result->user_id = $this->power->user();
            $result->save(true);
            $rating -= 1;
        }
        return $result;
    }

    public function find_resources() {
        $this->questions_scope = $this->power->questions_for_showing_results();
        if($this->questions_scope === false)
        {
            $this->show_msg(i18n::get('error'));
            $this->redirect_to('Welcome/index');
            return false;
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
        return true;
    }
}

