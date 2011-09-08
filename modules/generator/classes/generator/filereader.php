<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of filereader
 *
 * @author burningface
 */
class Generator_Filereader extends Generator_File {
    
    public function getModels(){
        $path = $this->getApplicationPaths(Generator_Filereader::$MODEL);
        $dh = opendir($path);
        $models = array();
        while (($file = readdir($dh)) != false){
            if($file != "." && $file != ".."){
                $file_path = $path.DIRECTORY_SEPARATOR.$file;
                if($this->isPHPFile($file_path)){
                    $name = basename($file_path, ".php");
                    $models[$name] = $name;
                }
            }
        }
        closedir($dh);
        ksort($models);
        return $models;
    }
}

?>
