<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 *
 * @author burningface
 */
class Generator_Template_Crud {

    public static function factory($table) {
        $db_table = Generator_Db_Table::factory($table);

        $file = Generator_File::factory()
                ->set_file_name(strtolower($db_table->get_name()))
                ->set_directory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "controller")
                ->add_row("class Controller_" . Generator_Util_Text::upper_first($db_table->get_name()) . " extends Controller_Template {\n")
                ->add_row("public \$template = 'templates/template';\n", 4)
                
                // action_index
                ->add_row(self::meta("action_index"))
                ->add_row("public function action_index()", 4)
                ->add_row("{", 4)
                ->add_row("\$view = View::factory('lists/" . $db_table->get_name() . "');", 8)
                ->add_row("\$view->result = ORM::factory('" . $db_table->get_name() . "')->find_all();", 8)
                ->add_row("\$this->template->content = \$view;", 8)
                ->add_row("}\n", 4)
                
                // action_new
                ->add_row(self::meta("action_new"))
                ->add_row("public function action_new()", 4)
                ->add_row("{", 4)
                ->add_row("\$model = ORM::factory('" . $db_table->get_name() . "');\n", 8)
                ->add_row("\$form = View::factory('forms/" . $db_table->get_name() . "');", 8);
                
                foreach ($db_table->get_table_fields() as $field){
                    
                    if($field->is_foreign_key())
                    {
                        $file->add_row("\$form->".$field->get_name()." = ORM::factory('".$db_table->get_referenced_table_name($field->get_name())."')->find_all()->as_array('".$db_table->get_primary_key_name()."', '".$db_table->get_primary_key_name()."');", 8);
                    }
                }
                
                $file->add_row("\$form->action = '/" . $db_table->get_name() . "/new';\n", 8)
                     ->add_row("if (isset(\$_POST['submit']))", 8)
                     ->add_row("{", 8)
                     ->add_row("\$model->values(\$_POST);\n", 12)
                     ->add_row("if(\$model->validation()->check())", 12)
                     ->add_row("{", 12)
                     ->add_row("\$model->save();", 16)
                     ->add_row("}", 12)
                     ->add_row("else", 12)
                     ->add_row("{", 12)
                     ->add_row("\$form->errors = \$model->validation()->errors('form-errors');", 16)
                     ->add_row("\$form->values = \$_POST;", 16)
                     ->add_row("}", 12)
                     ->add_row("}\n", 8)
                     ->add_row("\$this->template->content = \$form;\n", 8)
                     ->add_row("}\n", 4)
                
                // action_edit
                ->add_row(self::meta("action_edit"))
                ->add_row("public function action_edit()", 4)
                ->add_row("{", 4)
                ->add_row("\$id = \$this->request->param('id');", 8)
                ->add_row("\$model = ORM::factory('" . $db_table->get_name() . "', \$id);\n", 8)
                ->add_row("if(\$model->loaded())", 8)
                ->add_row("{", 8)
                ->add_row("\$form = View::factory('forms/" . $db_table->get_name() . "');", 12);
                
                foreach ($db_table->get_table_fields() as $field){
                    
                    if($field->is_foreign_key())
                    {
                        $file->add_row("\$form->".$field->get_name()." = ORM::factory('".$db_table->get_referenced_table_name($field->get_name())."')->find_all()->as_array('".$db_table->get_primary_key_name()."', '".$db_table->get_primary_key_name()."');", 12);
                    }
                }
                        
                $file->add_row("\$form->action = '/" . $db_table->get_name() . "/edit/'.\$id;", 12)
                     ->add_row("\$form->values = \$model->as_array();\n", 12)
                     ->add_row("if (isset(\$_POST['submit']))", 12)
                     ->add_row("{", 12)
                     ->add_row("\$model->values(\$_POST);", 16)
                     ->add_row("if(\$model->validation()->check())", 16)
                     ->add_row("{", 16)
                     ->add_row("if(\$model->update()){", 20)
                     ->add_row("\$this->request->redirect(\$this->request->referrer());", 24)
                     ->add_row("}", 20)
                     ->add_row("}", 16)
                     ->add_row("else", 16)
                     ->add_row("{", 16)
                     ->add_row("\$form->errors = \$model->validation()->errors('form-errors');", 20)
                     ->add_row("\$form->values = \$_POST;", 20)
                     ->add_row("}", 16)
                     ->add_row("}\n", 12)
                     ->add_row("\$this->template->content = \$form;\n", 12)
                     ->add_row("}", 8)
                     ->add_row("else", 8)
                     ->add_row("{", 8)
                     ->add_row("throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');", 12)
                     ->add_row("}", 8)
                     ->add_row("}\n", 4)
                
                // action_show
                ->add_row(self::meta("action_show"))
                ->add_row("public function action_show()", 4)
                ->add_row("{", 4)
                ->add_row("\$id = \$this->request->param('id');", 8)
                ->add_row("\$model = ORM::factory('" . $db_table->get_name() . "', \$id);\n", 8)
                ->add_row("if(\$model->loaded())", 8)
                ->add_row("{", 8)
                ->add_row("\$view = View::factory('shows/" . $db_table->get_name() . "')", 12)
                ->add_row("->bind('model', \$model);\n", 20)
                ->add_row("\$this->template->content = \$view;", 12)
                ->add_row("}", 8)
                ->add_row("else", 8)
                ->add_row("{", 8)
                ->add_row("throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');", 12)
                ->add_row("}", 8)
                ->add_row("}\n", 4)
                
                // action_delete
                ->add_row(self::meta("action_delete"))
                ->add_row("public function action_delete()", 4)
                ->add_row("{", 4)
                ->add_row("if(isset(\$_POST['id']))", 8)
                ->add_row("{", 8)
                ->add_row("ORM::factory('" . $db_table->get_name() . "', \$_POST['id'])->delete();", 12)
                ->add_row("}\n", 8)
                ->add_row("\$this->request->redirect('/" . $db_table->get_name() . "');", 8)
                ->add_row("}\n", 4)
                ->add_row("}");
                        
                return $file;
    }
    
    private static function meta($comment = null)
    {
        return "    /**
     * $comment
     */";
    }

}

?>
