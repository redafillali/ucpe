<?php
/*
 * Page de connexion
 */

// Always start this first
session_start();
if($_POST) {
    connexion($_POST['email'], $_POST['password']);
}
if(isset( $_SESSION['user_id'] ) && !empty($_SESSION['user_id'])) :
        wp_redirect(get_bloginfo('url').'/?page=family&type=details');
    else:
    get_header(); ?>
    <form method="post" class="row col-sm-12">
        <div class="form-group col-md-6">
            <label for="email">Email :</label>
            <input id="email" class="form-control" type="email" name="email" required />
        </div>
        <div class="form-group col-md-6">
            <label for="password">Mot de passe :</label>
            <input id="password" class="form-control" type="password" name="password" required />
        </div>
        <div class="form-group col-md-12">
            <button class="form-control btn btn-primary" type="submit">Envoyer</button>
        </div>
    </form>
<?php
    get_footer();
    endif;
?>
