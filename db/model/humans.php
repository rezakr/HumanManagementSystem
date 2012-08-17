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
    
    protected function __isOrphan(){
        
    }
    protected function __getSons(){
        $params = $this->__addParentGenderToSearch();
        $params[] = array('gender','==',1);
        return $this->__searchInDB($params);                
    }
    protected function __addParentGenderToSearch($params=null){
        if($this->gender==true)
            $searchQuery = array('fatherID','==',$this->_id);
        else
            $searchQuery = array('motherID','==',$this->_id);
        if($params==null) $params = array(); 
        $params[] = $searchQuery;
        return $params;
    }
    protected function __searchInDB($params){
        $temp=array();

        $result=$this->_db->find($params);
        
        foreach(array_keys($result) as $key){
            $temp[]=new humans($this->_db,$result[$key],$key);
        }
        return $temp;
    }
    
    protected function __getDaughters(){
        $params = $this->__addParentGenderToSearch();
        $params[] = array('gender','==',0);
        return $this->__searchInDB($params);        
    }
    
    protected function __getChildren(){
        $params = $this->__addParentGenderToSearch();
        return $this->__searchInDB($params);
        
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
        // $son will get father's lastName? 
        // if it's the case, it have to be recursive for all other sons/daughters
        // $son should be saved after this, it's developer's job.
    }
    public function __toString(){
        $str="id " . $this->id;
        $str.= 'name : ' .$this->fname;
        $str.= ' last name : ' .$this->lname;
        $str.= ' gender : '; 
        if ($this->gender == true) $str.= ' male ';
        if ($this->gender == false) $str.= 'female';
        
        $str.= ' fatherID : ' .$this->fatherID;
        $str.= ' motherID : ' .$this->motherID;
        $str.= ' birthday : ' .$this->birthday;
        $str.= ' and is dead? : ' .$this->dead;
        $str.="!";
        echo $str;
        return $str;
    }
}

?>
