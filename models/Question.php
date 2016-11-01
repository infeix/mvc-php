<?php

class Question extends Model {

    function __construct() {
        
    }

    //TODO: At this point view components are handled by the model. 
    //Not the best solution.
    function render()
    {
        ?>
        <div class="form-group question-<?php echo $this->id; ?>" style="display: none;">
        <legend><?php echo $this->label; ?></legend>
        <?php
            if($this->input_type == "sortablelist" )
            {
                ?>
                    <label class="form-check-label">
                                    <?php echo i18n::get('drag_and_drop'); ?>
                                </label>
                    <ol class="sortable-answers"><?php
                    foreach($this->answer_scope->all() as $answer)
                    {
                        ?>
                        <li data-name="question-<?php echo $this->id; ?>" data-id="question-<?php echo $this->id; ?>-<?php echo $answer->id; ?>">
                            <div class="form-check"><label class="form-check-label">
                                    <?php echo $answer->label; ?>
                                </label>
                            </div>
                            <input type="hidden" 
                                    class="required" 
                                    id="question-<?php echo $this->id; ?>-<?php echo $answer->id; ?>"
                                    name="question-<?php echo $this->id; ?>"
                                    value="<?php echo $answer->id; ?>">
                        </li>
                        <?php
                    }
                    ?>
                    </ol><?php
            }
            elseif ($this->input_type == "checkbox" || $this->input_type == "radio")
            {
                foreach($this->answer_scope->all() as $answer)
                {
                    $disabled = '';
                    if($answer->enabled == 0)
                    {
                        $disabled = 'disabled';
                    }
                    $tab = '';
                    if(isset($answer->parent_id) && $answer->parent_id != NULL)
                    {
                        $tab = 'tabbed';
                    }
                    ?>
                    <div class="form-check <?php echo $tab; ?>">
                        <label class="form-check-label">
                          <input type="<?php echo $this->input_type; ?>" 
                                 class="form-check-input required" 
                                 name="question-<?php echo $this->id; ?>"
                                 value="<?php echo $answer->id; ?>" <?php echo $disabled; ?>>
                            <?php echo $answer->label; ?>
                        </label>
                    </div>
                    <?php
                }
            }  ?>  
                    
        </div><?php
    }
    
    function render_its_results($finished) 
    {
         ?>
        <div class="form-group question-<?php echo $this->id; ?>">
        <legend><?php echo $this->label; ?></legend>
        <?php
        
        foreach($this->answer_scope->all() as $answer)
        {
            $percent = (count($answer->result_scope->all()) / $finished) * 100;
            if($this->multi_select > 1)
            {
                $percent = $percent/($this->multi_select-1);
            }
            ?>
            <div class="form-check">
                <label class="form-check-label">
                    <?php echo "{$percent}% {$answer->label}"; ?>
                </label>
            </div>
            <?php                
        }
        
        ?>            
        </div>
        <?php
    }
}