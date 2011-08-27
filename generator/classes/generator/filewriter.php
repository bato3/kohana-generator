<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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

    public function addRows($rows) {
        if (!empty($row)) {
            $this->rows = array_merge($this->rows, $rows);
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

    private function mkdir($path) {
        @mkdir($path);
        @chmod($path, 0777);
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
        
        $dirpath = $this->getApplicationPaths($mod);

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
