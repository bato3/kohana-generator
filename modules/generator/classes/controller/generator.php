<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of generator
 *
 * @author burningface
 */
class Controller_Generator extends Kohana_Controller_Template {

    private static $SESSION_KEY = "generator_logged_in";
    private static $LOGIN_FAILD = "login failed!";
    public $template = "generatortemplate";
    private $logged_in = false;
    private $links = array(
        "assets" => "assets",
        "template" => "template",
        "model" => "model",
        "list" => "list",
        "show" => "show",
        "form" => "form",
        "formbuilder" => "form builder",
        "controller" => "controller builder",
        "curlcontroller" => "curl controller",
        "logout" => "logout"
    );

    public function before() {
        parent::before();
        $this->logged_in = Session::instance()->get(self::$SESSION_KEY);
        if ($this->request->action() != "login" && !$this->logged_in) {
            Request::current()->redirect("generator/login");
        }
        $this->template->links = $this->links;
        if (array_key_exists($this->request->action(), $this->links)) {
            $this->template->legend = ucwords($this->links[$this->request->action()]);
        }
    }

    public function action_index() {
        $config = Generator_Util::loadConfig();
        $this->template->legend = ucfirst($this->request->action());
        $this->template->content = "<h3>Hello " . ucwords(strtolower($config->get("author"))) . "!</h3><div>You are logged in!</div><div>Please setup your database first!</div>";
    }

    public function action_login() {
        if (!$this->logged_in) {

            $form = View::factory("forms/generatorlogin");
            $form->action = "generator/login";
            $form->labels = array("login" => "Login", "password" => "Password");

            if (isset($_POST["submit"])) {
                $validation = Validation::factory($_POST)
                        ->rule("password", "not_empty");
                if ($validation->check()) {
                    $config = Generator_Util::loadConfig();
                    if ($_POST["password"] == $config->get("password")) {
                        Session::instance()->set(self::$SESSION_KEY, true);
                        $this->request->redirect("generator");
                    }
                } else {
                    $this->showFlash(self::$LOGIN_FAILD);
                    $form->errors = $validation->errors("form_errors");
                }
            }

            $this->template->legend = ucfirst($this->request->action());
            $this->template->content = $form;
            
        } else {
            $this->request->redirect("/generator");
        }
    }

    public function action_logout() {
        Session::instance()->delete(self::$SESSION_KEY);
        Request::current()->redirect("generator");
    }

    public function action_form() {
        $form = View::factory("forms/generatorform");
        $form->labels = array("table" => "Db table names",
            "clear_button" => "Clear");
        $form->tablenames = Generator_Form::listTables(true);

        $this->template->content = $form;
    }

    public function action_formbuilder() {
        $form = View::factory("forms/generatorformbuilder");
        $form->action = "generatorajax/formbuilder";
        $form->labels = array("generate_formbuilder_button" => "Generete Form",
            "add_row_button" => "Add new row",
            "input_name" => "Input field name",
            "flashmessage" => "Flash message",
            "generate_form_name" => "File name",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }

    public function action_loginform() {
        $this->template->content = "loginform";
    }

    public function action_model() {
        $form = View::factory("forms/generatormodel");
        $form->labels = array("model_button" => "Generate models",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }
    
    public function action_list() {
        $form = View::factory("forms/generatorlist");
        $form->labels = array("list_button" => "Generate lists",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }
    
    public function action_show() {
        $form = View::factory("forms/generatorshow");
        $form->labels = array("show_button" => "Generate shows",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }

    public function action_controller() {
        $form = View::factory("forms/generatorcontroller");
        $form->action = "generatorajax/controller";
        $form->labels = array("generate_controller_button" => "Generate controller",
            "controller_name" => "Controller name",
            "add_action_button" => "Add new action method",
            "clear_button" => "Clear"
        );

        $this->template->content = $form;
    }
    
    public function action_curlcontroller() {
        $form = View::factory("forms/generatorcurlcontroller");
        $form->action = "generatorajax/curlcontroller";
        $form->labels = array("generate_curlcontroller_button" => "Generate curl controller",
            "models" => "Models",
            "clear_button" => "Clear"
        );
        
        $reader = new Generator_Filereader();
        $form->models = $reader->getModels();
        $this->template->content = $form;
    }

    public function action_assets() {
        $form = View::factory("forms/generatorassets");
        $form->labels = array("assets_button" => "Generate assets structure",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }
    
    public function action_template() {
        $form = View::factory("forms/generatortemplate");
        $form->labels = array("template_button" => "Generate template",
            "clear_button" => "Clear");

        $this->template->content = $form;
    }

    private function showFlash($flash) {
        if (!empty($flash)) {
            $this->template->flash = $flash;
        }
    }

}

?>
