<?php
namespace db\model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of objects
 *
 * @author reza
 */

abstract class objects {
    protected $_db;
    protected $_tempArray;
    protected $_id;
    protected $_array;
    //put your code here
    
    public function __construct(\db\adaptor\Json $db,$array=null, $id=null) {
        $this->_db = $db;
        $this->_id=$id;
        $this->_createTempArray($array);
    }
    protected function _createTempArray($array=null){
        foreach ($this->_tempArray as $value) {
            $this->_array[$value]=null;
            if($array!=null){
                if(isset($array[$value]))
                    $this->_array[$value]=$array[$value];
            }
        }
    }
    public function update($array){
        foreach ($this->_array as $key => $value) {
            if(isset($array[$key]))
                $this->_array[$key] = $array[$key];
        }
    }
    public function save(){
        $id = $this->_id;
        if(is_null($this->_id)){
            var_dump($this->_array);
            $id = $this->_id=$this->_db->add($this->_array);
            
        }
        else
            $this->_db->update($this->_id,$this->_array);
        $this->_db->save();
        $this->_db->releaseLockForWrite();
        return $id;
    }
    
    public function __get($key)
    {
        $functionName = '__get'.ucwords($key);
        if(method_exists($this, $functionName))
                return call_user_func(array($this, $functionName));
                //return call_user_func('self::' . $functionName);
        if($key=='id' && (!is_null($this->_id)))
            return $this->_id;
        if(isset($this->_array[$key])){
            return $this->_array[$key];
        }
        if(in_array($key, $this->_tempArray)){
            return null;
        }
        throw new \Exception("$key wasn't found");
    }
    
    public function __set($key, $value)
    {
        $functionName = '__set'.ucwords($key);
        if(method_exists($this, $functionName))
                return call_user_func(array($this, $functionName),$value);
                //return call_user_func('self::' . $functionName);

        if(array_key_exists($key, $this->_array))
        {
            $this->_array[$key] = $value;
        }
    }



}

?>
