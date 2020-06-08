<?php
if(isset($_POST['id']) && isset($_POST['type'])) {
    get_ajax_data($_POST['type'], $_POST['id']);
}