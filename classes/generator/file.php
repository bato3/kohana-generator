<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_File {
    
    private $lines;
    private $file_name;
    private $ext;
    private $dir;
    private $disable_close_tag = false;
    
    public static $PHP = "php";
    public static $JS = "js";
    public static $CSS = "css";
    
    public function __construct() {
        $this->lines = array();
        $this->ext = self::$PHP;
    }
    
    public function addLine($line, $space=0){
        $this->lines[] = Generator_Util_Text::space($space).$line;
        return $this;
    }
    
    public function addLines(array $lines){
        array_merge($this->lines, $lines);
        return $this;
    }
        
    public function getLines(){
        return $this->lines;
    }
    
    public function count(){
        return count($this->lines);
    }
    
    public function hasLines(){
        return 0 < $this->count() ? true : false;
    }
    
    public function hasFileName(){
        return empty($this->file_name) ? false : true;
    }
    
    public function hasFileExt(){
        return empty($this->ext) ? false : true;
    }
    
    public function hasDirectory(){
        return empty($this->dir) ? false : true;
    }
    
    public function setFileName($file_name){
        $this->file_name = $file_name;
        return $this;
    }
    
    public function getFileName(){
        return $this->file_name;
    }
    
    public function setFileExt($ext){
        $this->ext = $ext;
        return $this;
    }
    
    public function getFileExt(){
        return $this->ext;
    }

    public function setDirectory($dir){
        $this->dir = $dir;
        return $this;
    }
    
    public function getDirectory(){
        return $this->dir;
    }
    
    public static function factory(){
        return new Generator_File();
    }
    
    public function getFilePath(){
        if($this->hasFileExt() && $this->hasFileName()){
            return $this->getDirectory().DIRECTORY_SEPARATOR.$this->getFileName().".".$this->getFileExt();
        }else{            
            return $this->getDirectory();
        }
    }
    
    public function file_exists(){
        return file_exists(DOCROOT.DIRECTORY_SEPARATOR.$this->getFilePath());
    }
    
    public function dir_exists(){
        return file_exists(DOCROOT.DIRECTORY_SEPARATOR.$this->getDirectory());
    }
    
    public function pathIsWritable(){
        return is_writable($this->getFilePath());
    }
    
    public function setDisableCloseTag($boolean){
        $this->disable_close_tag = $boolean;
        return $this;
    }
    
    public function disableCloseTag(){
        return $this->disable_close_tag;
    }
    
}

?>
