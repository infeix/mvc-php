<?php
class DatabaseSetup extends DatabaseConnection {

    public function __construct() {
    }
    
    function clean() {
        
        $this->down('config/db/3_add_result.php');
        $this->down('config/db/2_add_answer.php');
        $this->down('config/db/1_add_question.php');
        $this->down('config/db/0_add_user.php');
        $this->up('config/db/0_add_user.php');
        $this->up('config/db/1_add_question.php');
        $this->up('config/db/2_add_answer.php');
        $this->up('config/db/3_add_result.php');
    }
    
    function up($file) {
        require $file;
        $querys = explode(";", $up);
        foreach ($querys as $query)
        {
            $query = trim($query);
            if(!empty($query))
            {
                if($this->send_query($query) !== TRUE)
                {
                    echo "FAIL to send:\n{$query}\nErrormessage: " . $this->error() . "\n";
                }
            }
        }
    }
    
    function down($file) {
        require $file;
        $querys = explode(";", $down);
        foreach ($querys as $query)
        {
            $query = trim($query);
            if(!empty($query))
            {
                if($this->send_query($query) !== TRUE)
                {
                    echo "FAIL to send:\n{$query}\nErrormessage: " . $this->error() . "\n";
                }
            }
        }
    }

}