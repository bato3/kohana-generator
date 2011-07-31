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
class Generator_Filewriter {

    public static $MODEL = 1;
    public static $FORM = 2;
    public static $CONTROLLER = 3;
    public static $ASSETS = 4;
    public static $ASSETS_CSS = 5;
    public static $ASSETS_JS = 6;
    public static $ASSETS_IMG = 7;
    private $filename;
    private $name;
    private $path;
    private $rows = array();
    private $write_is_ok = false;

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
    
    private function getApplicationPaths($mod){
        switch ($mod) {
            case 1 :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR;
                break;
            case 2 :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "forms" . DIRECTORY_SEPARATOR;
                break;
            case 3 :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "controller" . DIRECTORY_SEPARATOR;
                break;
            case 4 :
                return DOCROOT . "assets" . DIRECTORY_SEPARATOR;
                break;
            case 5 :
                return DOCROOT . "assets" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR;
                break;
            case 6 :
                return DOCROOT . "assets" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR;
                break;
            case 7 :
                return DOCROOT . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR;
                break;
            default :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR;
        }
    }

    public function write($mod=1) {
        $fh = null;
        $dirpath = null;
        $error = null;
        $dirpath = $this->getApplicationPaths($mod);
        
        if (!file_exists($dirpath)) {
            @mkdir($dirpath);
            @chmod($dirpath, 0777);
        } else if (empty($this->filename)) {
            $error = "<div class=\"error\">Directory exists: <cite>$dirpath</cite> Please delete first!</div>";
        }
        
        if (is_writable($dirpath)) {
            if (isset($this->filename)) {
                $dirpath .= $this->filename;
                if (!file_exists($dirpath)) {
                    if ($dirpath != null) {
                        $fh = fopen($dirpath, "w");
                        foreach ($this->rows as $row) {
                            fwrite($fh, $row . "\n");
                        }
                        fclose($fh);
                        @chmod($dirpath, 0777);
                        $this->write_is_ok = true;
                    }
                } else {
                    $error = "<div class=\"error\">File exists: <cite>$dirpath</cite> Please delete first!</div>";
                }
            }
        } else {
            $error = "<div class=\"error\"><cite>$dirpath</cite> Is not writable!</div>";
        }

        $this->path = empty($error) ? $dirpath : $error;
    }

    public function fileExists($file, $mod=1){
        return file_exists($this->getApplicationPaths($mod) . $file);
    }
}

?>
