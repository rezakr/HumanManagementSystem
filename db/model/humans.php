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
    protected $_tempArray=array('fname','lname', 'gender','fatherID','motherID','birthdate','dead');
    //or db. I'll think on it after doing this.
    
    public function isOrphan(){
       $father = $this->__getFather();
       $mother = $this->__getMother();
       
       if($father->dead && $mother->dead)
           return true;
       else 
           return false;
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
        
        if(sizeof($result)==0 ) return $temp;
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
    protected function __getFather(){
        $fatherQuery = $this->_db->get($this->fatherID);
        if (is_null($fatherQuery)) return null;
        return new humans($this->_db, $fatherQuery, $this->fatherID);        
    }
    protected function __getMother(){
        $motherQuery = $this->_db->get($this->motherID);
        if (is_null($motherQuery)) return null;
        return new humans($this->_db, $motherQuery, $this->motherID);                
    }
    protected function __getDateOfBirth(){
        if(!is_null($this->_array['birthdate']))            
            return date('Y-m-d', $this->birthdate);
        return null;
    }
    protected function __setBirthdate($arg){
        $newBirthdate = $arg;

        $mother = $this->__getMother();
        $father = $this->__getFather();

        if(!is_null($mother))
            if($this->__hasBirthdayGap($mother->birthdate, $newBirthdate))
                    throw new \Exception("You can't do that! the gap between mother's birthday and child is too low");

        if(!is_null($father))
            if($this->__hasBirthdayGap($father->birthdate, $newBirthdate))
                    throw new \Exception("You can't do that! the gap between father's birthday and child is too low");

        $children = $this->__getChildren();
        if(sizeof($children)!=0){
            foreach ($children as $child) {
                if($this->__hasBirthdayGap($newBirthdate, $child->birthdate)){
                        throw new \Exception("You can't do that! the gap between parents' birthday and child is too low");
                }
            }
        }   
        $this->_array['birthdate']=$newBirthdate;
        var_dump($arg);
        echo 'I am here';
    }
    
    
    protected function __getChildren(){
        $params = $this->__addParentGenderToSearch();
        return $this->__searchInDB($params);
        
    }
    public function isTheOldestInFamily(){
        $father = $this->__getFather();
        // No parent to decide/can throw exception too.
        if($father == null) 
            return true;
        if($father->dead == 0)
            return false;
        $children = $father->chidren;
        foreach ($children as $child) {
            if($this->birthdate < $child->birthdate)
                if($child->dead == 0)
                    return false;
        }
        return true;
    }
    protected function __getOldestBrother(){
        $father = $this->__getFather();
        // No parent to decide/can throw exception too.
        if($father == null) 
            return null;
        $sons = $father->sons;
        if(empty($sons)) 
            return null;

        $oldestBrother = $sons[0];
        foreach ($sons as $son) {
            if($oldestBrother->birthdate > $son->birthdate)
                $oldestBrother = $son;
        }
        return $oldestBrother;        
    }
    protected function __getOldestSister(){
        $father = $this->__getFather();
        // No parent to decide/can throw exception too.
        if($father == null) 
            return null;
        $daughters = $father->daughters;
        if(empty($daughters)) 
            return null;
        $oldestSister = $daughters[0];
        foreach ($daughters as $daughter) {
            if($oldestSister->birthdate > $daughter->birthdate)
                $oldestSister = $daughter;
        }
        return $oldestSister;        
        
    }    
    protected function __getYoungestBrother(){
        $father = $this->__getFather();
        // No parent to decide/can throw exception too.
        if($father == null) 
            return null;
        $sons = $father->sons;
        if(empty($sons)) 
            return null;
        $youngestBrother = $sons[0];
        foreach ($sons as $son) {
            if($youngestBrother->birthdate < $son->birthdate)
                $youngestBrother = $son;
        }
        return $youngestBrother;        
        
    }
        
    protected function __getYoungestSister(){
        $father = $this->__getFather();
        // No parent to decide/can throw exception too.
        if($father == null) 
            return null;
        $daughters = $father->daughters;
        if(empty($daughters)) 
            return null;
        $youngestSister = $daughters[0];
        foreach ($daughters as $daughter) {
            if($youngestSister->birthdate < $daughter->birthdate)
                $youngestSister = $daughter;
        }
        return $youngestSister;         
    }
    protected function __hasBirthdayGap($parent, $child){
        if(is_null($parent) || is_null($child))
            return false;
        if($parent + (10*3600*24*365) > $child ){
            echo 'yes has a birthday gap';
            return true;
        }
        return false;
    }
    public function dies(){
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
        if(!is_null($this->_array['fname']))
            $str.= " name : " .$this->fname;
        else
            $str.= " Unknown name";
        if(!is_null($this->_array['lname']))
            $str.= "\t last name : " .$this->lname;
        else 
            $str.="\t\t";

        if(!is_null($this->_array['gender'])){
            $str.= "\t gender : "; 
            if ($this->gender == true) $str.= ' male ';
            if ($this->gender == false) $str.= 'female';
        }else 
            $str.="\t\t";
        if(!is_null($this->_array['fatherID']))
            $str.= "\t fatherID : " .$this->fatherID;
        else 
            $str.="\t\t";
        if(!is_null($this->_array['motherID']))
            $str.= "\t motherID : " .$this->motherID;
        else 
            $str.="\t\t";
        if(!is_null($this->_array['birthdate']))            
//            $str.= "\t birthdate : " . date('Y-m-d', $this->birthdate);
            $str.= "\t birthdate : " . $this->dateOfBirth;
        else 
            $str.="\t\t";
        if(!is_null($this->_array['dead'])){
            if ($this->dead == true) $str.= "\t dead ";
            if ($this->dead == false) $str.= "\t alive";
        }
        else 
            $str.="\t\t";

        $str.="!";
        return $str;
    }
}

?>
