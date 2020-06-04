<?php
add_filter('init', function ($template) {
    if (isset($_GET['page'])) {
        $page = $_GET["page"];
        include plugin_dir_path(__FILE__) . "pages/$page.php";
        die;
    }

    if (isset($_GET['page']) && $_GET['page'] == "preload") {
        include plugin_dir_path(__FILE__) . "pages/precharge.php";
        die;
    }
});