<?php get_header(); ?>
<form method="post" class="row col-sm-12">
    <div class="form-group col-md-6">
        <label for="nom">Nom :</label>
        <input class="form-control" type="text" name="nom" required />
    </div>
    <div class="form-group col-md-6">
        <label for="tel">Téléphone :</label>
        <input class="form-control" type="tel" name="tel" required />
    </div>
    <div class="form-group col-md-6">
        <label for="email">Email :</label>
        <input class="form-control" type="email" name="email" required />
    </div>
    <div class="form-group col-md-6">
        <label for="password">Mot de passe :</label>
        <input class="form-control" type="password" name="password" required />
    </div>
    <div class="form-group col-md-12">
        <input class="form-control btn btn-primary" type="submit" name="submit" value="Envoyer" required />
    </div>
</form>
<?php get_header(); ?>
