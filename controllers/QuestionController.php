<?php


class QuestionController extends Controller {

    function __construct() {
        parent::__construct();
        $this->questions_scope = $this->power->questions_for_creating_a_result();
        if($this->questions_scope === false)
        {
            $this->redirect_to('result/index');
            return;
        }
        $this->view->set_layout('WelcomeTemplate');
    }

    public function index() {
        $this->find_resources();
        return $this->render('Question/index');        
    }

    public function find_resources() {
        $questions_scope = $this->questions_scope->join("Question_label", SCOPE_CHILD)
                                                 ->where(array("Question_label.lang" => i18n::lang()));
        $answers = $questions_scope->scope_of_its("Answer")
                                   ->join("Answer_label", SCOPE_CHILD)
                                   ->where(array("Answer_label.lang" => i18n::lang()));

        $questions = $questions_scope->has_manny_of($answers)->all();
        $this->set_resource('Questions', $questions);
    }

}

