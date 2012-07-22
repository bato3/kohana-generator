<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Writer {

    private $config;
    private $rows;
    private $errors;
    private $skipped_files;
    private $skipped_dirs;
    private $generated_files;
    private $generated_dirs;

    public function __construct()
    {
        $this->rows = array();
        $this->errors = array();
        $this->skipped_files = array();
        $this->skipped_dirs = array();
        $this->generated_files = array();
        $this->generated_dirs = array();
        $this->config = Generator_Util_Config::load();
    }

    private function open(Generator_File $file) 
    {
        $this->rows = $file->get_rows();
    }

    private function php_open(Generator_File $file) 
    {
        $this->rows = array();

        $this->rows[] = $this->config->start_php_file;
        $this->rows[] = $this->config->open_php;
        $this->rows[] = "/**";
        $this->rows[] = "* @package";
        $this->rows[] = "* @author " . $this->config->author;
        $this->rows[] = "* @license " . $this->config->license;
        $this->rows[] = "* @copyright (c) " . date("Y") . " ".$this->config->author;
        $this->rows[] = "*";
        $this->rows[] = "*/";
        $this->rows = array_merge($this->rows, $file->get_rows());
    }

    private function php_close() 
    {
        $this->rows[] = $this->config->close_php;
    }

    private function write(Generator_File $file) 
    {
        $rows = "";
        
        if (!empty($this->rows))
        {
            $count = count($this->rows);
            $i = 0;
            
            foreach ($this->rows as $row) {
                
                if ($i == $count - 1) 
                {
                    $rows .= $row;
                } 
                else 
                {
                    $rows .= $row . "\n";
                }
                
                ++$i;
            }

            if(!$file->file_exists())
            {
                file_put_contents(DOCROOT.DIRECTORY_SEPARATOR.$file->get_file_path(), $rows);
                @chmod(DOCROOT.DIRECTORY_SEPARATOR.$file->get_file_path(), 0777);
                $this->add_generated_files($file->get_file_path());
            }
            else 
            {
                $this->add_skipped_files($file->get_file_path());
            }

        } 
        else 
        {
            $this->add_skipped_files($file->get_file_path());
        }
    }

    public function register(Generator_Item_Abstract_Item $generator) 
    {
        if (!$generator->is_empty()) 
        {

            $files = $generator->get();

            foreach ($files as $file) {

                $this->mkdir($file);

                if (!$file->file_exists()) 
                {

                    if ($file->has_rows() && $file->has_file_name() && $file->has_file_ext()) 
                    {

                        switch ($file->get_file_ext()) {

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
                                if(!$file->get_disable_close_tag()){
                                    $this->php_close();
                                }
                                $this->write($file);
                                break;
                        }
                        
                    } 
                    elseif (!$file->has_rows() && !$file->has_file_name() && $file->has_directory()) 
                    {
                        $this->mkdir($file);
                    }
                } 
                else 
                {
                    if ($file->has_file_ext()) 
                    {
                        $this->add_skipped_files($file->get_file_path());
                    }
                }
            }
        }
        
        $this->add_errors($generator->get_errors());
        
        return $this;
    }

    public function mkdir(Generator_File $file)
    {
        $dirs = explode(DIRECTORY_SEPARATOR, $file->get_directory());
        $path = DOCROOT;
        
        foreach ($dirs as $dir) {
            
            if(!empty($dir))
            {
                $path .= $dir.DIRECTORY_SEPARATOR;
                
                if (!file_exists($path)) 
                {
                    @mkdir($path);
                    @chmod($path, 0777);
                    $this->add_generated_dirs($path);
                } 
                else 
                {
                    $this->add_skipped_dirs($path);
                }
            }
        }        
        
        return $this;
    }

    private function add_errors($error) 
    {
        if (is_array($error)) 
        {
            $this->errors = array_merge($this->errors, $error);
        } 
        else 
        {
            $this->errors[] = $error;
        }
        
        return $this;
    }

    private function add_skipped_files($skipped) 
    {
        if (is_array($skipped)) 
        {
            $this->skipped_files = array_merge($this->skipped_files, $skipped);
        } 
        else
        {
            $this->skipped_files[] = $skipped;
        }
        
        return $this;
    }

    private function add_skipped_dirs($skipped) 
    {
        if (is_array($skipped)) 
        {
            $this->skipped_dirs = array_merge($this->skipped_dirs, $skipped);
        } 
        else 
        {
            $this->skipped_dirs[] = $skipped;
        }
        
        return $this;
    }

    private function add_generated_files($generated) 
    {
        if (is_array($generated)) 
        {
            $this->generated_files = array_merge($this->generated_files, $generated);
        }
        else 
        {
            $this->generated_files[] = $generated;
        }

        return $this;
    }

    private function add_generated_dirs($generated) 
    {
        if (is_array($generated)) 
        {
            $this->generated_files = array_merge($this->generated_files, $generated);
        } 
        else 
        {
            $this->generated_dirs[] = $generated;
        }
        
        return $this;
    }

    public function get_files() 
    {
        return $this->files;
    }

    public function get_errors() 
    {
        return $this->errors;
    }

    public function get_skipped_files() 
    {
        return $this->skipped_files;
    }

    public function get_skipped_dirs() 
    {
        return $this->skipped_dirs;
    }

    public function get_generated_files() 
    {
        return $this->generated_files;
    }

    public function get_generated_dirs() 
    {
        return $this->generated_dirs;
    }

    public static function factory() 
    {
        return new Generator_Writer();
    }

}

?>
