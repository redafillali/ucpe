<?php

add_action('wp_enqueue_scripts', 'initial_admin_links_hide_stylesheet');

function initial_admin_links_hide_stylesheet()
{
    wp_enqueue_style('inscription_style', plugins_url('/assets/style.css', __FILE__));
}

/*
 * Create new client
 */

function create_client($data)
{
    if (!check_mail($data['email'])) {
        global $wpdb;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $create = $wpdb->insert(
            'uc_clients',
            $data,
            array('%s', '%d')
        );
        if ($create) {
            return false;
        } else {
            return 'error';
        }
    } else {
        return 'mail';
    }
}

/*
 * Check mail
 */
function check_mail($email)
{
    global $wpdb;
    $result = $wpdb->get_var("select count(*) from uc_clients where email='$email'");
    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}

function connexion($login, $pwd)
{
    global $wpdb;
    $result = $wpdb->get_row("select * from uc_clients where email='$login'");
    if (password_verify($pwd, $result->password)) {
        $_SESSION['user_id'] = $result->ID;
        echo 'loggedin';
    } else {
        echo '<br />error';
    }
}

function get_client_by_ID($id)
{
    global $wpdb;
    $client = $wpdb->get_row("select * from uc_clients where ID='$id'");
    return $client;
}

function get_data($type)
{
    global $wpdb;
    $result = '';
    if ($type == 'ville') $result = $wpdb->get_results('select * from uc_villes');
    if ($type == 'assurance') $result = $wpdb->get_results('select * from uc_assurance');
    return $result;
}

function get_ajax_data($type, $id)
{
    global $wpdb;
    if ($type == 'ecole') $result = $wpdb->get_results("select * from uc_etablissements WHERE ville_id = $id");
    if ($type == 'niveau') $result = $wpdb->get_results("select niveaux from uc_etablissements WHERE ID = $id");
    echo json_encode($result);
}

function create_enfant($data)
{
    global $wpdb;
    $create = $wpdb->insert(
        'uc_enfants',
        $data,
        array('%s')
    );
    if ($create) {
        wp_redirect($_SERVER['HTTP_REFERER']);
        return true;
    } else {
        return 'error';
    }
}
function get_enfants($parent_id) {
    global $wpdb;
    $result = $wpdb->get_results("select * from uc_enfants where parent_id='$parent_id'");
    return $result;
}
function delete_enfant($id) {
    global $wpdb;
    $wpdb->delete(
        'uc_enfants',
        array('ID' => $id)
    );
    wp_redirect(bloginfo('url').'?page=family&type=details#enfants');
}
function get_data_by_id($type, $id) {
    global $wpdb;
    $result = '';
    if($type == "ecole") {
        $result = $wpdb->get_row("select etablissement from uc_etablissements WHERE ID='$id'");
        $result = $result->etablissement;
    } elseif ($type == 'assurance') {
        $result = $wpdb->get_row("select * from uc_assurance WHERE ID='$id'");
    }
    return $result;
}
function create_adhesion($data) {
    global $wpdb;
    $create = $wpdb->insert(
        'uc_adherons',
        $data,
        array('%s')
    );
    if($create) wp_redirect(bloginfo('url').'/?page=recap&type=details');git
}