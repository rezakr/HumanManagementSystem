<?php
    function __autoload($class_name) {
        include  $cat = str_replace('\\', '/', $class_name) . '.php';                
    }

    date_default_timezone_set('GB');

    ini_set('display_startup_errors', '1');
    ini_set('display_errors', '1');

    $filename = array_shift($argv);
    $command = array_shift($argv);
    
    $controller = new \db\controller();
    $result['message'] = "All Good and easy\n";
    $result['body'] = "You can run it with create, update \$id, show \$id, and more\n";
    switch ($command) {
        case "create":
            $result = $controller->create($argv);
            break;
        case "update":
            $id = array_shift($argv);
            if(is_numeric($id))
                $result = $controller->update((int)$id,$argv);
            else{
                $result['error'] = true;
                $result['message'] = "Wrong Argument, there's no ID";            
            }
            break;
        case "show":
            $id = array_shift($argv);
            if(is_numeric($id)){
                $result = $controller->show($id,$argv);
            }
            else{
                if($id == "all")
                    $result = $controller->showAll();
                else{
                    $result['error'] = true;
                    $result['message'] = "Wrong Argument, can't work with show $id";
                }
                    
            }
            break;
        case "help":
        default:
            break;    
    }
    
    if (!$result['error'])
        echo $result['body']."\n";
    else
        echo $result['message']."\n";


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
