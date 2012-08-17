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

    public function save(){
        if(is_null($this->_id))
            $this->_id=$this->_db->add($this->_array);
        else
            $this->_db->update($this->_id,$this->_array);
        $this->_db->save();
    }
    
    public function __get($key)
    {
        if($key=='id' && (!is_null($this->_id)))
            return $this->_id;
        if(isset($this->_array[$key])){
            echo 'key =' . $key ;
            echo ' value = '.$this->_array[$key];
            return $this->_array[$key];
        }
    }
    
    public function __set($key, $value)
    {
        if(array_key_exists($key, $this->_array))
        {
            $this->_array[$key] = $value;
        }
    }

}

?>
