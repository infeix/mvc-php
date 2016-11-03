<?php

class Answer extends Model {

    function __construct() {
        
    }
    
    function validate() {
        $this->validate_existence('question');
    }
    
    function disabled()
    {
        if($this->enabled == 0)
        {
            return 'disabled';
        }
        return '';
    }

    function indented()
    {
        if(isset($this->parent_id) && $this->parent_id != NULL)
        {
            return 'indented';
        }
        return '';
    }
    
    function render_sortable() {        
        ?>
        <li data-name="question-<?php echo $this->question_id; ?>" 
            data-id="question-<?php echo $this->question_id; ?>-<?php echo $this->id; ?>">
            <div class="form-check">
                <label class="form-check-label"><?php echo $this->get_label(); ?>
                </label>
            </div>
            <input type="hidden" 
                    class="required" 
                    id="question-<?php echo $this->question_id; ?>-<?php echo $this->id; ?>"
                    name="question-<?php echo $this->question_id; ?>"
                    value="<?php echo $this->id; ?>">
        </li><?php
    }
    
    function render_selectable($type = 'radio') {        
        
        ?>
        <div class="form-check <?php echo $this->indented(); ?>">
            <label class="form-check-label">
              <input type="<?php echo $type; ?>" 
                     class="form-check-input required" 
                     name="question-<?php echo $this->question_id; ?>"
                     value="<?php echo $this->id; ?>" <?php echo $this->disabled(); ?>>
                <?php echo $this->get_label(); ?>
            </label>
        </div>
        <?php
    }
    
    
    function get_label() {
        if(isset($this->label))
        {
            return $this->label;
        }
        if(!isset($this->answer_label))
        {
            $label_scope = Answer_label::scope()->where(array('answer' => $this, 
                                                              'lang' => i18n::lang()));
            if($label_scope->exists())
            {
                $this->answer_label = $label_scope->first();
            }
        }
        return $this->answer_label->label;
    }
}