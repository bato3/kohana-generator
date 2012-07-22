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
                ->set_file_name($this->config->get("jquery_name"))
                ->set_file_ext(Generator_File::$JS)
                ->set_directory("assets" . DIRECTORY_SEPARATOR . "js");

        $file2 = Generator_File::factory()
                ->set_file_name($this->config->get("reset_css_name"))
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

        $this->add($file1);
        $this->add($file2);
        $this->add($file3);
    }

}

?>
