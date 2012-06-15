<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of filewriter
 *
 * @author burningface
 */
class Generator_Filewriter extends Generator_File {

    private $filename;
    private $name;
    private $path;
    private $rows = array();
    private $write_is_ok = false;
    private $error = null;
    private $user_spec_path;

    public function __construct($filename=null, $disable_php_extension=false) {
        if (!empty($filename) && !$disable_php_extension) {
            $this->name = $filename;
            $this->filename = strtolower($filename) . ".php";
        } else if (!empty($filename) && $disable_php_extension) {
            $explode = explode(".", $filename);
            $this->name = $explode[0];
            $this->filename = strtolower($filename);
        }
    }

    public function addRow($row) {
        if (!empty($row)) {
            $this->rows[] = $row;
        }
    }

    public function getRows() {
        return $this->rows;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getPath() {
        return $this->path;
    }

    public function getName() {
        return $this->name;
    }

    public function writeIsOk() {
        return $this->write_is_ok;
    }

    public function userSpecPath($path) {
        $this->user_spec_path = $path.DIRECTORY_SEPARATOR;
    }
    
    public function mkdir($path) {
        $result=false;
        if(!file_exists($path)){
            $result = @mkdir($path);
            @chmod($path, 0777);
        }
        return $result;
    }

    private function writeFile($path) {
        $fh = null;
        if ($path != null) {
            
            if (!file_exists($path)) {
                
                $fh = fopen($path, "w");
                foreach ($this->rows as $row) {
                    fwrite($fh, $row . "\n");
                }
                fclose($fh);
                @chmod($path, 0777);
                $this->write_is_ok = true;
                return true;
                
            }else{
                
                $this->error =  "<div class=\"error\">File exists: <cite>$path</cite> Please delete first!</div>";
                return false;
            }
            
        }else{
            
            $this->error = "Path is empty !";
            return false;
        }
    }

    public function write($mod=1) {
        
        if($mod != Generator_Filewriter::$USER_SPECIFIES_IT){
            $dirpath = $this->getApplicationPaths($mod);
        }else{
            $dirpath = $this->user_spec_path;
        }

        if (!isset($this->filename)) {
            if (!file_exists($dirpath)) {

                $this->mkdir($dirpath);
                $this->write_is_ok = true;
                
            } else {

                $this->error = "<div class=\"error\">Directory exists: <cite>$dirpath</cite> Please delete first!</div>";
            }
        } else {
            if(!file_exists($dirpath)){
                $this->mkdir($dirpath);
            }
            if (is_writable($dirpath)) {

                $dirpath .= $this->filename;
                $this->writeFile($dirpath);
                
            } else {
                $this->error = "<div class=\"error\"><cite>$dirpath</cite> Is not writable!</div>";
            }
        }

        $this->path = empty($this->error) ? $dirpath : $this->error;
    }

}

?>
