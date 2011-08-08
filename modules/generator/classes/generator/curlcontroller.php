<?php
defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of curlcontroller
 *
 * @author burningface
 */
class Generator_Curlcontroller extends Generator_Controller {
    
    private static function referenced($model){
        $m = new $model;
        $table = $m->table_name();
        
        $result = "";
        $db = Database::instance();
        $query = $db->query(Database::SELECT, 'SELECT * FROM information_schema.key_column_usage WHERE (TABLE_NAME=\''
                . $table . '\' OR REFERENCED_TABLE_NAME=\'' . $table . '\') AND referenced_column_name IS NOT NULL AND REFERENCED_TABLE_NAME != \'' . $table . '\'');
        
        foreach($query as $array){
            $field = Generator_Field::factory($array);
            $result .= "        \$" . $field->getReferencedColumnName() . " = new " . $field->getReferencedModelName() ."();\n"; 
            $result .= "        \$this->form->" . $field->getReferencedColumnName() . " = " ."\$" . $field->getReferencedColumnName() . "->selectOptions();\n";
        }
        
        return $result;
    }


    public static function generate($post) {
        $result = new Generator_Result();
        
        $model = !empty($post["model"]) ? $post["model"] : self::$default_controller;
        $actions = array("index","create","edit","delete");

        $writer = new Generator_Filewriter($model);

        $controllername = "Controller_" . Generator_Util::upperFirst($model);

        $writer->addRow(Generator_Util::$OPEN_CLASS_FILE);
        $writer->addRow(Generator_Util::classInfoHead($controllername));
        $writer->addRow("class " . $controllername . " extends Controller_Template {\n");
        $writer->addRow(self::getActionPaths($model, $actions));
        
        $writer->addRow("    private \$form;");
        
        $controllers = self::getControllers(); 
        
        $writer->addRow("    public \$template = \"template/template\";");
        
        $config = Generator_Util::loadConfig();
        $lang = "\$model->labels();";
        if($config->get("multilang_support")){
            $lang = "__(\"$model\");";
        }
        
        $exception = $config->get("item_not_found_exception"); 
        
        $form = $model;
        $model = "Model_".Generator_Util::upperFirst($model);
        if($config->get("multilang_support")){
            $writer->addRow("
    public function before() {
        parent::before();
        I18n::\$lang = \"en\";
        \$this->template->title = \"$form &#187; \" . \$this->request->action();        
    }");
        }
        $writer->addRow("
    public function action_index() {
        \$model = new $model();
        \$list = View::factory(\"lists/$form\");
        \$list->labels = $lang
        \$list->result = \$model->find_all();
        \$this->template->content = \$list;
    }
    
    public function action_create() {
        \$model = new $model();
        \$this->initForm();
        \$this->form->labels = $lang
        \$this->form->action = \$this->create_url;
                
        if (isset(\$_POST[\"submit\"])) {
            \$model->values(\$_POST);
                
            if (\$model->validation()->check()) {
                
                \$model->save(\$model->validation());
                \$this->request->redirect(\$this->index_url);
                
            } else {
                
                \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                \$this->form->values = \$_POST;
                
            }
                
        }
        \$this->template->content = \$this->form;
                
    }

    public function action_edit() {
        \$model = new $model(\$this->request->param(\"id\"));
        \$this->initForm();
        \$this->form->labels = $lang
        \$this->form->action = \$this->edit_url . \"/\" . \$this->request->param(\"id\");
                
        if (\$model->loaded()) {
            \$this->form->values = \$model->as_array();        
        }else{
            throw new HTTP_Exception_404(\"".$exception["1"]." \" . \$this->request->param(\"id\") . \" ".$exception["2"]."\");
        }       
        
        if (isset(\$_POST[\"submit\"])) {
                
            if (\$model->loaded()) {
                \$model->values(\$_POST);
                
                if (\$model->validation()->check()) {
                
                    \$model->update(\$model->validation());
                    \$this->request->redirect(\$this->index_url);
                
                } else {
                
                    \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                    \$this->form->values = \$_POST;
                
                }
                
            }
                
        }
        \$this->template->content = \$this->form;
                
    }
                
    public function action_show() {
        \$model = new $model(\$this->request->param(\"id\"));
        
        if (\$model->loaded()) {
            \$view = View::factory(\"shows/$form\");
            \$view->labels = $lang
            \$view->model = \$model;
            \$this->template->content = \$view;
        }else{
            throw new HTTP_Exception_404(\"".$exception["1"]." \" . \$this->request->param(\"id\") . \" ".$exception["2"]."\");
        }

    }

    public function action_delete() {
        \$model = new $model(\$this->request->param(\"id\"));
        
        if (\$model->loaded()) {
            \$model->delete();
        }
                
        \$this->request->redirect(\$this->index_url);
    }
                
    private function initForm(){
        \$this->form = View::factory(\"forms/$form\");\n".self::referenced($model)."
    }
                "
        );

        $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
        $writer->write(Generator_Filewriter::$CONTROLLER);
        
        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());
        
        return $result;
    }
}

?>
