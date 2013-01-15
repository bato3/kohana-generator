<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Message {
    
    public static function factory() 
    {
        $file = Generator_File::factory()
                ->set_directory("application" . DIRECTORY_SEPARATOR . "messages")
                ->set_file_name("validation")
                ->add_row("return array(\n");
        
        if(Generator_Util_Config::load()->support_multilang)
        {
            $array = Generator_Util_Config::load()->validation;
            
            foreach ($array as $key => $val){
               $file->add_row("'" . $key . "' => '" . $key . "',", 4);
            }
        }
        
        $file->add_row("\n);");
        
        return $file;
    }
    
}

?>
