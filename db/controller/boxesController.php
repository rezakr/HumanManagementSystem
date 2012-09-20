<?php
namespace db\controller;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of boxesController
 *
 * @author reza
 */
class boxesController extends actionController{
    public function showAction($param=null){
        $id = $param[0];
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
            $this->_view['result'] = $this->_repository->get($id);
        }catch(\Exception $e){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = $e->getMessage();
            return;
        }    
    }
        
    public function showall(){
        $allBoxes = $this->_repository->getAll();
        $this->_view['result'] = $allBoxes;
        $this->_view['multi']=true;
        if(empty($allBoxes)){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = "Nothing in DB";
            return;
        }
    }
    
    public function createAction(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $this->setRequestPostParameters($_POST);
            parse_str(implode('&', $this->getRequestParameters()),$arguments);
            $new= $this->_repository->create($arguments);
            $this->_view['id'] = $new->save();
            $id=$this->_view['id'];
            $link = "Location: /boxes/show/$id/";
            header ($link);
        }
        $this->_view['title']="Create a new box";
        $this->_view['action']="/boxes/create/";      
        
    }
    //put your code here
    public function editAction($param=null){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $id=$_POST['id'];
            $edit = $_POST;
            $argv = $this->getRequestParameters();
            try{
                $updatePerson = $this->_repository->get((int)$id);
            }catch(\Exception $e){
                $this->_view['error'] = true;
                $this->_view['errorMessage'] = $e->getMessage();
                return;
            }
            $this->setRequestPostParameters($edit);
            $argv=$this->getRequestParameters();
            parse_str(implode('&', $argv),$arguments);
            $updatePerson->update($arguments); 
            $updatePerson->save();
            $link = "Location: /boxes/show/$id/";
            header ($link);
        }
        $id = $param[0];
        if(!is_numeric($id)){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = "Wrong Argument, there's no ID";            
            return;
        }
        $this->_view['title']="Editing box id $id ";
        $this->_view['action']="/boxes/edit/";
		
        try{
            $this->_view['result'] = $this->_repository->get((int)$id);
        }catch(\Exception $e){
            $this->_view['error'] = true;
            $this->_view['errorMessage'] = $e->getMessage();
            return;
        }

    }
}

?>
