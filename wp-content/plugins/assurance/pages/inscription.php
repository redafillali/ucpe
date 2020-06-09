<?php
/*
  * Template neme: Inscription parent
  */
session_start();
session_destroy();
if($_POST)  {
    $error = create_client($_POST);
}
get_header(); ?>
<div class="row col-12">
    <ul id="progressbar">
        <li class="active">Création du compte</li>
        <li>Informations sur les enfants & adhésion</li>
        <li>Confirmation & Paiement</li>
        <li>Impression du bulletin</li>
    </ul>
</div>
<h2>1 - Renseignements concernant le Parent :</h2>
<?php if($error) : var_dump($error); ?>
    <div class="alert alert-danger col-12" role="alert">
        Un problème est survenu lors de votre inscription
    </div>
<?php endif; ?>
<form method="post" class="row col-sm-12">
    <div class="form-group col-md-6">
        <label for="nom">Nom et prénom du parent :</label>
        <input id="nom" class="form-control" type="text" name="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'] ?>" required />
    </div>
    <div class="form-group col-md-6">
        <label for="tel">Tél père :</label>
        <input id="tel" class="form-control" type="tel" name="tel"  minlength="10" pattern=".{10,10}" value="<?php if(isset( $_POST['tel'])) echo $_POST['tel'] ?>" required />
    </div>
    <div class="form-group col-md-6">
        <label for="tel">Tél mère :</label>
        <input id="tel" class="form-control" type="tel" name="tel_mere"  minlength="10" pattern=".{10,10}" value="<?php if(isset( $_POST['tel_mere'])) echo $_POST['tel_mere'] ?>" required />
    </div>
    <div class="form-group col-md-6">
        <label for="email">Adresse mail du parent :</label>
        <input id="email" class="form-control" type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" required />
        <small id="emailError" class="form-text <?php if(isset($error) && $error == 'mail') echo 'show'; ?>">Désolé, cette adresse mail à déjà été utilisée</small>
    </div>
    <div class="form-group col-md-6">
        <label for="password">Mot de passe :</label>
        <input id="password" class="form-control" type="password" name="password" minlength="8" required />
    </div>
    <div class="form-group col-md-6">
        <label for="password2">Confirmer votre mot de passe :</label>
        <input id="password2" class="form-control" type="password" name="password" minlength="8" required />
    </div>

    <div class="form-group col-md-12 justify-content-center">
        <button class="btn btn-warning" type="submit">Envoyer</button>
    </div>
</form>
    <script>
        var password = document.getElementById("password")
            , confirm_password = document.getElementById("password2");

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
<?php get_footer(); ?>