<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Umfrage</title>

    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/login.css" rel="stylesheet">
    <link href="/assets/css/general.css" rel="stylesheet">
    <link href="/assets/css/question_index.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      <div class="page-wrap"><?php
            if(Session::has('msg'))
            {?>
                <div id='msg' style='display: none;' class="msg-container warning"><?php echo Session::pop('msg'); ?></div><?php
            }             
            require $this->current_view;
          ?>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery-sortable.js"></script>
    <script src="/assets/js/md5.js"></script>
    <script src="/assets/js/login.js"></script>
    <script src="/assets/js/paginate.js"></script>
    <script src="/assets/js/questions.js"></script>
    <script>
        $( document ).ready(function(){
            $('#msg').each(function(){$(this).slideDown();});
        });
        $( document ).ready($( document ).click(function(){
            $('#msg').slideUp();
        }));
    </script>
  </body>
</html>
