<?php
/*
 * Page de connexion
 */
get_header(); ?>
    <form method="post" class="row col-sm-12">
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
<?php get_footer();
