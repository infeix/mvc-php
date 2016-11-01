<div class="contailner">
    <div class="row">
        <div class="col-xs-12">
        
            <h1 class="form-questions form-signin-heading"><?php echo i18n::get('result'); ?></h1>
            <div class="form-questions"><?php
            
              foreach($this->resource["Results"] as $question)
              {
                $question->render_its_results($this->resource["Finished"]);                
              } ?>
                <a href='/user/logout' class="btn btn-lg btn-primary"><?php echo i18n::get('logout'); ?></a>
            </div>
            
        </div>
    </div>
</div>
