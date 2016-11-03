<?php

class Question extends Model {

    function validate() {
        $this->validate_existence('sort_index');
    }
    
    function get_label() {
        if(isset($this->label))
        {
            return $this->label;
        }
        if(!isset($this->question_label))
        {
            $label_scope = Question_label::scope()->where(array('question' => $this, 
                                                                'lang' => i18n::lang()));
            if($label_scope->exists())
            {
                $this->question_label = $label_scope->first();
            }
        }
        return $this->question_label->label;
    }
    
    //TODO: At this point view components are handled by the model. 
    //Not the best solution.
    function render()
    {
        ?>
        <div class="form-group question-<?php echo $this->id; ?>" 
             style="display: none;">
        
            <legend>
                <?php echo $this->get_label(); ?>
            </legend>
            <?php

            if($this->input_type == "sortablelist" )
            {
                $this->render_answers_sortable();
            }
            elseif ($this->input_type == "checkbox" || $this->input_type == "radio")
            {
                $this->render_answers_selectable();
            }  

        ?>
        </div>
        <?php
    }
    
    function render_answers_sortable() {     
        $this->get_scope("Answer");      
        ?>
        <label class="form-check-label"><?php echo i18n::get('drag_and_drop'); ?>
        </label>
        <ol class="sortable-answers"><?php
        
        foreach($this->answer_scope->all() as $answer)
        {
            $answer->render_sortable();
        }
        
        ?></ol><?php
    }
    
    function render_answers_selectable() {        
        $this->get_scope("Answer");
        foreach($this->answer_scope->all() as $answer)
        {
            $answer->render_selectable($this->input_type);
        }
    }
    
    function render_its_results($finished) {        
        $this->get_scope("Answer");
         ?>
        <div class="form-group question-<?php echo $this->id; ?>">
            <legend><?php echo $this->get_label(); ?></legend>
            <?php

            foreach($this->answer_scope->all() as $answer) {
                $answer->get_scope("Result");
                $percent = (count($answer->result_scope->all()) / $finished) * 100;
                if($this->multi_select > 1)
                {
                    $percent = $percent/($this->multi_select-1);
                }
                ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <?php echo "{$percent}% {$answer->get_label()}"; ?>
                    </label>
                </div>
                <?php                
            }

            ?>            
        </div>
        <?php
    }
}