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

        date_default_timezone_set('GB');

        ini_set('display_startup_errors', '1');
        ini_set('display_errors', '1');
    ?>

    <?php
    echo '<br/>';
    $r=new db\model\repository();
//    $person = $r->create();
//    $person=$r->get(2);
//    $person->fname = "nemidonam";
//    $person->lname = "chi";
//    $person->gender = 0;
//    $time = new DateTime('1990-12-12');
//    $person->birthdate = $time->getTimestamp();
//    $person->dead = 1;
//    $person->save();
    
    
    $time = new DateTime('1960-12-12');
    $person3 = $r->get(1);
    $person2 = $r->get(3);
    echo "Hi " . $person3->oldestBrother . "\n";
    try{
//    $person2->birthdate = $time->getTimestamp();
    }
    catch (\Exception $e){
        echo $e->getMessage();
    }
//    $person2->birthdate = 11021;//time() - (30)* (3600*24*365);
//    $person2->save();
    
    
//    $person2->fname = 'reza';
//    $person2->lname = 'k';
//    $person2->gender = 1;
    
//    $person2->save();
    
//    $person = $r->create();
//    $person->fname = 'sarah';
//    $person->lname = 'babaei';
//    $person2->adopt($person);
//    $person->save();
/*    for ($index = 3; $index < 4; $index++) {
        $person = $r->get($index);
//        $person->gender = 0;
        $person->fatherID = 7;
        $person->motherID = 8;
        $person->save();
    }*/
    
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