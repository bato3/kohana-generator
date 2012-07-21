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
                        ->setFileName(strtolower($db_table->getName()))
                        ->setDirectory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "controller")
                        ->addLine("class Controller_" . Generator_Util_Text::upperFirst($db_table->getName()) . " extends Controller_Template {\n")
                        ->addLine(Generator_Util_Text::space(4) . "public \$template = 'templates/template';\n")
                
                        // action_index
                        ->addLine(self::meta("action_index"))
                        ->addLine(Generator_Util_Text::space(4) . "public function action_index()")
                        ->addLine(Generator_Util_Text::space(4) . "{")
                        ->addLine(Generator_Util_Text::space(8) . "\$view = View::factory('lists/" . $db_table->getName() . "');")
                        ->addLine(Generator_Util_Text::space(8) . "\$view->result = ORM::factory('" . $db_table->getName() . "')->find_all();")
                        ->addLine(Generator_Util_Text::space(8) . "\$this->template->content = \$view;")
                        ->addLine(Generator_Util_Text::space(4) . "}\n")
                
                        // action_new
                        ->addLine(self::meta("action_new"))
                        ->addLine(Generator_Util_Text::space(4) . "public function action_new()")
                        ->addLine(Generator_Util_Text::space(4) . "{")
                        ->addLine(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->getName() . "');\n")
                        ->addLine(Generator_Util_Text::space(8) . "\$form = View::factory('forms/" . $db_table->getName() . "');");
                        foreach ($db_table->getTableFields() as $field){
                            if($field->isForeignKey()){
                                $file->addLine(Generator_Util_Text::space(8) . "\$form->".$field->getName()." = ORM::factory('".$db_table->getReferencedTableName($field->getName())."')->find_all()->as_array('".$db_table->getPrimaryKeyName()."', '".$db_table->getPrimaryKeyName()."');");
                            }
                        }
                        $file->addLine(Generator_Util_Text::space(8) . "\$form->action = '/" . $db_table->getName() . "/new';\n")
                        ->addLine(Generator_Util_Text::space(8) . "if (isset(\$_POST['submit']))")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "\$model->values(\$_POST);\n")
                        ->addLine(Generator_Util_Text::space(12) ."if(\$model->validation()->check())")
                        ->addLine(Generator_Util_Text::space(12) ."{")
                        ->addLine(Generator_Util_Text::space(16) ."\$model->save();")
                        ->addLine(Generator_Util_Text::space(12) ."}")
                        ->addLine(Generator_Util_Text::space(12) ."else")
                        ->addLine(Generator_Util_Text::space(12) ."{")
                        ->addLine(Generator_Util_Text::space(16) ."\$form->errors = \$model->validation()->errors('form-errors');")
                        ->addLine(Generator_Util_Text::space(16) ."\$form->values = \$_POST;")
                        ->addLine(Generator_Util_Text::space(12) ."}")
                        ->addLine(Generator_Util_Text::space(8) ."}\n")
                        ->addLine(Generator_Util_Text::space(8) ."\$this->template->content = \$form;\n")
                        ->addLine(Generator_Util_Text::space(4) . "}\n")
                
                        // action_edit
                        ->addLine(self::meta("action_edit"))
                        ->addLine(Generator_Util_Text::space(4) . "public function action_edit()")
                        ->addLine(Generator_Util_Text::space(4) . "{")
                        ->addLine(Generator_Util_Text::space(8) . "\$id = \$this->request->param('id');")
                        ->addLine(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->getName() . "', \$id);\n")
                        ->addLine(Generator_Util_Text::space(8) . "if(\$model->loaded())")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "\$form = View::factory('forms/" . $db_table->getName() . "');");
                        foreach ($db_table->getTableFields() as $field){
                            if($field->isForeignKey()){
                                $file->addLine(Generator_Util_Text::space(12) . "\$form->".$field->getName()." = ORM::factory('".$db_table->getReferencedTableName($field->getName())."')->find_all()->as_array('".$db_table->getPrimaryKeyName()."', '".$db_table->getPrimaryKeyName()."');");
                            }
                        }
                        $file->addLine(Generator_Util_Text::space(12) . "\$form->action = '/" . $db_table->getName() . "/edit/'.\$id;")
                        ->addLine(Generator_Util_Text::space(12) . "\$form->values = \$model->as_array();\n")
                        ->addLine(Generator_Util_Text::space(12) . "if (isset(\$_POST['submit']))")
                        ->addLine(Generator_Util_Text::space(12) . "{")
                        ->addLine(Generator_Util_Text::space(16) . "\$model->values(\$_POST);")
                        ->addLine(Generator_Util_Text::space(16) . "if(\$model->validation()->check())")
                        ->addLine(Generator_Util_Text::space(16) . "{")
                        ->addLine(Generator_Util_Text::space(20) . "if(\$model->update()){")
                        ->addLine(Generator_Util_Text::space(24) . "\$this->request->redirect(\$this->request->referrer());")
                        ->addLine(Generator_Util_Text::space(20) . "}")
                        ->addLine(Generator_Util_Text::space(16) . "}")
                        ->addLine(Generator_Util_Text::space(16) . "else")
                        ->addLine(Generator_Util_Text::space(16) . "{")
                        ->addLine(Generator_Util_Text::space(20) . "\$form->errors = \$model->validation()->errors('form-errors');")
                        ->addLine(Generator_Util_Text::space(20) . "\$form->values = \$_POST;")
                        ->addLine(Generator_Util_Text::space(16) . "}")
                        ->addLine(Generator_Util_Text::space(12) . "}\n")
                        ->addLine(Generator_Util_Text::space(12) . "\$this->template->content = \$form;\n")
                        ->addLine(Generator_Util_Text::space(8) . "}")
                        ->addLine(Generator_Util_Text::space(8) . "else")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');")
                        ->addLine(Generator_Util_Text::space(8) . "}")
                        ->addLine(Generator_Util_Text::space(4) . "}\n")
                
                        // action_show
                        ->addLine(self::meta("action_show"))
                        ->addLine(Generator_Util_Text::space(4) . "public function action_show()")
                        ->addLine(Generator_Util_Text::space(4) . "{")
                        ->addLine(Generator_Util_Text::space(8) . "\$id = \$this->request->param('id');")
                        ->addLine(Generator_Util_Text::space(8) . "\$model = ORM::factory('" . $db_table->getName() . "', \$id);\n")
                        ->addLine(Generator_Util_Text::space(8) . "if(\$model->loaded())")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "\$view = View::factory('shows/" . $db_table->getName() . "')")
                        ->addLine(Generator_Util_Text::space(20) . "->bind('model', \$model);\n")
                        ->addLine(Generator_Util_Text::space(12) . "\$this->template->content = \$view;")
                        ->addLine(Generator_Util_Text::space(8) . "}")
                        ->addLine(Generator_Util_Text::space(8) . "else")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "throw new HTTP_Exception_404('Model id : '.\$id.' was not found in database!');")
                        ->addLine(Generator_Util_Text::space(8) . "}")
                        ->addLine(Generator_Util_Text::space(4) . "}\n")
                
                        // action_delete
                        ->addLine(self::meta("action_delete"))
                        ->addLine(Generator_Util_Text::space(4) . "public function action_delete()")
                        ->addLine(Generator_Util_Text::space(4) . "{")
                        ->addLine(Generator_Util_Text::space(8) . "if(isset(\$_POST['id']))")
                        ->addLine(Generator_Util_Text::space(8) . "{")
                        ->addLine(Generator_Util_Text::space(12) . "ORM::factory('" . $db_table->getName() . "', \$_POST['id'])->delete();")
                        ->addLine(Generator_Util_Text::space(8) . "}\n")
                        ->addLine(Generator_Util_Text::space(8) . "\$this->request->redirect('/" . $db_table->getName() . "');")
                        ->addLine(Generator_Util_Text::space(4) . "}\n")
                        ->addLine("}");
                        
                        return $file;
    }
    
    private static function meta($comment = null){
        return "    /**
    * $comment
    */";
    }

}

?>
