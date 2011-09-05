<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of generatorassets
 *
 * @author burningface
 */
class Controller_Generatorassets extends Controller {

    private $img = array("image/jpg", "image/png", "image/gif");
    
    public function action_media() {
        $file = $this->request->param("file");

        $dir = pathinfo($file, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file = pathinfo($file, PATHINFO_FILENAME);
                
        if (($file = Kohana::find_file("generatorassets", $dir.$file, $ext)) != false) {
            
            $mime = File::mime_by_ext($ext);
            $content = file_get_contents($file);
            
            $this->response->headers(array(
                "content-type" => $mime,
                "content-length"=> strlen($content),
                "last-modified" => date('r', filemtime($file)),
                ));

            $this->response->body($content);
            
        } else {
            $this->response->status(404);
        }
    }
    
}
?>
