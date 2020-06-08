<?php
/*
 * Page de connexion
 */

// Always start this first
session_start();
if($_POST) {
    $error = connexion($_POST['email'], $_POST['password']);
}
if(isset( $_SESSION['user_id'] ) && !empty($_SESSION['user_id'])) :
        wp_redirect(get_bloginfo('url').'/famille/');
    else:
    get_header(); ?>
    <div class="col-12">
        <p align="center">Un e-mail contenant un lien pour modifier votre mot de passe vous sera envoyé.</p>
    </div>
        <form method="post" class="row col-md-6 offset-2">
        <div class="form-group col-12">
            <label for="email">Adresse mail du parent :</label>
            <input id="email" class="form-control" type="email" name="email" required />
        </div>

            <?php if(isset($error)) : ?>
                <div class="alert alert-danger col-12" role="alert">
                    <p>l'adresse mail entré est incorrect.</p>
                </div>
            <?php endif; ?>
        <div class="form-group col-md-12 justify-content-center">
            <button class="btn btn-warning" type="submit">Envoyé</button>
        </div>
    </form>
<?php
    get_footer();
    endif;
?>
