<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_File {
    
    private $rows;
    private $file_name;
    private $ext;
    private $dir;
    private $disable_close_tag = false;
    
    public static $PHP = "php";
    public static $JS = "js";
    public static $CSS = "css";
    
    public function __construct() 
    {
        $this->rows = array();
        $this->ext = self::$PHP;
    }
    
    public function add_row($row, $space=0)
    {
        $this->rows[] = Generator_Util_Text::space($space).$row;
        return $this;
    }
    
    public function add_rows(array $rows)
    {
        array_merge($this->rows, $rows);
        return $this;
    }
        
    public function get_rows()
    {
        return $this->rows;
    }
    
    public function count()
    {
        return count($this->rows);
    }
    
    public function has_rows()
    {
        return 0 < $this->count() ? true : false;
    }
    
    public function has_file_name()
    {
        return empty($this->file_name) ? false : true;
    }
    
    public function has_file_ext()
    {
        return empty($this->ext) ? false : true;
    }
    
    public function has_directory()
    {
        return empty($this->dir) ? false : true;
    }
    
    public function set_file_name($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }
    
    public function get_file_name()
    {
        return $this->file_name;
    }
    
    public function set_file_ext($ext)
    {
        $this->ext = $ext;
        return $this;
    }
    
    public function get_file_ext()
    {
        return $this->ext;
    }

    public function set_directory($dir)
    {
        $this->dir = $dir;
        return $this;
    }
    
    public function get_directory()
    {
        return $this->dir;
    }
    
    public static function factory()
    {
        return new Generator_File();
    }
    
    public function get_file_path()
    {
        if($this->has_file_ext() && $this->has_file_name())
        {
            return $this->get_directory().DIRECTORY_SEPARATOR.$this->get_file_name().".".$this->get_file_ext();
        }
        else
        {            
            return $this->get_directory();
        }
    }
    
    public function file_exists()
    {
        return file_exists(DOCROOT.DIRECTORY_SEPARATOR.$this->get_file_path());
    }
    
    public function dir_exists()
    {
        return file_exists(DOCROOT.DIRECTORY_SEPARATOR.$this->get_directory());
    }
    
    public function path_is_writable()
    {
        return is_writable($this->get_file_path());
    }
    
    public function set_disable_close_tag($boolean)
    {
        $this->disable_close_tag = $boolean;
        return $this;
    }
    
    public function get_disable_close_tag()
    {
        return $this->disable_close_tag;
    }
    
}

?>
