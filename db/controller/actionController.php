<?php
namespace db\controller;

/**
 * Description of controller
 *
 * A simple framework controller that will be extended in other controllers and
 * will run the actions.
 * 
 * @author reza
 */

class actionController{
    protected $_view;
    protected $_repository;
    protected $_arguments;
    protected $_baseName;
    public function __construct($arguments = null){
        $this->_baseName = lcfirst(basename(get_class($this),"Controller"));

        $this->_repository = new \db\model\repository();
        echo "<a href='/index/create/'>new person</a>";

        $this->_arguments = array();
        $id = null;
        if(!empty($arguments)){
            $action = array_shift($arguments);
            if(!empty($arguments)){
                $id = array_shift($arguments);
            }
        }else{
            $action ="show"; 
            $id = "all";
        }
        $this->setRequestParameters($arguments);
        $this->_view['error']=false;
        $this->_view['errorMessage']=null;
        $this->_view['body']=null;
        $this->_view['result']=null;
        $this->$action($id);
    }
    public function __call($method, $args) {
        $functionName = ucwords($method)."Action";
        if(method_exists($this, $functionName)){
                $result=call_user_func(array($this, $functionName),$args);
                $this->_view['file']=APPLICATION_VIEW_PATH."/$this->_baseName/$method.php";
                /* Can change it to some other stance
                 * like running it on multi the first time, 
                 * and the $this->_view['result'] shouldn't have an array.
                 */
                if(isset($this->_view['multi'])){
                    if($this->_view['multi']){
                        $this->multiRender($this->_view['file'],$this->_view);
                        return;
                    }
                }
                $this->render($this->_view['file'], $this->_view);
        }
        else{
            $this->_view['error']=true;
            $parts = Explode("\\", __FILE__);
            $this->_view['errorMessage']="There's no action for $method in ".$parts[count($parts) - 1];        
            $this->_view['file']=APPLICATION_VIEW_PATH."/error.php";
            $this->render($this->_view['file'], $this->_view);
        }
    }
    public function render($view,$param=null){
        if(!file_exists($view)){
            $param['error']=true;
            $param['errorMessage']="$view is not created.";
            
        }
        if($param['error']){
            $view = APPLICATION_VIEW_PATH."/error.php";
            if(!file_exists($view)){
                echo 'No error page is created';
                die();
            }                           
        }

        include $view;
        if($param['error']){
            die();
        }

    }
    public function multiRender($view, $multiparam=null){
        if(!file_exists($view) || $multiparam['error']){
            $this->render($view,$multiparam);
            return;
        }
        if(!is_array($multiparam['result'])){
            $params[]=$multiparam['result'];
        }else
            $params=$multiparam['result'];                
        foreach ($params as $param) {
            $this->_view['result']=$param;
            $this->render($view,$this->_view);
        }

    }
        
    public function getRequestParameters() {
        return $this->_arguments;
    }

    public function setRequestParameters($arguments){
        $this->_view['body'] = "Working in setParameter of controller\n";
        if (!is_null($arguments) && is_array($arguments)){
            $this->_arguments = $arguments;
        }        
    }
    public function __destruct(){
        $this->_repository->cancelChanges();
    }
       
    public function setRequestPostParameters($arguments){
        $this->_result['error'] = false;
        $this->_result['body'] = "Working in construct of controller\n";
        $this->_result['message'] = "";

        if (!is_null($arguments) && is_array($arguments)){
            $temp=array();
            if(!empty($arguments))
                foreach ($arguments as $key => $value) {
                    if($value=="")
                        $temp[] = $key."=null";
                    else
                        $temp[] = $key."=".$value;
                }
            $this->_arguments = $temp;
        }
    }


}

?>

