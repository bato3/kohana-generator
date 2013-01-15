<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Util_Cache {
    
    public static function clean(){
        $cache = new Generator_Util_Cache();
        $cache->start_clean();
    }


    public function start_clean(){
        $this->rmfiles(Kohana::$cache_dir);
    }

    private function rmfiles($dir_path){
        $dir_handle = opendir($dir_path);
        while(($file = readdir($dir_handle)) != false){
            if($file != "." && $file != ".."){
                $file_path = $dir_path.DIRECTORY_SEPARATOR.$file;
                @chmod($file_path, 0777);
                if(is_dir($file_path)){
                    $this->rmfiles($file_path);
                    if($file_path != Kohana::$cache_dir){
                        @rmdir($file_path);
                    }
                }else{
                    @unlink($file_path);
                }
            }
        }
    }
}

?>
