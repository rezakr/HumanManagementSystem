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
    switch ($command) {
        case "new":
            $controller->render('view/new.php');
            break;
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
            }
            break;
        case "edit":
            $id = (int)array_shift($arguments);
            $controller->setRequestParameters($arguments);
            $result=$controller->show($id);
            if($result['error'])
                continue;
            $param['id']=$id;
            $param['fname']=$result['result']->fname;
            $param['lname']=$result['result']->lname;
            $param['gender']=$result['result']->gender;
            $param['motherID']=$result['result']->motherID;
            $param['fatherID']=$result['result']->fatherID;
            $param['birthdate']=date('y-m-d', $result['result']->birthdate);
            $param['dead']=$result['result']->dead;
            $controller->render('view/edit.php', $param);
            //include('view/edit.php');
            break;
        case "update":
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
            break;
        case "show":
            $id = array_shift($arguments);
            $controller->setRequestParameters($arguments);
            if(is_numeric($id))
                $id=(int)$id;
            else{
                echo "<a href='/index.php/new/'>new person</a>";
                $id="all";
            }
            $result = $controller->show($id);
            if($result['error']) continue;
            if(!is_array($result['result'])){
                $params[]=$result['result'];
            }else
                $params=$result['result'];                
            foreach ($params as $param) {
                include 'view/show.php';
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
            break;
    }
    if ($result['error'])
        echo $result['message']."\n";
    ?>
</body>