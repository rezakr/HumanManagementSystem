<?php
    function __autoload($class_name) {
        include  $cat = str_replace('\\', '/', $class_name) . '.php';                
    }

    date_default_timezone_set('GB');

    ini_set('display_startup_errors', '1');
    ini_set('display_errors', '1');

    $filename = array_shift($argv);
    $command = array_shift($argv);
    
    $controller = new \db\controller($argv);
    $result['error'] = true;
    $result['message'] = "Wrong Argument, type help to see more\n";
    $result['body'] = "use create, update \$id, show \$id, and more\n";
    switch ($command) {
        case "create":
            $result = $controller->create();
            break;
        case "update":
            $id = array_shift($argv);
            $controller->setRequestParameters($argv);
            $result = $controller->update($id);
            break;
        case "show":
            $id = array_shift($argv);
            $controller->setRequestParameters($argv);
            $result = $controller->show($id);
            break;
        case "help":
            $result['error'] = false;
        default:
            break;
    }
    if (!$result['error'])
        echo $result['body']."\n";
    else
        echo $result['message']."\n";
?>
