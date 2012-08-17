<?php
namespace db\adaptor;
/**
 * Description of dbAdaptor
 *
 * @author reza
 */

define('__NumberOfTriesForLuck',16); 

abstract class Adaptor {
    protected $path;
    protected $array;
    protected $file;
    public function __construct($filePath) {
        $this->array = array();
        $this->path=$filePath;
        // should load the path to $db, but for now it's just an empty array
        $this->_updateArray();
    }
    
    abstract protected function _encode($array);

    abstract protected function _decode($array);

    protected function _has($id){
        if(isset($this->array[$id]))
            if($this->array[$id]==null)
                return false;
            else 
                return true;
        else
            return false;
    }
    
    protected function _updateArray(){
        if(file_exists($this->path)){
            $fileContent = file_get_contents($this->path);
            $this->array = $this->_decode($fileContent);
        }else{
            $this->array = array();
        }
    }
    
    protected function _lockDB(){
        $tempLock = false;
        $this->file = fopen($this->path,'a'); 
        $tempLock= flock($this->file, LOCK_EX);
        return $tempLock;
    }
    protected function _unlockDB(){
        if(file_exists($this->path)){
            flock($this->file, LOCK_UN);
            fclose($this->file); 
        }else{
            //Error in unlocking the wrong file
        }
    }
    public function getAll(){
        $tempArray = array();
        $i=0;
        if(sizeof($this->array==0)) return $tempArray;
        foreach ($this->array as $arr){
            $tempSingleEntry = $arr;
            if($tempSingleEntry != null) 
                $tempArray[$i] = $tempSingleEntry;
            $i++;
        }
        return $tempArray;
    }
        
    public function get($id){
        if($this->_has($id)){
            return $this->array[$id];
        }else{
            return null;
            // Maybe some error/exception?
        }
    }
    
    public function add($data){
        // should I check the structure of $data too?
        $this->array[]=$data;
        return sizeof($this->array);
    }
    
    public function update($id, $data){
        // Maybe should change next line with get();
        if($this->_has($id)){
            foreach ($data as $key => $value){
                $this->array[$id][$key]=$value;            
            }
        }else{
            // Maybe some error/exception?
        }
    }

    public function delete($id){
        if($this->_has($id))
            $this->array[$id]=null;
    }

    public function save(){
        file_put_contents($this->path, $this->_encode($this->array));        
    }
    
    public function find($param){
        $tempArray = array();
        
        $i=0;
        foreach ($this->array as $arr){
            if(!isset($arr[$param[0]])){
                $i++;
                continue;
            }
            switch ($param[1]){
                case ">":
                    if($arr[$param[0]]>$param[2])
                        $tempArray[$i] = $arr;
                    break;
                case ">=":
                    if($arr[$param[0]]>$param[2] or $arr[$param[0]]==$param[2])
                        $tempArray[$i] = $arr;
                    break;
                case "<":
                    if($arr[$param[0]]<$param[2])
                        $tempArray[$i] = $arr;
                    break;
                case "<=":
                    if($arr[$param[0]]<$param[2] or $arr[$param[0]]==$param[2])
                        $tempArray[$i] = $arr;
                    break;
                case "==":
                    if($arr[$param[0]]==$param[2])
                        $tempArray[$i] = $arr;
                    break;
            }
            $i++;
        }
        return $tempArray;

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
