<div id="generator_result">
    <?php
    if (!$write_ok) {
        echo "<div class=\"error\"><h1>Something wrong!</h1></div>";
    }
    ?>
    <div>
        <h3>Generated models:</h3>
        <div class="details">
            <?php
            echo $files;
            ?>
        </div>
    </div>
</div>