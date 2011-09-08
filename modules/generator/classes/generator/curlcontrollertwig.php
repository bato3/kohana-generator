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
        
        $model_template = $model = !empty($post["model"]) ? $post["model"] : self::$default_controller;
        $actions = array("index","create","edit","delete");
        $config = Generator_Util::loadConfig();
        $writer = new Generator_Filewriter($model);
                
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
        $writer->addRow("    private \$session;");
        
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
        \$this->session = Session::instance();
    }");
        }else{
            $writer->addRow("
    public function before() {
        parent::before();
        \$this->template->title = \"$form &#187; \" . \$this->request->action();       
        \$this->session = Session::instance();
    }");
        }
        
        $writer->addRow("
    public function action_index() {
        \$model = new $model();
        \$this->template->labels = $lang
                
        \$this->template->show_head = __(\"show_head\");
        \$this->template->edit_head = __(\"edit_head\");
        \$this->template->delete_head = __(\"delete_head\");
                
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
                \$this->session->set(\"".$config->get("flash")."\", __(\"save_success\"));
                \$this->request->redirect(\$this->index_url);
                
            } else {
                
                \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                \$this->form->values = \$_POST;
                \$this->form->flash = __(\"save_failed\");
                
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
                    \$this->session->set(\"".$config->get("flash")."\", __(\"update_success\"));
                    \$this->request->redirect(\$this->index_url);
                
                } else {
                
                    \$this->form->errors = \$model->validation()->errors(\"form_errors\");
                    \$this->form->values = \$_POST;
                    \$this->form->flash = __(\"update_failed\");
                
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
            \$this->session->set(\"".$config->get("flash")."\", __(\"delete_success\"));
        }else{
            \$this->session->set(\"".$config->get("flash")."\", __(\"delete_failed\"));
        }
                
        \$this->request->redirect(\$this->index_url);
    }
                
    private function initForm(){
        \$this->form = View::factory(\"forms/$form\");\n".self::referenced($model)."
        \$flash = \$this->session->get(\"".$config->get("flash")."\");
        if(!empty (\$flash)){
            \$this->form->flash = \$flash;
            \$this->session->delete(\"".$config->get("flash")."\");
        }
    }
                "
        );

        $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
        $writer->write(Generator_Filewriter::$CONTROLLER);
        
        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());
        
        $result_array = array();
        $result_array[] = $result;
        $result_array[] = self::generateViews($model_template);
        
        return $result_array;
    }
    
    private static function generateViews($name){
        $result = new Generator_Result();
        $config = Generator_Util::loadConfig();
        $twig_extension = $config->get("twig_extension");
        
        $index_writer = new Generator_Filewriter("index.$twig_extension",true);
        $path = $index_writer->getApplicationPaths(Generator_Filewriter::$VIEWS).$name;
        
        if($index_writer->mkdir($path)){
            $index_writer->userSpecPath($path);

            $index_writer->addRow("{% extends \"template/template.$twig_extension\" %}");
            $index_writer->addRow("{% block content %}");
            $index_writer->addRow("{% include \"lists/$name.$twig_extension\" %}");
            $index_writer->addRow("{% endblock %}");
            $index_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);
            $result->addItem($index_writer->getFilename(), $index_writer->getPath(), $index_writer->getRows());
            $result->addWriteIsOk($index_writer->writeIsOk());

            $create_writer = new Generator_Filewriter("create.$twig_extension",true);
            $create_writer->userSpecPath($path);

            $create_writer->addRow("{% extends \"template/template.$twig_extension\" %}");
            $create_writer->addRow("{% block content %}");
            $create_writer->addRow("{% autoescape false %}");
            $create_writer->addRow("{{ form }}");
            $create_writer->addRow("{% endautoescape %}");
            $create_writer->addRow("{% endblock %}");
            $create_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);
            $result->addItem($create_writer->getFilename(), $create_writer->getPath(), $create_writer->getRows());
            $result->addWriteIsOk($create_writer->writeIsOk());

            $edit_writer = new Generator_Filewriter("edit.$twig_extension",true);
            $edit_writer->userSpecPath($path);

            $edit_writer->addRow("{% extends \"template/template.$twig_extension\" %}");
            $edit_writer->addRow("{% block content %}");
            $edit_writer->addRow("{% autoescape false %}");
            $edit_writer->addRow("{{ form }}");
            $edit_writer->addRow("{% endautoescape %}");
            $edit_writer->addRow("{% endblock %}");
            $edit_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);
            $result->addItem($edit_writer->getFilename(), $edit_writer->getPath(), $edit_writer->getRows());
            $result->addWriteIsOk($edit_writer->writeIsOk());

            $show_writer = new Generator_Filewriter("show.$twig_extension",true);
            $show_writer->userSpecPath($path);

            $show_writer->addRow("{% extends \"template/template.$twig_extension\" %}");
            $show_writer->addRow("{% block content %}");
            $show_writer->addRow("{% include \"shows/$name.$twig_extension\" %}");
            $show_writer->addRow("{% endblock %}");
            $show_writer->write(Generator_Filewriter::$USER_SPECIFIES_IT);
            $result->addItem($show_writer->getFilename(), $show_writer->getPath(), $show_writer->getRows());
            $result->addWriteIsOk($show_writer->writeIsOk());
        
        }else{
            $result->addItem($name, "<div class=\"error\">Directory exists: <cite>$path</cite> Please delete first!</div>");
            $result->addWriteIsOk(false);
        }
        
        return $result;
    }
}

?>
