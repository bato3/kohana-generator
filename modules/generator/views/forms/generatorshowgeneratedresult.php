<div id="generator_result">
    <?php
    $ok = true;
    if(is_array($result)){
        $is_ok = array();
        foreach($result as $item){
            array_push($is_ok, $item->writeIsOK());
        }
        if (in_array(false, $is_ok)) {
            echo "<div class=\"error\"><h1>Something wrong!</h1></div>";
            $ok = false;
        }
    }else{
        if (!$result->writeIsOK()) {
            echo "<div class=\"error\"><h1>Something wrong!</h1></div>";
            $ok = false;
        }
    }
    ?>
    <div>
        <?php
        $list = array();
        
        if(is_array($result)){
            foreach($result as $item){
                $list = array_merge($list, $item->getItems());
            }
        }else{
            $list = $result->getItems();            
        }
            
        $i = 1;
        foreach ($list as $key => $item) {
            ?>
            <div class="details_link_div"><a href="#" class="details_link" id="<?php echo $i; ?>"><?php echo $key ?></a></div>
            <div class="item_details" id="details_<?php echo $i; ?>">

                <div class="details">
                    <div>
                        <h3>File source code:</h3>
                        <code>
                            <?php
                            if (!$ok) {
                                echo "<div class=\"error\">";
                            }
                            if (is_array($item[1])) {
                                echo "<p>";
                                foreach ($item[1] as $row) {
                                    echo htmlentities($row) . "<br />";
                                }
                                echo "</p>";
                            } else {
                                echo "<p>";
                                echo $item[1];
                                echo "</p>";
                            }
                            if (!$ok) {
                                echo "</div>";
                            }
                            ?>
                        </code>
                    </div>

                    <div>
                        <h3>File path:</h3>
                        <div class="details">
                            <?php
                            echo $item[0];
                            ?>
                        </div>
                    </div>

                </div>

            </div>
            <?php
            ++$i;
        }
        ?>
    </div>
</div>