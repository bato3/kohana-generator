<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 * Description of generator
 *
 * @author burningface
 */
class Controller_Gmedia extends Controller {
    
    
    public function action_index()
    {
        $file = $this->request->param("file");
        if(!empty($file)){
            $path = MODPATH."generator".DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR.$file;
            if(file_exists($path)){
                $this->response->headers("Content-Type", File::mime_by_ext(pathinfo($path, PATHINFO_EXTENSION)));
                $this->response->body(file_get_contents($path));
            }else{
                $this->response->status("404");
            }
        }else{
            $this->response->status("404");
        }
    }
    
}

?>
