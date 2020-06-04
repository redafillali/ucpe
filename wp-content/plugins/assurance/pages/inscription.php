<<<<<<< HEAD
<?php
/*
  * Template neme: Inscription parent
  */
if($_POST)  {
    $error = create_client($_POST);
    echo $error;
    if($error) {
        wp_redirect(get_bloginfo('url') . '/?page=inscrption&type=parent&error='.$error);
    }
}
get_header(); ?>
<form method="post" class="row col-sm-12">
    <div class="form-group col-md-6">
        <label for="nom">Nom :</label>
        <input id="nom" class="form-control" type="text" name="nom" required />
    </div>
    <div class="form-group col-md-6">
        <label for="tel">Téléphone :</label>
        <input id="tel" class="form-control" type="tel" name="tel" required />
    </div>
    <div class="form-group col-md-6">
        <label for="email">Email :</label>
        <input id="email" class="form-control" type="email" name="email" required />
        <small id="emailError" class="form-text <?php if($_GET['error'] == 'mail') echo 'show'; ?>">Désolé, cette adresse mail à déjà été utilisée</small>
    </div>
    <div class="form-group col-md-6">
        <label for="password">Mot de passe :</label>
        <input id="password" class="form-control" type="password" name="password" required />
    </div>
    <div class="form-group col-md-12">
        <button class="form-control btn btn-primary" type="submit">Envoyer</button>
    </div>
</form>
<?php get_footer(); ?>