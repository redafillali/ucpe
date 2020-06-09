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
        $pwd = $data['password'];
        $data['password'] = password_hash($pwd, PASSWORD_DEFAULT);
        $create = $wpdb->insert(
            'uc_clients',
            $data,
            array('%s')
        );
        if ($create) {
            $_SESSION['user_id'] = $wpdb->insert_id;
            welcome($data['nom'], $data['email'], $pwd);
            wp_redirect(get_bloginfo('url').'/connexion/');
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
    if ($result && password_verify($pwd, $result->password)) {
        $_SESSION['user_id'] = $result->ID;
        return false;
    } else {
        return true;
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
    wp_redirect(bloginfo('url').'/famille/');
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
}
function update_adhesion($id, $data) {
    global $wpdb;
    $update = $wpdb->update(
        'uc_adherons',
        $data,
        array('ID' => $id),
        array('%s')
    );
}
function delete_adhesion($id) {
    global $wpdb;
    $delete = $wpdb->delete(
        'uc_adherons',
        array('ID' => $id)
    );
}
function create_commande($parent_id, $montant) {
    if(!check_commande($parent_id)) {
        global $wpdb;
        $create = $wpdb->insert(
            'uc_commande',
            array(
                'parent_id' => $parent_id,
                'montant' => $montant
            )
        );

        $wpdb->show_errors();
        $wpdb->print_error();
        if ($create) {
            $id = $wpdb->insert_id;
            $numero = str_pad(intval($id), 6, "0", STR_PAD_LEFT);;
            $update = $wpdb->update(
                'uc_commande',
                array('numero' => $numero),
                array('ID'=> $id),
                array('%s','%d')
            );
            $wpdb->show_errors();
            $wpdb->print_error();
            if ($update) wp_redirect(get_bloginfo('url') . '/recap/');
        }
    } else {
        wp_redirect(get_bloginfo('url') . '/recap/');
    }
}
function get_commande($parent_id) {
    global $wpdb;
    $commande = $wpdb->get_row("select * from uc_commande where parent_id='$parent_id'");
    return $commande;
}
function get_all_commandes() {
    global $wpdb;
    return $wpdb->get_results("select * from uc_commande");
}

function check_commande($parent_id)
{
    global $wpdb;
    $result = $wpdb->get_var("select count(*) from uc_commande where parent_id='$parent_id'");
    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}
function get_recap_data($parent_id) {
    global $wpdb;
    $assur1 = $wpdb->get_var("select count(*) from uc_enfants where parent_id='$parent_id' AND type_assur='1'");
    $assur2 = $wpdb->get_var("select count(*) from uc_enfants where parent_id='$parent_id' AND type_assur='2'");
    $adhesion = $wpdb->get_var("select montant from uc_adherons where family_id='$parent_id'");
    $data = array(
        'assur1' => $assur1,
        'assur2' => $assur2,
        'adhesion' => $adhesion
    );
    return $data;
}
function update_commande($numero, $etat, $TransId, $montant) {
    global $wpdb;
    $commande = $wpdb->get_row("select * from uc_commande where numero='$numero'");
    if($commande->montant == $montant) {
        $update = $wpdb->update(
            'uc_commande',
            array(
                'etat' => $etat,
                'transaction' => $TransId
            ),
            array('numero' => $numero),
            array("%s")
        );
        if($update) {
            $parent = get_client_by_ID($commande->parent_id);
            paiement_bulletin($parent->nom, $parent->email);
        }
        return $update;
    } else {
        return false;
    }
}
function pwd_reset_request($user_email) {
    global $wpdb;
    if(check_mail($user_email)) {    //Generate a random string.
        $token = openssl_random_pseudo_bytes(16);
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);
        $request = $wpdb->insert(
            'uc_reset_pwd',
            array(
                'user_email' => $user_email,
                'token' => $token,
            ),
            array('%s')
        );
        if($request) {
            $url = get_bloginfo('url');
            $message = "<p>Bonjour,</p>
            <p>Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte. Veuillez confirmer la réinitialisation pour choisir un nouveau mot de passe. Autrement, vous pouvez ignorer cet e-mail.</p>
            <p><a href='$url/reinitialisation-mot-de-passe/?token=$token&email=$user_email'>$url/reinitialisation-mot-de-passe/?token=$token&email=$user_email</a></p>
            <p><b>Equipe UCPE,</b></p>";
            $objet = "Réinitialisation du mot de passe";
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $send_mail = wp_mail($user_email, $objet, $message,$headers);
            return !$send_mail;
        } else {
            return true;
        }
    } else {
        return true;
    }
}
function check_token($token, $email) {
    global $wpdb;
    $check_token = $wpdb->get_row("select * from uc_reset_pwd where user_email='$email' and token='$token'");
    if($check_token && $check_token->used == '0') {
        return true;
    } else {
        return false;
    }
}
function reset_pwd($email, $token, $pwd) {
    $url = get_bloginfo('url');
    global $wpdb;
    if(check_mail($email)) {
        if(check_token($token, $email)) {
            $edit_pwd = $wpdb->update(
                'uc_clients',
                array('password' => password_hash($pwd, PASSWORD_DEFAULT)),
                array('email' => $email),
                array("%s")
            );
            $wpdb->print_error();
            $wpdb->show_errors();
            if($edit_pwd) {
                $wpdb->update(
                    'uc_reset_pwd',
                    array('used' => '1'),
                    array('token'=> $token),
                    array('%d', "%s")
                );
                $wpdb->print_error();
                $wpdb->show_errors();
                $message = "<p>Bonjour,</p>
                <p>Votre mot de passe UCPE a bien été modifié.</p>
                <p>Votre Nouveau mot de passe est: <b>$pwd</b></p>
                <p>Vous pouvez vous connecter à votre espace en suivant le lien : <a href='$url/connexion/'>$url/connexion/</a></p>
                <p><b>Equipe UCPE,</b></p>";
                $objet = "votre mot de passe a bien été réinitialisé";
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $send_mail = wp_mail($email, $objet, $message,$headers);
                if($send_mail) bloginfo(get_bloginfo('url').'/connexion/');
                return !$send_mail;
            }
        } else {
            return true;
        }
    } else {
        return true;
    }
}
function get_adhesion_id($parent_id) {
    global $wpdb;
    $adhesion = $wpdb->get_row("select * from uc_adherons where family_id='$parent_id'");
    return $adhesion;
}
function delegue_option($option) {
    if($option == '1') return 'Participer au Bureau UCPE / Activités UCPE';
    if($option == '2') return 'Assister au conseil d’école / établissement';
    if($option == '3') return 'Assister au conseil de classe';
}