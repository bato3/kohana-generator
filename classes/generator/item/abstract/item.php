<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 * Description of item
 *
 * @author burningface
 */
abstract class Generator_Item_Abstract_Item {

    protected $config;
    private $items;
    private $errors;
    private $skipped_files;
    private $skipped_dirs;
    private $generated_files;
    private $generated_dirs;

    public function __construct() {
        $this->items = array();
        $this->errors = array();
        $this->skipped_files = array();
        $this->skipped_dirs = array();
        $this->generated_files = array();
        $this->generated_dirs = array();
        $this->config = Generator_Util_Config::load();
        $this->init();
    }

    public function add(Generator_File $item) {
        $this->items[] = $item;
        return $this;
    }

    public function addErrors($string) {
        $this->errors[] = $string;
        return $this;
    }

    public function addSkippedFile($string) {
        $this->skipped_files[] = $string;
        return $this;
    }

    public function addSkippedDir($string) {
        $this->skipped_dirs[] = $string;
        return $this;
    }

    public function addGeneratedFile($string) {
        $this->generated_files[] = $string;
        return $this;
    }

    public function addGeneratedDir($string) {
        $this->generated_dirs[] = $string;
        return $this;
    }

    public function get() {
        return $this->items;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getSkippedFiles() {
        return $this->skipped_files;
    }

    public function getSkippedDirs() {
        return $this->skipped_dirs;
    }

    public function getGeneratedFiles() {
        return $this->generated_files;
    }

    public function getGeneratedDirs() {
        return $this->generated_dirs;
    }

    public function count() {
        return (int) count($this->items);
    }

    public function isEmpty() {
        return 0 < $this->count() ? false : true;
    }

    protected abstract function init();
}

?>
