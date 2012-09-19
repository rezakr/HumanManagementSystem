<?php
namespace db\controller;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indexController
 *
 * @author reza
 */
class indexController extends actionController{
    
    public function showAction($param=null){
        $id = $param[0];
        $arguments = $this->getRequestParameters();

        if(is_null($id))
            $id = "all";
        if(!is_numeric($id)){
            if($id == "all")
                $this->showAll();
            else{
                $this->_view['error'] = true;
                $this->_view['errorMessage'] = "Wrong Argument, can't work with show $id";
            }
            return;
        }
        try{
            $showPerson = $this->_repository->get($id);
        }catch(\Exception $e){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = $e->getMessage();
            return;
        }    
        if(empty($arguments)){
            $this->_view['body'] = "".$showPerson;
            $this->_view['result'] = $showPerson;
        }else{
            $functionName = array_shift($arguments);
            $this->_view['multi']=true;
            if($functionName[strlen($functionName)-1]=="?"){
                return $this->__callBooleanFunction($id, $functionName,$arguments);
            }            
            try {
                $resultValue = $showPerson->$functionName;            
            } catch (\Exception $e) {
                $this->_view['error'] = true;
                $this->_view['errorMessage'] = $e->getMessage();
                return $this->_view;
            }
            $this->_view['result'] = $resultValue;
            if(empty($resultValue)){
                $this->_view['error']=true;
                $this->_view['errorMessage']="Getting $functionName no result found";
            }else{
                $this->_view['body']="Getting $functionName \n";
                if(is_array($resultValue)){
                    foreach ($resultValue as $arr) {
                        $this->_view['body'].="\n".$arr;
                    }
                }else{
                    $this->_view['body'].="\n".$resultValue;                    
                }                
            }       
        }
    }
    public function showall(){
        $allHumans = $this->_repository->getAll();
        $this->_view['result'] = $allHumans;
        $this->_view['multi']=true;
        if(empty($allHumans)){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = "Nothing in DB";
            return;
        }
        $this->_view['body']="Getting all \n";
        foreach ($allHumans as $arr) {
            $this->_view['body'].="\n".$arr;
        }
        return;
            
    }    
    public function createAction(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $new = $_POST;
            $this->setRequestPostParameters($new);
            parse_str(implode('&', $this->getRequestParameters()),$arguments);
            $refinedArguments = $this->__refine($arguments);        
            $newPerson = $this->_repository->create($refinedArguments);
            $this->_view['id'] = $newPerson->save();
            $this->_view['body'] = "Person named $newPerson->fname was created with the ID $id";
            $id=$this->_view['id'];
            $link = "Location: /index/show/$id/";
            header ($link);
        }
        $this->_view['title']="Create a new human";
        $this->_view['action']="/index/create/";      
    }
    public function editAction($param){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $id=$_POST['id'];
            $edit = $_POST;
            $argv = $this->getRequestParameters();
            if(!is_numeric($id)){
                $this->_view['error'] = true;
                $this->_view['errorMessage'] = "Wrong Argument, there's no ID";            
                return;
            }
            try{
                $updatePerson = $this->_repository->get($id);
            }catch(\Exception $e){
                $this->_view['error'] = true;
                $this->_view['errorMessage'] = $e->getMessage();
                return;
            }
            if(empty($argv)){
//                $this->_view['error'] = true;
//                $this->_view['errorMessage'] = "No argument given";
//                return;
            }else{
                if($argv['0']=="adopt"){
                    $idSon = $argv[1];
                    if(is_numeric($idSon)){
                        try{
                            $son= $this->_repository->get((int)$idSon);
                        }catch(\Exception $e){
                            $this->_view['error'] = true;
                            $this->_view['errorMessage'] = $e->getMessage();
                            return;
                        }    
                        $updatePerson->adopt($son);
                        $son->save();
                    }else{
                        $this->_view['error']=true;
                        $this->_view['errorMessage']='Wrong argument for update';
                    }
                    $this->_view['body'] = "$idSon is now a child of $id";
                    return;
                }
                if($argv[0]=="dies"){
                    $updatePerson->dies();
                    $updatePerson->save();
                    $this->_view['body'] = "$updatePerson->lname is now dead"; 
                    return;
                }
            }
            $this->setRequestPostParameters($edit);
            $argv=$this->getRequestParameters();
            parse_str(implode('&', $argv),$arguments);
            $refinedArguments = $this->__refine($arguments);

            $updatePerson->update($refinedArguments); 
            $updatePerson->save();
            $this->_view['body'] = "Updated id=$id \n". $updatePerson;

            if($this->_view['error'])
                return;
            $link = "Location: /index/show/$id/";
            header ($link);
        }
        $id = $param[0];
        if(!is_numeric($id)){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = "Wrong Argument, there's no ID";            
            return;
        }
        $this->_view['title']="Editing human id $id ";
        $this->_view['action']="/index/edit/";
        try{
            $updatePerson = $this->_repository->get($id);
        }catch(\Exception $e){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = $e->getMessage();
            return;
        }
        $this->_view['result']=$updatePerson;
    }

    private function __callBooleanFunction($id, $functionName,$argv=null){
        $functionName= "is".  substr_replace(ucwords($functionName),"",-1);
        $person = $this->_repository->get($id);
        if(empty($argv)){
            $result = $person->$functionName();
        }else{
            if(is_numeric($argv[0])){
                $secondPerson = $this->_repository->get((int)$argv[0]);
                $result = $person->$functionName($secondPerson);
            }
        }
        if($result)
            $this->_result['body']="true";
        else
            $this->_result['body']="false";
        return $this->_result;
    }
    private function __refine($array){
        $refinedArray = array();
        
        if(empty($array)){
            return $refinedArray;
        }
        foreach ($array as $key => $value) {
            switch ($key) {
                case "birthdate":
                    $date = new \DateTime($value);
                    $refinedArray[$key]=$date->getTimestamp();
                    break;
                case "gender":
                case "dead":
                    $newValue = (boolean)$value;
                    $refinedArray[$key]=$newValue;
                    break;
                // Should check for father and mother in DB?
                case "fatherID":
                case "motherID":
                    if(is_numeric($value)){
                        $newValue = (int)$value;
                        $refinedArray[$key]=$newValue;
                    }
                    break;
                default:
                    $refinedArray[$key]=$value;
                    break;
            }
        }
        return $refinedArray;
    }  
}

?>
