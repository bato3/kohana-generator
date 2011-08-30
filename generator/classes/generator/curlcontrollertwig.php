<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of curlcontrollertwig
 *
 * @author burningface
 */
class Generator_Curlcontrollertwig extends Generator_Curlcontroller {
    
    public static function generate($post) {
        $result = new Generator_Result();
        
        $model = !empty($post["model"]) ? $post["model"] : self::$default_controller;
        $actions = array("index","create","edit","delete");
        $config = Generator_Util::loadConfig();
        $writer = new Generator_Filewriter($model);
        self::generateViews($model);
        
        $controllername = "Controller_" . Generator_Util::upperFirst($model);
        
        $writer->addRow(Generator_Util::$OPEN_CLASS_FILE);
        $writer->addRow(Generator_Util::classInfoHead($controllername));
        $writer->addRow("class " . $controllername . " extends Controller_Template_Twig {\n");
        $writer->addRow(self::getActionPaths($model, $actions));
        $exception = "throw new HTTP_Exception_404(sprintf(__(\"item_not_found_exception\"), \$this->request->param(\"id\")));";
        if(!$config->get("multilang_support")){
            $writer->addRow("    private \$item_not_found_exception = \"".$config->get("item_not_found_exception")."\";");
            $exception = "throw new HTTP_Exception_404(sprintf(\$this->exception, \$this->request->param(\"id\")));";
        }
        $writer->addRow("    private \$form;");
        
        //$controllers = self::getControllers(); 

        $lang = "\$model->labels();";
        if($config->get("multilang_support")){
            $lang = "__(\"$model\");";
        } 
        
        $form = $model;
        $model = "Model_".Generator_Util::upperFirst($model);
        
        if($config->get("multilang_support")){
            $languages = $config->get("languages");
            $writer->addRow("
    public function before() {
        parent::before();
        I18n::\$lang = \"".$languages[0]."\";
        \$this->template->title = \"$form &#187; \" . \$this->request->action();        
    }");
        }else{
            $writer->addRow("
    public function before() {
        parent::before();
        \$this->template->title = \"$form &#187; \" . \$this->request->action();        
    }");
        }
        
        $writer->addRow("
    public function action_index() {
        \$model = new $model();
        \$this->template->labels = $lang
        \$this->template->show = __(\"show\");
        \$this->template->edit = __(\"edit\");
        \$this->template->delete = __(\"delete\");
        \$this->template->create = __(\"create\");
        \$this->template->result = \$model->find_all();
    }
    
    public function action_create() {
        \$model = new $model();
        \$this->initForm();
        \$this->form->labels = $lang
        \$this->form->action = \$this->create_url;
                
        if (isset(\$_POST[\"submit\"])) {
                            
            if (\$model->values(\$_POST)->validation()->check() && \$model->csrf(\$_POST)->check()) {
                
                \$model->save(\$model->validation());
                \$this->request->redirect(\$this->index_url);
                
            } else {
                
                \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                \$this->form->values = \$_POST;
                
            }
                
        }
        \$this->template->form = \$this->form;
                
    }

    public function action_edit() {
        \$model = new $model(\$this->request->param(\"id\"));
        \$this->initForm();
        \$this->form->labels = $lang
        \$this->form->action = \$this->edit_url . \"/\" . \$this->request->param(\"id\");
                
        if (\$model->loaded()) {
            \$this->form->values = \$model->as_array();        
        }else{
            $exception
        }       
        
        if (isset(\$_POST[\"submit\"])) {
                
            if (\$model->loaded()) {
                
                if (\$model->values(\$_POST)->validation()->check() && \$model->csrf(\$_POST)->check()) {
                
                    \$model->update(\$model->validation());
                    \$this->request->redirect(\$this->index_url);
                
                } else {
                
                    \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                    \$this->form->values = \$_POST;
                
                }
                
            }
                
        }
        \$this->template->form = \$this->form;
                
    }
                
    public function action_show() {
        \$model = new $model(\$this->request->param(\"id\"));
        
        if (\$model->loaded()) {
            \$this->template->labels = $lang
            \$this->template->model = \$model;
            \$this->template->back = __(\"back\");    
        }else{
            $exception
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
    
    private static function generateViews($name){
        $index_writer = new Generator_Filewriter("index.html",true);
        $path = $index_writer->getApplicationPaths(Generator_Filewriter::$VIEWS).$name;
        
        if($index_writer->mkdir($path)){
            $index_writer->userSpecPath($path);

            $index_writer->addRow("{% extends \"template/template.html\" %}");
            $index_writer->addRow("{% block content %}");
            $index_writer->addRow("{% include \"lists/$name.html\" %}");
            $index_writer->addRow("{% endblock %}");
            $index_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);

            $create_writer = new Generator_Filewriter("create.html",true);
            $create_writer->userSpecPath($path);

            $create_writer->addRow("{% extends \"template/template.html\" %}");
            $create_writer->addRow("{% block content %}");
            $create_writer->addRow("{% autoescape false %}");
            $create_writer->addRow("{{ form }}");
            $create_writer->addRow("{% endautoescape %}");
            $create_writer->addRow("{% endblock %}");
            $create_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);

            $edit_writer = new Generator_Filewriter("edit.html",true);
            $edit_writer->userSpecPath($path);

            $edit_writer->addRow("{% extends \"template/template.html\" %}");
            $edit_writer->addRow("{% block content %}");
            $edit_writer->addRow("{% autoescape false %}");
            $edit_writer->addRow("{{ form }}");
            $edit_writer->addRow("{% endautoescape %}");
            $edit_writer->addRow("{% endblock %}");
            $edit_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);

            $show_writer = new Generator_Filewriter("show.html",true);
            $show_writer->userSpecPath($path);

            $show_writer->addRow("{% extends \"template/template.html\" %}");
            $show_writer->addRow("{% block content %}");
            $show_writer->addRow("{% include \"shows/$name.html\" %}");
            $show_writer->addRow("{% endblock %}");
            $show_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);
        
        }else{
            Kohana_Log::instance()->add(Log::ALERT, "nem lehet létrehozni $path");
        }
    }
}

?>
