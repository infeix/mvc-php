
    <div class="row">
        <div class="col-xs-12">
        
            <h1 class="form-questions form-signin-heading"><?php echo i18n::get('questionary'); ?></h1>
            <form class="form-questions paginate" id="questions-form" pagesize="3">
              
              <?php
              foreach($this->resource["Questions"] as $question)
              {
                $question->render();                
              } ?>
              <a class="btn btn-info" href="" onclick="return prev_page(validate_form);">&lt;</a>
              <label class="btn btn-default disabled" style="cursor: default">
                <span class="current-page"></span>/<span class="count-pages"></span>
              </label>
              <a class="btn btn-info" href="" onclick="return next_page(validate_form);">&gt;</a>
              <a class="btn btn-primary" href="" onclick="return send_question_form();">
                  <?php echo i18n::get("send"); ?>
              </a>
              <a href='/user/logout' class="btn btn-info"><?php echo i18n::get('logout'); ?></a>
            </form>
        </div>
    </div>
