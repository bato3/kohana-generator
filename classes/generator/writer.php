<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Writer {

    private $config;
    private $lines;
    private $errors;
    private $skipped_files;
    private $skipped_dirs;
    private $generated_files;
    private $generated_dirs;

    public function __construct() {
        $this->lines = array();
        $this->errors = array();
        $this->skipped_files = array();
        $this->skipped_dirs = array();
        $this->generated_files = array();
        $this->generated_dirs = array();
        $this->config = Generator_Util_Config::load();
    }

    private function open(Generator_File $file) {
        $this->lines = $file->getLines();
    }

    private function php_open(Generator_File $file) {
        $this->lines = array();

        $this->lines[] = $this->config->start_php_file;
        $this->lines[] = $this->config->open_php;
        $this->lines[] = "/**";
        $this->lines[] = "* @package";
        $this->lines[] = "* @author " . $this->config->author;
        $this->lines[] = "* @license " . $this->config->license;
        $this->lines[] = "* @copyright (c) " . date("Y") . " ".$this->config->author;
        $this->lines[] = "*";
        $this->lines[] = "*/";
        $this->lines = array_merge($this->lines, $file->getLines());
    }

    private function php_close() {
        $this->lines[] = $this->config->close_php;
    }

    private function write(Generator_File $file) {
        $lines = "";
        if (!empty($this->lines)) {
            $count = count($this->lines);
            $i = 0;
            foreach ($this->lines as $line) {
                if ($i == $count - 1) {
                    $lines .= $line;
                } else {
                    $lines .= $line . "\n";
                }
                ++$i;
            }

            if(!$file->file_exists()){
                file_put_contents(DOCROOT.DIRECTORY_SEPARATOR.$file->getFilePath(), $lines);
                @chmod(DOCROOT.DIRECTORY_SEPARATOR.$file->getFilePath(), 0777);
                $this->addGeneratedFiles($file->getFilePath());
            }else {
                $this->addSkippedFiles($file->getFilePath());
            }

        } else {
            $this->addSkippedFiles($file->getFilePath());
        }
    }

    public function register(Generator_Item_Abstract_Item $generator) {
        if (!$generator->isEmpty()) {

            $files = $generator->get();

            foreach ($files as $file) {

                $this->mkdir($file);

                if (!$file->file_exists()) {

                    if ($file->hasLines() && $file->hasFileName() && $file->hasFileExt()) {

                        switch ($file->getFileExt()) {

                            case Generator_File::$JS :
                                $this->open($file);
                                $this->write($file);
                                break;

                            case Generator_File::$CSS :
                                $this->open($file);
                                $this->write($file);
                                break;

                            case Generator_File::$PHP :
                                $this->php_open($file);
                                if(!$file->disableCloseTag()){
                                    $this->php_close();
                                }
                                $this->write($file);
                                break;
                        }
                    } elseif (!$file->hasLines() && !$file->hasFileName() && $file->hasDirectory()) {
                        $this->mkdir($file);
                    }
                } else {
                    if ($file->hasFileExt()) {
                        $this->addSkippedFiles($file->getFilePath());
                    }
                }
            }
        }
        $this->addErrors($generator->getErrors());
        /*
          $this->addSkippedFiles($generator->getSkippedFiles());
          $this->addSkippedDirs($generator->getSkippedDirs());
          $this->addErrors($generator->getErrors());
          $this->addGeneratedFiles($generator->getGeneratedFiles());
          $this->addGeneratedDirs($generator->getGeneratedDirs());
         */

        return $this;
    }

    public function mkdir(Generator_File $file) {
        $dirs = explode(DIRECTORY_SEPARATOR, $file->getDirectory());
        $path = DOCROOT;
        foreach ($dirs as $dir) {
            if(!empty($dir)){
                $path .= $dir.DIRECTORY_SEPARATOR;
                if (!file_exists($path)) {
                    @mkdir($path);
                    @chmod($path, 0777);
                    $this->addGeneratedDirs($path);
                } else {
                    $this->addSkippedDirs($path);
                }
            }
        }        
        return $this;
    }

    private function addErrors($error) {
        if (is_array($error)) {
            $this->errors = array_merge($this->errors, $error);
        } else {
            $this->errors[] = $error;
        }
        return $this;
    }

    private function addSkippedFiles($skipped) {
        if (is_array($skipped)) {
            $this->skipped_files = array_merge($this->skipped_files, $skipped);
        } else {
            $this->skipped_files[] = $skipped;
        }
        return $this;
    }

    private function addSkippedDirs($skipped) {
        if (is_array($skipped)) {
            $this->skipped_dirs = array_merge($this->skipped_dirs, $skipped);
        } else {
            $this->skipped_dirs[] = $skipped;
        }
        return $this;
    }

    private function addGeneratedFiles($generated) {
        if (is_array($generated)) {
            $this->generated_files = array_merge($this->generated_files, $generated);
        } else {
            $this->generated_files[] = $generated;
        }

        return $this;
    }

    private function addGeneratedDirs($generated) {
        if (is_array($generated)) {
            $this->generated_files = array_merge($this->generated_files, $generated);
        } else {
            $this->generated_dirs[] = $generated;
        }
        return $this;
    }

    public function getFiles() {
        return $this->files;
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

    public static function factory() {
        return new Generator_Writer();
    }

}

?>
