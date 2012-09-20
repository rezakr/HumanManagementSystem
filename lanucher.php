<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lanucher
 *
 * @author reza
 */
class lanucher {
    
    public function __construct(){
        $argString=str_replace("/"," ",$_SERVER['QUERY_STRING']);
        $argString=trim(str_replace("&"," ",$argString));
        if(empty($argString)){
            $argString= "";
        }
        $arguments = explode(" ",$argString);
        $filename = array_shift($arguments);

        
        if(empty($arguments)){
            echo "Welcome!\n";
            die();
        }
//            $arguments[] = 'humans';
        
        $controller = "db\\controller\\".array_shift($arguments)."Controller";
        if(!file_exists($controller.".php")){
              echo "$controller doesn't exists\n";
              die();
//            throw new \Exception("$controller doesn't exists");
        }
        $myController = new $controller($arguments);

    }
    public function run(){
        
    }
    //put your code here
}

?>
