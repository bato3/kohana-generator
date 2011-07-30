<?php
defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of assets
 *
 * @author burningface
 */
class Generator_Assets {
    
    private static $html;
    
    public static function generateAssets(){
        $writer = new Generator_Filewriter();
        $writer->write(Generator_Filewriter::$ASSETS);
        self::$html .= $writer->getPath() . "<br />";
        
        $writer->write(Generator_Filewriter::$ASSETS_CSS);
        self::$html .= $writer->getPath() . "<br />";
        
        $writer->write(Generator_Filewriter::$ASSETS_IMG);
        self::$html .= $writer->getPath() . "<br />";
        
        $writer->write(Generator_Filewriter::$ASSETS_JS);
        self::$html .= $writer->getPath() . "<br />";
        
        $writer = new Generator_Filewriter("reset.css", true);
        $writer->addRow(
       "html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed, 
        figure, figcaption, footer, header, hgroup, 
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
        }

        article, aside, details, figcaption, figure, 
        footer, header, hgroup, menu, nav, section {
                display: block;
        }
        body {
                line-height: 1;
        }
        ol, ul {
                list-style: none;
        }
        blockquote, q {
                quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
                content: '';
                content: none;
        }
        table {
                border-collapse: collapse;
                border-spacing: 0;
        }"
        );
        $writer->write(Generator_Filewriter::$ASSETS_CSS);
        self::$html .= $writer->getPath() . "<br />";
        
        $config = Kohana::$config->load("generator");
        $jquery = file_get_contents($config->get("jquery"));
        $writer = new Generator_Filewriter("jquery.js", true);
        $writer->addRow($jquery);
        $writer->write(Generator_Filewriter::$ASSETS_JS);
        self::$html .= $writer->getPath() . "<br />";
        return self::$html;
    }
    
}

?>
