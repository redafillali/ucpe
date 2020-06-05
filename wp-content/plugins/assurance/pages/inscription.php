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
<div class="row col-md-12">
    <ul id="progressbar">
        <li class="active">Création du compte</li>
        <li>Informations sur les enfants & adhésion</li>
        <li>Confirmation & Paiement</li>
    </ul>
</div>
<h2>1 - Renseignements concernant le Parent :</h2>
<form method="post" class="row col-sm-12">
    <div class="form-group col-md-6">
        <label for="nom">Nom et prénom du parent :</label>
        <input id="nom" class="form-control" type="text" name="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'] ?>" required />
    </div>
    <div class="form-group col-md-6">
        <label for="tel">Téléphone du parent :</label>
        <input id="tel" class="form-control" type="tel" name="tel" value="<?php if(isset( $_POST['tel'])) echo $_POST['tel'] ?>" required />
    </div>
    <div class="form-group col-md-6">
        <label for="email">Adresse mail du parent :</label>
        <input id="email" class="form-control" type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" required />
        <small id="emailError" class="form-text <?php if(isset($error) && $error == 'mail') echo 'show'; ?>">Désolé, cette adresse mail à déjà été utilisée</small>
    </div>
    <div class="form-group col-md-6">
        <label for="password">Mot de passe :</label>
        <input id="password" class="form-control" type="password" name="password" required />
    </div>
    <div class="form-group col-md-12 justify-content-center">
        <button class="btn btn-warning" type="submit">Envoyer</button>
    </div>
</form>
<?php get_footer(); ?>