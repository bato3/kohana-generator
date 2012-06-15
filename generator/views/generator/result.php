<?php
if($result){
?>
<div class="clear"></div>
<div id="accordion_wrapper">
<div id="accordion">
    
        <h3><a href="#"><?php Generator_Util_Lang::get("generated_files") ?></a></h3>
	<div>
            <ul>
            <?php
            foreach($generated_files as $file){
                echo "<li>$file <span class=\"success\"></span></li>";
            }
            ?>
            </ul>
	</div>
        
        <h3><a href="#"><?php Generator_Util_Lang::get("generated_dirs") ?></a></h3>
	<div>
            <ul>
            <?php
            foreach($generated_dirs as $dir){
                echo "<li>$dir <span class=\"success\"></span></li>";
            }
            ?>
            </ul>
	</div>
    
	<h3><a href="#"><?php Generator_Util_Lang::get("skipped_files") ?></a></h3>
	<div>
            <ul>
            <?php
            foreach($skipped_files as $file){
                echo "<li>$file</li>";
            }
            ?>
            </ul>
	</div>
        
        <h3><a href="#"><?php Generator_Util_Lang::get("skipped_dirs") ?></a></h3>
	<div>
            <ul>
            <?php
            foreach($skipped_dirs as $dir){
                echo "<li>$dir</li>";
            }
            ?>
            </ul>
	</div>
                       
	<h3><a href="#"><?php Generator_Util_Lang::get("errors") ?></a></h3>
	<div>
            <ul>
            <?php
            foreach($errors as $error){
                echo "<li class=\"error_list\"><span class=\"ui-state-error\">$error</span></li>";
            }
            ?>
            </ul>
	</div>

<?php
}
?>
</div>
</div>