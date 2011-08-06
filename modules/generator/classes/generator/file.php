<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author burningface
 */
class Generator_File {

    public static $MODEL = 1;
    public static $FORM = 2;
    public static $CONTROLLER = 3;
    public static $ASSETS = 4;
    public static $ASSETS_CSS = 5;
    public static $ASSETS_JS = 6;
    public static $ASSETS_IMG = 7;
    public static $LIST = 8;
    public static $I18n = 9;

    protected function getApplicationPaths($mod) {
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
            case 8 :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "lists" . DIRECTORY_SEPARATOR;
                break;
            case 9 :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "i18n" . DIRECTORY_SEPARATOR;
                break;
            default :
                return DOCROOT . "application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR;
        }
    }

    public function fileExists($file, $mod=1) {
        return file_exists($this->getApplicationPaths($mod) . $file);
    }

    public function isPHPFile($file) {
        return "text/x-php" == mime_content_type($file) ? true : false;
    }
    
}

?>
