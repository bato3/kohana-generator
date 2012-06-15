<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of result
 *
 * @author burningface
 */
class Generator_Result {
    
    private $items = array();
    private $write_ok = array();
    
    public function getItems() {
        return $this->items;
    }
    
    public function addWriteIsOk($ok){
        array_push($this->write_ok, $ok);
    }
    
    public function writeIsOK(){
        return in_array(false, $this->write_ok) ? false : true;
    }
    
    public function addItem($key,$path,$rows=null){
        if(!empty ($rows)){
            $this->items[$key] = array($path,$rows);
        }else{
            $this->items[$key] = array($path, $path);
        }
    }
    
}

?>
