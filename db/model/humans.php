<?php
namespace db\model;
require_once 'objects.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of humans
 *
 * @author reza
 */
/*
 * Won't do anything about repo('humans'), 
 * it'll be another object that chooses between models
 */
// Or maybe humans

class humans extends objects {
    protected $_tempArray=array('fname','lname', 'gender','fatherID','motherID','birthday','dead');
    //or db. I'll think on it after doing this.
    
    protected function __getSons(){
        
    }
    
    protected function __getDaughters(){
    }
    protected function __getChildern(){
        $temp=array();
        if($this->gender==true)
            $searchArray = array('fatherID','==',$this->_id);
        else
            $searchArray = array('motherID','==',$this->_id);
            
        $result=$this->_db->find($searchArray);

        foreach(array_keys($result) as $key){
            $temp[]=new humans($this->_db,$result[$key],$key);
        }
        
        return $temp;
        
    }
    protected function __isTheOldestInFamily(){
        
    }
    protected function __getOldestBrother(){
        
    }
    protected function __getOldestSister(){
        
    }
    protected function __getYoungestBrother(){
        
    }
    protected function __getYoungestSister(){
        
    }
    protected function dies(){
        $this->dead = true;
    }
    public function isSiblingOf(humans $p){
        if($this->motherID==$p->motherID || 
                $this->fatherID==$p->fatherID ){
            return true;
        }
        return false;
    }
    
    //A new father, have to be saved first, then it can get its ID?

    
    public function adopt(humans $p){
        $x=$this->gender;
        if($x==true){
            echo 'here';
            $p->fatherID=$this->_id;
        }elseif($x==false){
            echo 'there';
            $p->motherID=$this->_id;
        }
        
        // $son should be saved after this, it's developer's job.
    }
    
    public function __get($key){
        
        if($key=='getChildern'){
            $temp = $this->__getChildern();
            return $temp;
        }
        
        return parent::__get($key);
    }

}

?>
