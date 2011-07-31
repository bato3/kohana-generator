<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ajax
 *
 * @author burningface
 */
class Controller_Generatorajax extends Controller {

    private function sendHtml($html) {
        $this->response->headers(array(
            "content-type" => "text/html",
            "cache-control" => "no-cache"
        ));
        $this->response->body($html)->send_headers()->body();
    }

    public function action_formfieldslist() {
        if ($this->request->is_ajax()) {
            $id = $this->request->param("id");
            if (!empty($id)) {
                $form = View::factory("forms/generatorformsettings");
                $form->action = "generatorajax/form";
                $form->name = $id;
                $form->language = array("generate_form_button" => "Generete Form");
                $form->fields = Generator_Util::listTableFields($id);
                $this->sendHtml($form->render());
            } else {
                $this->sendHtml("Requested id doesn't exsists!");
            }
        } else {
            throw new HTTP_Exception_404();
        }
    }

    public function action_form() {
        if ($this->request->is_ajax()) {
            $post = $_POST;
            $writer = Generator_Form::generate($post);
            $view = View::factory("forms/generatorshowgeneratedform");
            $view->rows = $writer->getRows();
            $view->savepath = $writer->getPath();
            $view->write_ok = $writer->writeIsOk();
            $view->flash_generated = isset($_POST["flashmessage"]) ? true : false;
            $this->sendHtml($view);
        } else {
            throw new HTTP_Exception_404();
        }
    }

    public function action_controller() {
        if ($this->request->is_ajax()) {
            $writer = Generator_Controller::generate($_POST);
            $view = View::factory("forms/generatorshowgeneratedcontroller");
            $view->rows = $writer->getRows();
            $view->savepath = $writer->getPath();
            $view->write_ok = $writer->writeIsOk();
            $this->sendHtml($view);
        } else {
            throw new HTTP_Exception_404();
        }
    }

    public function action_model() {
        if ($this->request->is_ajax()) {
            Generator_Model::setDateFormat($_POST["date_format"]);
            $html = Generator_Model::generate();

            $view = View::factory("forms/generatorshowgeneratedmodel");
            $view->write_ok = in_array(false, Generator_Model::getIsOkArray()) ? false : true;
            $view->files = $html;
            $this->sendHtml($view);
        } else {
            throw new HTTP_Exception_404();
        }
    }

    public function action_assets() {
        if ($this->request->is_ajax()) {
            $this->sendHtml(Generator_Assets::generate());
        } else {
            throw new HTTP_Exception_404();
        }
    }

}

?>
