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
                ->add_row(Generator_Util_Text::space(4) . "public \$template = 'templates/template';\n")
                
                // action_index
                ->add_row(self::meta("action_index"))
                ->add_row(Generator_Util_Text::space(4) . "public function action_index()")
                ->add_row(Generator_Util_Text::space(4) . "{")
                ->add_row(Generator_Util_Text::space(8) . "\$view = View::factory('lists/" . $db_table->get_name() . "');")
                ->add_row(Generator_Util_Text::space(8) . "\$view->result = ORM::factory('" . $db_table->get_name() . "')->find_all();")
                ->add_row(Generator_Util_Text::space(8) . "\$this->template->content = \$view;")
                ->add_row(Generator_Util_Text::space(4) . "}\n")
                
                // action_new
                ->add_row(self::meta("action_new"))
                ->add_row(Generator_Util_Text::space(4) . "public function action_new()")
                ->add_row(Generator_Util_Text::space(4) . "{")
                ->add_row(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->get_name() . "');\n")
                ->add_row(Generator_Util_Text::space(8) . "\$form = View::factory('forms/" . $db_table->get_name() . "');");
                
                foreach ($db_table->get_table_fields() as $field){
                    
                    if($field->is_foreign_key())
                    {
                        $file->add_row(Generator_Util_Text::space(8) . "\$form->".$field->get_name()." = ORM::factory('".$db_table->get_referenced_table_name($field->get_name())."')->find_all()->as_array('".$db_table->get_primary_key_name()."', '".$db_table->get_primary_key_name()."');");
                    }
                }
                
                $file->add_row(Generator_Util_Text::space(8) . "\$form->action = '/" . $db_table->get_name() . "/new';\n")
                     ->add_row(Generator_Util_Text::space(8) . "if (isset(\$_POST['submit']))")
                     ->add_row(Generator_Util_Text::space(8) . "{")
                     ->add_row(Generator_Util_Text::space(12) . "\$model->values(\$_POST);\n")
                     ->add_row(Generator_Util_Text::space(12) ."if(\$model->validation()->check())")
                     ->add_row(Generator_Util_Text::space(12) ."{")
                     ->add_row(Generator_Util_Text::space(16) ."\$model->save();")
                     ->add_row(Generator_Util_Text::space(12) ."}")
                     ->add_row(Generator_Util_Text::space(12) ."else")
                     ->add_row(Generator_Util_Text::space(12) ."{")
                     ->add_row(Generator_Util_Text::space(16) ."\$form->errors = \$model->validation()->errors('form-errors');")
                     ->add_row(Generator_Util_Text::space(16) ."\$form->values = \$_POST;")
                     ->add_row(Generator_Util_Text::space(12) ."}")
                     ->add_row(Generator_Util_Text::space(8) ."}\n")
                     ->add_row(Generator_Util_Text::space(8) ."\$this->template->content = \$form;\n")
                     ->add_row(Generator_Util_Text::space(4) . "}\n")
                
                // action_edit
                ->add_row(self::meta("action_edit"))
                ->add_row(Generator_Util_Text::space(4) . "public function action_edit()")
                ->add_row(Generator_Util_Text::space(4) . "{")
                ->add_row(Generator_Util_Text::space(8) . "\$id = \$this->request->param('id');")
                ->add_row(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->get_name() . "', \$id);\n")
                ->add_row(Generator_Util_Text::space(8) . "if(\$model->loaded())")
                ->add_row(Generator_Util_Text::space(8) . "{")
                ->add_row(Generator_Util_Text::space(12) . "\$form = View::factory('forms/" . $db_table->get_name() . "');");
                
                foreach ($db_table->get_table_fields() as $field){
                    
                    if($field->is_foreign_key())
                    {
                        $file->add_row(Generator_Util_Text::space(12) . "\$form->".$field->get_name()." = ORM::factory('".$db_table->get_referenced_table_name($field->get_name())."')->find_all()->as_array('".$db_table->get_primary_key_name()."', '".$db_table->get_primary_key_name()."');");
                    }
                }
                        
                $file->add_row(Generator_Util_Text::space(12) . "\$form->action = '/" . $db_table->get_name() . "/edit/'.\$id;")
                     ->add_row(Generator_Util_Text::space(12) . "\$form->values = \$model->as_array();\n")
                     ->add_row(Generator_Util_Text::space(12) . "if (isset(\$_POST['submit']))")
                     ->add_row(Generator_Util_Text::space(12) . "{")
                     ->add_row(Generator_Util_Text::space(16) . "\$model->values(\$_POST);")
                     ->add_row(Generator_Util_Text::space(16) . "if(\$model->validation()->check())")
                     ->add_row(Generator_Util_Text::space(16) . "{")
                     ->add_row(Generator_Util_Text::space(20) . "if(\$model->update()){")
                     ->add_row(Generator_Util_Text::space(24) . "\$this->request->redirect(\$this->request->referrer());")
                     ->add_row(Generator_Util_Text::space(20) . "}")
                     ->add_row(Generator_Util_Text::space(16) . "}")
                     ->add_row(Generator_Util_Text::space(16) . "else")
                     ->add_row(Generator_Util_Text::space(16) . "{")
                     ->add_row(Generator_Util_Text::space(20) . "\$form->errors = \$model->validation()->errors('form-errors');")
                     ->add_row(Generator_Util_Text::space(20) . "\$form->values = \$_POST;")
                     ->add_row(Generator_Util_Text::space(16) . "}")
                     ->add_row(Generator_Util_Text::space(12) . "}\n")
                     ->add_row(Generator_Util_Text::space(12) . "\$this->template->content = \$form;\n")
                     ->add_row(Generator_Util_Text::space(8) . "}")
                     ->add_row(Generator_Util_Text::space(8) . "else")
                     ->add_row(Generator_Util_Text::space(8) . "{")
                     ->add_row(Generator_Util_Text::space(12) . "throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');")
                     ->add_row(Generator_Util_Text::space(8) . "}")
                     ->add_row(Generator_Util_Text::space(4) . "}\n")
                
                // action_show
                ->add_row(self::meta("action_show"))
                ->add_row(Generator_Util_Text::space(4) . "public function action_show()")
                ->add_row(Generator_Util_Text::space(4) . "{")
                ->add_row(Generator_Util_Text::space(8) . "\$id = \$this->request->param('id');")
                ->add_row(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->get_name() . "', \$id);\n")
                ->add_row(Generator_Util_Text::space(8) . "if(\$model->loaded())")
                ->add_row(Generator_Util_Text::space(8) . "{")
                ->add_row(Generator_Util_Text::space(12) . "\$view = View::factory('shows/" . $db_table->get_name() . "')")
                ->add_row(Generator_Util_Text::space(20) . "->bind('model', \$model);\n")
                ->add_row(Generator_Util_Text::space(12) . "\$this->template->content = \$view;")
                ->add_row(Generator_Util_Text::space(8) . "}")
                ->add_row(Generator_Util_Text::space(8) . "else")
                ->add_row(Generator_Util_Text::space(8) . "{")
                ->add_row(Generator_Util_Text::space(12) . "throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');")
                ->add_row(Generator_Util_Text::space(8) . "}")
                ->add_row(Generator_Util_Text::space(4) . "}\n")
                
                // action_delete
                ->add_row(self::meta("action_delete"))
                ->add_row(Generator_Util_Text::space(4) . "public function action_delete()")
                ->add_row(Generator_Util_Text::space(4) . "{")
                ->add_row(Generator_Util_Text::space(8) . "if(isset(\$_POST['id']))")
                ->add_row(Generator_Util_Text::space(8) . "{")
                ->add_row(Generator_Util_Text::space(12) . "ORM::factory('" . $db_table->get_name() . "', \$_POST['id'])->delete();")
                ->add_row(Generator_Util_Text::space(8) . "}\n")
                ->add_row(Generator_Util_Text::space(8) . "\$this->request->redirect('/" . $db_table->get_name() . "');")
                ->add_row(Generator_Util_Text::space(4) . "}\n")
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
