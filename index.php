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
    echo '<br/>';
    $argString=str_replace("/"," ",$_SERVER['QUERY_STRING']);
    $argString=trim(str_replace("&"," ",$argString));

    if(empty($argString)){
        $argString= "show all";
    }
    $arguments = explode(" ",$argString);

    $controller = new db\controller($arguments);
    $command = array_shift($arguments);
    $link = "Location: /index.php";
    $result['error']=false;
    $param['title']="index";
    $param['action']="/index.php/";
//    $param['human']=null;
    switch ($command) {
        case "create":
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $new = $_POST;
                $controller->setRequestPostParameters($new);
                $result= $controller->create();
                if($result['error'])
                    continue;
                $id=$result['id'];
                $link = "Location: /index.php/show/$id/";
                header ($link);
                continue;
            }
            $param['title']="Create a new human";
            $param['action']="/index.php/create/";
            $controller->render('view/new.php',$param);
            break;
        case "edit":
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $id=$_POST['id'];
                $edit = $_POST;
                $controller->setRequestPostParameters($edit);
                $result = $controller->update($id);
                if($result['error'])
                    continue;
                $id=$edit['id'];
                $link = "Location: /index.php/show/$id/";
                header ($link);
            }
            $id = (int)array_shift($arguments);
            $param['title']="Editing human id $id ";
            $param['action']="/index.php/edit/";
            $controller->setRequestParameters($arguments);
            $result=$controller->show($id);
            if($result['error'])
                continue;
            $param['human']=$result['result'];
            $controller->render('view/edit.php',$param);
            break;
        case "show":
            $id = array_shift($arguments);
            $controller->setRequestParameters($arguments);
            if(is_numeric($id))
                $id=(int)$id;
            else{
                echo "<a href='/index.php/create/'>new person</a>";
                $id="all";
            }
            $result = $controller->show($id);
            if($result['error']) continue;
            if(!is_array($result['result'])){
                $params[]=$result['result'];
            }else
                $params=$result['result'];                
            foreach ($params as $param) {
                $show['human']=$param;
                $controller->render('view/show.php',$show);
            }

            break;
        case "help":
            $result['error'] = false;
            $result['body'] = "Use create fname=name lname=family gender=1 birthdate=1900-01-01 fatherID=id motherID=id\n"
            ."or update id something=newValue or adopt a son or daughter \n"
                ."to show the information you can use : \nshow all \n"
                ."show id\n"
                ."show id sons/daughters/children/father/mother or more\n"
                ."show id theOldestInFamily? or orphan?\n";
        default:
            echo "$command is not defined";
            break;
    }
    if ($result['error'])
        echo $result['message']."\n";
    ?>
</body>