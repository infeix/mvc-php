
       

        <form class="form-signin" id="formLogin" method="POST" action="/user/login">
          <h1 class="form-signin-heading"><?php echo i18n::get('welcome'); ?></h1>
          <label for="inputEmail" class="sr-only"><?php echo i18n::get('email'); ?></label>
          <input type="email" id="inputEmail" name="Email" class="form-control" placeholder="<?php echo i18n::get('email'); ?>" autofocus="">
          <label for="inputPassword" class="sr-only"><?php echo i18n::get('password'); ?></label>
          <input type="password" id="inputPassword" name="Password" class="form-control" placeholder="<?php echo i18n::get('password'); ?>">
          <input type="hidden" id="hiddenInputPassword" name="Password">
          <button id="buttonLogin" class="btn btn-lg btn-primary btn-block"><?php echo i18n::get('login'); ?></button>
        </form>
