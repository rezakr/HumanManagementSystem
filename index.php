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
    echo '<br/>';
    $r=new db\model\repository();
    $person2 = $r->get(1);
    
    
//    $person2->fname = 'reza';
//    $person2->lname = 'k';
//    $person2->gender = 1;
    
//    $person2->save();
    
//    $person = $r->create();
//    $person->fname = 'sarah';
//    $person->lname = 'babaei';
//    $person2->adopt($person);
//    $person->save();
    for ($index = 2; $index < 13; $index++) {
        $person = $r->get($index);
//        $person->gender = 0;
//        $person->fatherID = 0;
        $person->motherID = 1;
        $person->save();
    }
    
    $test=$person2->children;
    foreach ($test as $t) {
        echo $t.'<br/>';
    }

    echo 'SONS :';
    $test=$person2->sons;
    foreach ($test as $t) {
        echo $t.'<br/>';
    }
    ?>
        </pre>
</body>