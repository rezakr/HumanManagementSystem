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

        
    public function create(){
        $lock = $this->__getLock();

        $temp = new humans($this->_repository);
        //Change lock to some other function;
        return $temp;
    }

    // returns a humans class;
    public function get($id){
        $lock = $this->__getLock();
        $array = $this->_repository->get($id);
        if(empty($array))
            return null; // maybe an exception
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
