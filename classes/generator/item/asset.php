<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Asset extends Generator_Item_Abstract_Item {

    public function init() 
    {
        $file1 = Generator_File::factory()
                ->set_file_name("jquery.min")
                ->set_file_ext(Generator_File::$JS)
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "js");

        $file2 = Generator_File::factory()
                ->set_file_name("reset")
                ->set_file_ext(Generator_File::$CSS)
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "css");

        $file3 = Generator_File::factory()
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "image");
       
        if (!$file1->file_exists()) 
        {
            $file1->add_row(file_get_contents($this->config->get("jquery_url")));
        }

        if (!$file2->file_exists()) 
        {
            $file2->add_row(file_get_contents($this->config->get("reset_css_url")));
        }
        
        
        //kube css framework
        $file = Kohana::$cache_dir.DIRECTORY_SEPARATOR."kube.zip";
                
        if(!file_exists($file)){
            file_put_contents($file, file_get_contents($this->config->get("kube_css_framework_url")));
            chmod($file, 0766);
        }
        
        $zip = new ZipArchive();
        if($zip->open($file) == TRUE){
            $zip->extractTo(Kohana::$cache_dir, array("css/kube.min.css", "css/master.css"));
            $zip->close();
        }
                
        $file4 = Generator_File::factory()
                ->set_file_name("kube.min")
                ->set_file_ext(Generator_File::$CSS)
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "css");
        
        $file5 = Generator_File::factory()
                ->set_file_name("master")
                ->set_file_ext(Generator_File::$CSS)
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "css");
        
        if(!$file4->file_exists()){
            $file4->add_row(file_get_contents(Kohana::$cache_dir.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."kube.min.css"));
        }
        
        if(!$file5->file_exists()){
            $file5->add_row(file_get_contents(Kohana::$cache_dir.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."master.css"));
        }
        
        $this->add($file1);
        $this->add($file2);
        $this->add($file3);
        $this->add($file4);
        $this->add($file5);
    }

}

?>
