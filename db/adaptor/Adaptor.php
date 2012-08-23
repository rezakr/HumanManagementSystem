<?php
namespace db\adaptor;
/**
 * Description of dbAdaptor
 *
 * @author reza
 */

define('__NumberOfTriesForLuck',1); 

abstract class Adaptor {
    protected $_path;
    protected $_array;
    protected $_file;
    protected $_lock=false;
    public function __construct($filePath) {
        $this->_array = array();
        $this->_path=$filePath;
        // should load the path to $db, but for now it's just an empty array
        $this->_updateArray();
    }
    public function __destruct(){
        $this->_unlockDB();
    }
    abstract protected function _encode($array);

    abstract protected function _decode($array);

    protected function _has($id){
//        if(sizeof($this->_array)==0)
//            return false;
        if(isset($this->_array[$id]))
            if($this->_array[$id]==null)
                return false;
            else 
                return true;
        else
            return false;
    }
    
    protected function _updateArray(){
        if(file_exists($this->_path)){
            $fileContent = file_get_contents($this->_path);
            $this->_array = $this->_decode($fileContent);
        }else{
            $this->_array = array();
        }
    }
    
    protected function _lockDB(){
        $tempLock = false;
        if($this->_lock)
            return $this->_lock;
        $this->_file = fopen($this->_path,'a');  
        $tempLock= flock($this->_file, LOCK_EX);
        $this->_lock = $tempLock;
        return $tempLock;
    }
    protected function _unlockDB(){
        if(is_null($this->_file))
            return;
        if(file_exists($this->_path)){
            $this->_lock = false;
            flock($this->_file, LOCK_UN);
            fclose($this->_file); 
            $this->_file = null;
        }else{
            //Error in unlocking the wrong file
        }
    }
    public function getAll(){
        $tempArray = array();
        $i=0;
        if(empty($this->_array)) 
            return $tempArray;
        foreach ($this->_array as $arr){
            $tempSingleEntry = $arr;
            if($tempSingleEntry != null) 
                $tempArray[$i] = $tempSingleEntry;
            $i++;
        }
        return $tempArray;
    }
        
    public function get($id){
        if($this->_has($id)){
            return $this->_array[$id];
        }else{
            return null;
            // Maybe some error/exception?
        }
    }
    
    public function add($data){
        // should I check the structure of $data too?
        $this->_array[]=$data;
        return (sizeof($this->_array)-1);
    }
    
    public function update($id, $data){
        // Maybe should change next line with get();
        if($this->_has($id)){
            foreach ($data as $key => $value){
                $this->_array[$id][$key]=$value;            
            }
        }else{
            // Maybe some error/exception?
        }
    }

    public function delete($id){
        if($this->_has($id))
            $this->_array[$id]=null;
    }

    public function save(){
        file_put_contents($this->_path, $this->_encode($this->_array));        
    }
    
    public function recursiveFind($array, $params){
        if(sizeof($params)==0) {
            return $array;
        }
        $param = array_pop($params);
        if(sizeof($param)!=3)
            throw new \Exception("Wrong parameters in Find");

        $oldArray= $this->recursiveFind($array, $params);
        $tempArray = array();
        if(sizeof($oldArray)==0 ) 
            return $tempArray;
        foreach ($oldArray as $key => $arr) {
            if(!isset($arr[$param[0]]))
                continue;
            switch ($param[1]){
                case ">":
                    if($arr[$param[0]]>$param[2])
                        $tempArray[$key] = $arr;
                    break;
                case ">=":
                    if($arr[$param[0]]>$param[2] or $arr[$param[0]]==$param[2])
                        $tempArray[$key] = $arr;
                    break;
                case "<":
                    if($arr[$param[0]]<$param[2])
                        $tempArray[$key] = $arr;
                    break;
                case "<=":
                    if($arr[$param[0]]<$param[2] or $arr[$param[0]]==$param[2])
                        $tempArray[$key] = $arr;
                    break;
                case "==":
                    if($arr[$param[0]]==$param[2])
                        $tempArray[$key] = $arr;
                    break;
            }
        }
        unset($array);
        return $tempArray;
    }
    
    public function find($param){
        $query = $this->recursiveFind($this->_array, $param);
        if(sizeof($query)==0)
            return null;
        return $query;
        
    }
    
    public function getLockForWrite(){
        $lock = false;
        $i = 0;
        while ($i != __NumberOfTriesForLuck) {
            $lock = $this->_lockDB();
            if ($lock) {
                // Updating DB here
                $this->_updateArray();
                break;
            }
            $i++;
            sleep(0.01);
        }

        return $lock;
    }
    public function releaseLockForWrite(){
        $this->_unlockDB();
    }
}

?>
