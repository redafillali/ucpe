<?php
/*
 * Page de connexion
 */

// Always start this first
session_start();
if ($_POST) {
    if(isset($_POST['token'])) :
        reset_pwd($_POST['email'], $_POST['token'], $_POST['pwd']);
        else :
            $error = pwd_reset_request($_POST['email']);
            endif;
}
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
    wp_redirect(get_bloginfo('url') . '/famille/');
else:
    get_header(); ?>
    <div class="col-12">
        <?php if(isset($_GET['token']) && isset($_GET['email'])) : ?>
        <h3 align="center">Réinitialiser le mot de passe</h3>
        <?php else: ?>
        <p align="center">Saisissez l'adresse e-mail utilisée pour votre compte UCPE dans le champ ci-dessous. Nous vous
            enverrons par e-mail la marche à suivre pour réinitialiser votre mot de passe.</p>
        <?php endif; ?>
    </div>
    <?php if ($error) : ?>
    <div class="alert alert-danger col-md-6 offset-md-3" role="alert">
        l'adresse mail entré est incorrect.
    </div>
<?php elseif(!empty($error)  && $error == false):  ?>
    <div class="alert alert-success col-md-6 offset-md-3" role="alert">
        <h3>Vérifiez votre boîte de réception !</h3>
        Si l'adresse fournie correspond à celle associée à votre compte, vous allez bientôt recevoir un e-mail de
        réinitialisation du mot de passe.
    </div>
<?php endif; ?>
<?php if(isset($_GET['token']) && isset($_GET['email'])) : ?>
<?php if(check_token($_GET['token'], $_GET['email'])) : ?>

    <form method="post" class="row col-md-6 offset-md-3">
        <input type="hidden" value="<?php echo $_GET['token']; ?>" name="token">
        <div class="form-group col-12">
            <label for="email">Adresse mail :</label>
            <input id="email" class="form-control" type="email" name="email" value="<?php echo $_GET['email']; ?>" required readonly />
        </div>
        <div class="form-group col-12">
            <label for="email">Mot de passe :</label>
            <input class="form-control" type="password" minlength="8" name="pwd" id="pwd" required />
        </div>
        <div class="form-group col-12">
            <label for="email">Confirmer votre mot de passe :</label>
            <input class="form-control" type="password" minlength="8" name="pwd2" id="pwd2" required />
        </div>

        <div class="form-group col-md-12 justify-content-center">
            <button class="btn btn-warning" type="submit">Modifer</button>
        </div>
        <script>
            var password = document.getElementById("pwd")
                , confirm_password = document.getElementById("pwd2");

            function validatePassword(){
                if(password.value !== confirm_password.value) {
                    confirm_password.setCustomValidity("Les mots de passe ne correspondent pas");
                } else {
                    confirm_password.setCustomValidity('');
                }
            }

            password.onchange = validatePassword;
            confirm_password.onkeyup = validatePassword;
        </script>
    </form>
    <?php else : ?>
        <div class="alert alert-danger col-md-6 offset-md-3" role="alert">
            Le lien est incorrect ou il a déjà été utilisé !
        </div>
    <?php endif; ?>
<?php else : ?>
    <form method="post" class="row col-md-6 offset-md-3">
        <div class="form-group col-12">
            <label for="email">Adresse mail du parent :</label>
            <input id="email" class="form-control" type="email" name="email" required/>
        </div>
        <div class="form-group col-md-12 justify-content-center">
            <button class="btn btn-warning" type="submit">Envoyé</button>
        </div>
    </form>
<?php endif; ?>
    <?php
    get_footer();
endif;
?>
