<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <pre>
    <?php 
        function __autoload($class_name) {
            include  $cat = str_replace('\\', '/', $class_name) . '.php';                
        }

        ini_set('display_startup_errors', '1');
        ini_set('display_errors', '1');
    ?>

    <?php

    $r=new db\model\repository();
//    $person = $r->get(0);
    $person2 = $r->get(0);
    
    
    $person2->fname = 'reza';
    $person2->lname = 'k';
    $person2->gender = 1;
    
    $person2->save();
    
    $person = $r->create();
    $person->fname = 'sarah';
    $person->lname = 'babaei';
    $person2->adopt($person);
    $person->save();
    
    $test=$person2->getChildern;
    var_dump($test);
    
    ?>
        </pre>
</body>