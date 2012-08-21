<?php
namespace db\model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of repository
 *
 * @author reza
 */
class repository {
    protected $_repository;
    protected $_filepath = 'db/repository.db';
    protected $_hasLock = false;

    //or db. I'll think on it after doing this.
    public function __construct() {
        $database = new \db\adaptor\Json($this->_filepath);
        $this->_repository = $database;
    }
    protected function __getLock(){
        if ($this->_hasLock) return true;
        
        $lock= $this->_repository->getLockForWrite();
        if(!$lock){
            // Better to throw exception;
            echo 'DB is unavailable';
//            die;
        }
        $this->_hasLock=$lock;
        return $lock;
    }
    
    protected function __releaseLock(){
        $this->_repository->releaseLockForWrite();        
    }

    public function create($array = null){
        $lock = $this->__getLock();

        $temp = new humans($this->_repository,$array);
        //Change lock to some other function;
        return $temp;
    }

    public function getAll(){
        $lock = $this->__getLock();
        $tempArray=array();
        $array= $this->_repository->getAll();
        if(empty($array))
            return array();
        foreach ($array as $key => $arr) {
            $tempArray[]=new humans($this->_repository,$arr, $key);
            
        }
        return $tempArray;  
    }
    // returns a humans class;
    public function get($id){
        $lock = $this->__getLock();
        $array = $this->_repository->get($id);
        if(empty($array))
            throw new \Exception("$id was not found");
        else{
            $temp = new humans($this->_repository,$array,$id);
            return $temp;
        }
        
    }
    
    public function cancelChanges(){
        $this->__releaseLock();
        $this->_repository = new \db\adaptor\Json($this->_filepath);
    }

}

?>
