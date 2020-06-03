<?php

add_action('wp_enqueue_scripts', 'initial_admin_links_hide_stylesheet');

function initial_admin_links_hide_stylesheet() {
    wp_enqueue_style( 'inscription_style', plugins_url('/assets/style.css', __FILE__));
}

/*
 * Create new client
 */

function create_client($data) {
    if(!check_mail($data['email'])) {
        global $wpdb;
        $data['password'] = md5($data['password']);
        $create = $wpdb->insert(
          'uc_clients',
            $data,
            array('%s','%d')
        );
        return false;
    } else {
        return 'mail';
    }
}
/*
 * Check mail
 */
function check_mail($email) {
    global $wpdb;
    $result = $wpdb->get_var("select count(*) from uc_clients where email='$email'");
    if($result > 0) {
        return true;
    } else {
        return false;
    }
}