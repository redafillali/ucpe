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
        <form method="post" class="row col-md-6 offset-2">
            <?php if($error) : ?>
                <div class="alert alert-danger col-12" role="alert">
                    l'email ou le mot de passe entré est incorrect.
                </div>
            <?php endif; ?>
        <div class="form-group col-12">
            <label for="email">Adresse mail du parent :</label>
            <input id="email" class="form-control" type="email" name="email" required />
        </div>
        <div class="form-group col-12">
            <label for="password">Mot de passe :</label>
            <input id="password" class="form-control" type="password" name="password" required />
        </div>
        <div class="form-group col-md-6">
            <input id="garder" class="" type="checkbox" name="garder" value="oui" required checked />
            <label for="garder">Se souvenir de moi</label>
        </div>
        <div class="form-group col-md-6">
            <a href="<?php bloginfo('url'); ?>/reinitialisation-mot-de-passe/">Mot de passe oublié?</a>
        </div>
        <div class="form-group col-md-12 justify-content-center">
            <button class="btn btn-warning" type="submit">Se connecter</button>
        </div>
    </form>
<?php
    get_footer();
    endif;
?>
