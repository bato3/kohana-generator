<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Asset extends Generator_Item_Abstract_Item {
        
    public function init() {
        
        $file1 = Generator_File::factory()
                ->setFileName($this->config->get("jquery_name"))
                ->setFileExt(Generator_File::$JS)
                ->setDirectory("assets".DIRECTORY_SEPARATOR."js");
        
        $file2 = Generator_File::factory()
                ->setFileName($this->config->get("reset_css_name"))
                ->setFileExt(Generator_File::$CSS)
                ->setDirectory("assets".DIRECTORY_SEPARATOR."css");
        
        $file3 = Generator_File::factory()
                ->setDirectory("assets".DIRECTORY_SEPARATOR."image");
              
        if(!$file1->file_exists()){
            $file1->addLine(file_get_contents($this->config->get("jquery_url")));
        }
            
        if(!$file2->file_exists()){
            $file2->addLine(file_get_contents($this->config->get("reset_css_url")));      
        }
      
        $this->add($file1);
        $this->add($file2);
        $this->add($file3);
        
    }
}
?>