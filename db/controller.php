<?php
namespace db;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author reza
 */
class controller {
    protected $_result;
    protected $_repository;
    protected $_arguments;
    
    public function __construct($arguments = null){
        $this->_repository = new \db\model\repository();
        $this->_arguments = array();
        $this->setRequestParameters($arguments);
    }
    
    public function getRequestParameters() {
        return $this->_arguments;
    }
    public function setRequestPostParameters($arguments){
        $this->_result['error'] = false;
        $this->_result['body'] = "Working in construct of controller\n";
        $this->_result['message'] = "";

        if (!is_null($arguments) && is_array($arguments)){
            $temp=array();
            if(!empty($arguments))
                foreach ($arguments as $key => $value) {
                    if($value=="")
                        $temp[] = $key."=null";
                    else
                        $temp[] = $key."=".$value;
                }
            $this->_arguments = $temp;
        }        
        
    }
    public function setRequestParameters($arguments){
        $this->_result['error'] = false;
        $this->_result['body'] = "Working in construct of controller\n";
        $this->_result['message'] = "";

        if (!is_null($arguments) && is_array($arguments)){
            $this->_arguments = $arguments;
        }        
    }
    public function __destruct(){
        $this->_repository->cancelChanges();
    }
    public function render($view,$param=null){
        include $view;
    }
    public function create(){
        var_dump(implode('&', $this->getRequestParameters()));
        parse_str(implode('&', $this->getRequestParameters()),$arguments);
        // Have to check to the arguements;
        
        $refinedArguments = $this->__refine($arguments);
        
        $newPerson = $this->_repository->create($refinedArguments);
        $id = $newPerson->save();
        $this->_result['id'] = $id;
        $this->_result['body'] = "Person named $newPerson->fname was created with the ID $id";
        return $this->_result;
    }
    
    public function update($id){
        $argv = $this->getRequestParameters();
        if(!is_numeric($id)){
            $this->_result['error'] = true;
            $this->_result['message'] = "Wrong Argument, there's no ID";            
            return $this->_result;
        }
        parse_str(implode('&', $argv),$arguments);
        try{
            $updatePerson = $this->_repository->get($id);
        }catch(\Exception $e){
            $this->_result['error'] = true;
            $this->_result['message'] = $e->getMessage();
            return $this->_result;
        }
        if(empty($arguments)){
            $this->_result['error'] = true;
            $this->_result['message'] = "No argument given";
        }
        if($argv['0']=="adopt"){
            $idSon = $argv[1];
            if(is_numeric($idSon)){
                try{
                    $son= $this->_repository->get((int)$idSon);
                }catch(\Exception $e){
                    $this->_result['error'] = true;
                    $this->_result['message'] = $e->getMessage();
                    return $this->_result;
                }    
                $updatePerson->adopt($son);
                $son->save();
            }else{
                $this->_result['error']=true;
                $this->_result['Wrong argument for update'];
            }
            $this->_result['body'] = "$idSon is now a child of $id";
            return $this->_result;
        }
        if($argv[0]=="dies"){
            $updatePerson->dies();
            $updatePerson->save();
            $this->_result['body'] = "$updatePerson->lname is now dead"; 
            return $this->_result;
        }

        $refinedArguments = $this->__refine($arguments);

        $updatePerson->update($refinedArguments); 
        $updatePerson->save();
        $this->_result['body'] = "Updated id=$id \n". $updatePerson;
        return $this->_result;
    }
    public function showall(){
        $allHumans = $this->_repository->getAll();
        $this->_result['result'] = $allHumans;
        if(empty($allHumans)){
            $this->_result['error'] = true;
            $this->_result['message'] = "Nothing in DB";
            return $this->_result;
        }
        $this->_result['body']="Getting all \n";
        foreach ($allHumans as $arr) {
            $this->_result['body'].="\n".$arr;
        }
        return $this->_result;
            
    }
    public function show($id=null){
        $argv = $this->getRequestParameters();
        if(is_null($id))
            $id = "all";
        if(!is_numeric($id)){
            if($id == "all")
                $result = $this->showAll();
            else{
                $result['error'] = true;
                $result['message'] = "Wrong Argument, can't work with show $id";
            }
            return $result;
        }
        try{
            $showPerson = $this->_repository->get($id);
        }catch(\Exception $e){
            $this->_result['error'] = true;
            $this->_result['message'] = $e->getMessage();
            return $this->_result;
        }    
        if(empty($argv)){
            $this->_result['body'] = "".$showPerson;
            $this->_result['result'] = $showPerson;
        }else{
            $functionName = array_shift($argv);
            
            if($functionName[strlen($functionName)-1]=="?"){
                return $this->__callBooleanFunction($id, $functionName,$argv);
            }            
            try {
                $resultValue = $showPerson->$functionName;            
            } catch (\Exception $e) {
                $this->_result['error'] = true;
                $this->_result['message'] = $e->getMessage();
                return $this->_result;
            }

            $this->_result['result'] = $resultValue;
            if(empty($resultValue)){
                $this->_result['error']=true;
                $this->_result['message']="Getting $functionName no result found";
            }else{
                $this->_result['body']="Getting $functionName \n";
                if(is_array($resultValue)){
                    foreach ($resultValue as $arr) {
                        $this->_result['body'].="\n".$arr;
                    }
                }else{
                    $this->_result['body'].="\n".$resultValue;                    
                }
                
            }
            
        }
        
        return $this->_result;
        
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
                    if(is_int($value)){
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
