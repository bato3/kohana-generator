<div id="generator_result">
    <?php
    if (!$write_ok) {
        echo "<div class=\"error\"><h1>Something wrong!</h1></div>";
    }
    ?>
    <div>
        <h3>File source code:</h3>
        <div class="details">
            <code>
                <?php
                if (!$write_ok) {
                    echo "<div class=\"error\">";
                }
                foreach ($rows as $row) {
                    echo htmlentities($row) . "<br />";
                }
                if (!$write_ok) {
                    echo "</div>";
                }
                ?>
            </code>
        </div>
    </div>
    <div>
        <h3>File path:</h3>
        <div class="details">
            <?php
            echo $savepath;
            ?>
        </div>
    </div>
</div>