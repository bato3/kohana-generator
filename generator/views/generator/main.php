<div class="window">
    <div class="ui-widget-content ui-corner-all">
        <h3 class="ui-widget-header ui-corner-all"><div id="k_icon"></div><?php Generator_Util_Lang::get("name") ?></h3>
        <div class="window-body">
            <div id="menu">
                <ul>
                <?php
                    foreach ($menu as $key => $val){
                ?>
                    <li>
                        <button id="<?php echo $key ?>" class="menu_button"><?php Generator_Util_Lang::get($val["menu"]) ?></button>
                    </li>
                <?php
                    }
                ?>
                    <li>
                        <button id="logout" class="menu_button"><?php Generator_Util_Lang::get("logout") ?></button>
                    </li>
                </ul>
            </div>
            
            <div id="template_result_wrapper">
                <div id="template_result"></div>
                <div id="result"></div>
            </div>           
            
        </div>
    </div>
</div>