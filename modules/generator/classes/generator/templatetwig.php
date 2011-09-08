<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of templatetwig
 *
 * @author burningface
 */
class Generator_Templatetwig {
        
    public static function generate(){
        $result = new Generator_Result();
        $config = Generator_Util::loadConfig();
        $twig_extension = $config->get("twig_extension");
        $template = 
"<!DOCTYPE html>
<html>
    <head>
        <title>{% if title %}{% autoescape false %}{{title}}{% endautoescape %}{% endif %}</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        {% block stylesheets %}
        <link type=\"text/css\" href=\"/assets/css/reset.css\" rel=\"stylesheet\">
        <link type=\"text/css\" href=\"/assets/css/main.css\" rel=\"stylesheet\">
        {% endblock %}
        {% block javascripts %}
        <script type=\"text/javascript\" src=\"/assets/js/jquery.min.js\"></script>
        <script type=\"text/javascript\" src=\"/assets/js/main.js\"></script>
        {% endblock %}
    </head>
    <body>
        {% block content %}{% endblock %}
    </body>
</html>";
        
        $writer = new Generator_Filewriter("template.$twig_extension", true);
        $writer->addRow($template);
        $writer->write(Generator_Filewriter::$TEMPLATE);
        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());
        return $result;
    }
}

?>
