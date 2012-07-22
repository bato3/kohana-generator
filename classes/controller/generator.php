<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Controller_Generator extends Controller_Template {
    
    public $template;
    public $view;
    
    public function before() 
    {
        $this->template = 'generator'.DIRECTORY_SEPARATOR."template";
        parent::before();    
    }
    
    public function action_index(){}
    
    public function after() 
    {
        $this->template->content = $this->view;
        parent::after();
    }
    
}

?>
