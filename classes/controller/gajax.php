<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Controller_Gajax extends Controller {

    private $config;

    public function before() {
        parent::before();
        if (!$this->request->is_ajax()) {
            throw new HTTP_Exception_404();
        }
        $this->config = Generator_Util_Config::load();
    }

    public function action_index() {
        if ($this->login()) {
            $view = View::factory("generator/main");
            $view->menu = $this->config->register;
            Session::instance()->set("gadmin_logged_in", true);
            $json = json_encode(array("error" => false, "html" => $view->render()));
        } else {
            $view = View::factory("generator/login");
            $json = json_encode(array("error" => true, "html" => $view->render()));
        }
        $this->response
                ->headers("Content-Type", "application/json")
                ->body($json);
    }

    public function action_login() {
        if ($this->config->get("password") == $_POST["password"]) {
            $view = View::factory("generator/main");
            $view->menu = $this->config->register;
            Session::instance()->set("gadmin_logged_in", true);
            $json = json_encode(array("error" => false, "html" => $view->render()));
        } else {
            $json = json_encode(array("error" => true, "error_message" => "<div class=\"ui-state-error\">".  Generator_Util_Lang::get("login_failed", false)."</div>" ));
        }
        $this->response
                ->headers("Content-Type", "application/json")
                ->body($json);
    }

    public function action_show() {
        $view = null;
        $template = $_GET["template"];
        $logout = false;
        if (!$this->login()) {
            $template = "logout";
        }

        switch ($template) {

            case "logout":
                $view = View::factory("generator/login");
                Session::instance()->delete("gadmin_logged_in");
                $logout = true;
                break;

            default:
                Session::instance()->regenerate();
                try{
                    $view = View::factory("generator/views/$template");
                }  catch (Exception $e){
                    $view = View::factory("generator/error");
                    $view->error = Generator_Util_Lang::get("template_not_found", false);
                }
                break;
        }

        $this->response
                ->headers("Content-Type", "application/json")
                ->body(json_encode(array("html" => $view->render(), "logout" => $logout)));
    }

    public function action_generate() {
        Session::instance()->regenerate();
        $array = $this->config->register;
        $class = $array[$_GET["cmd"]]["class"];
        if(class_exists($class)){
            if (get_parent_class($class) === "Generator_Item_Abstract_Item") {
                $generator = Generator_Writer::factory();
                $generator->register(new $class);
                
                $view = View::factory("generator/result");
                $view->result = true;
                $view->errors = $generator->getErrors();
                $view->skipped_files = $generator->getSkippedFiles();
                $view->skipped_dirs = $generator->getSkippedDirs();
                $view->generated_files = $generator->getGeneratedFiles();
                $view->generated_dirs = $generator->getGeneratedDirs();
                
            }else{
                $view = View::factory("generator/error");
                $view->error = Generator_Util_Lang::get("class_not_compatible", false);
            } 
        }else{
            $view = View::factory("generator/error");
            $view->error = Generator_Util_Lang::get("class_not_found", false);
        }    

        $this->response
                ->headers("Content-Type", "text/html")
                ->body($view);
    }

    private function login() {
        $key = Session::instance()->get("gadmin_logged_in");
        return $key ? true : false;
    }

}

?>
