<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php 
        function __autoload($class_name) {
            include  $cat = str_replace('\\', '/', $class_name) . '.php';                
        }

        date_default_timezone_set('GB');

        ini_set('display_startup_errors', '1');
        ini_set('display_errors', '1');
        ?>

    <?php
    defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . ''));

    defined('APPLICATION_VIEW_PATH')
    || define('APPLICATION_VIEW_PATH', realpath(dirname(__FILE__) . '/view/'));

    echo '<br/>';
    $argString=str_replace("/"," ",$_SERVER['QUERY_STRING']);
    $argString=trim(str_replace("&"," ",$argString));
    if(empty($argString)){
        $argString= "";
    }
    $arguments = explode(" ",$argString);
    $filename = array_shift($arguments);

    if(empty($arguments))
        $arguments[] = 'index';
    $controller = "db\\controller\\".array_shift($arguments)."Controller";
    $myController = new $controller($arguments);
//    $myController = new db\controller\indexController($arguments);

    ?>
</body>