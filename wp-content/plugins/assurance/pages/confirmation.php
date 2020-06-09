<?php

// Always start this first
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
    get_header(); ?>
    <div class="row col-md-12">
        <ul id="progressbar">
            <li>Création du compte</li>
            <li>Informations sur les enfants & adhésion</li>
            <li>Confirmation & Paiement</li>
            <li class="active">Impression du bulletin</li>
        </ul>
    </div>
    <h2>1 - Renseignements concernant le Parent :</h2>
    <div class="col-md-12 row parent_info">
        <?php
        $parent = get_client_by_ID($_SESSION['user_id']);
        ?>
        <div class="col-md-6">
            Nom et prénom du parent : <b><?php echo $parent->nom ?></b>
        </div>
        <div class="col-md-6">
            Téléphone du parent : <b><?php echo $parent->tel ?></b>
        </div>
        <div class="col-md-12">
            Adresse mail du parent : <b><?php echo $parent->email ?></b>
        </div>
    </div>
    <h2>2 - Renseignements de(s) enfant(s) :</h2>
    <div class="col-md-12 row" id="enfants">
        <?php
        $enfants = get_enfants($_SESSION['user_id']);
        $i = 0;
        if ($enfants) :
            ?>
            <table class="table table-striped enfants">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Etablissement</th>
                    <th scope="col">Niveau</th>
                    <th scope="col">Assurance</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $montant_total = 0;
                foreach ($enfants as $enfant) :
                    $i++;
                    ?>
                    <tr>
                        <th scope="row"><?php echo $i; ?></th>
                        <td><?php echo $enfant->nom . " " . $enfant->prenom1 ?></td>
                        <td><?php echo get_data_by_id('ecole', $enfant->ecole_id) ?></td>
                        <td><?php echo $enfant->niveau ?></td>
                        <td><?php
                            $assurance = get_data_by_id('assurance', $enfant->type_assur);
                            echo $assurance->assurance . " - " . $assurance->prix . "DH";
                            $montant_total += $assurance->prix; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot style="background-color: #f1f1f1; font-weight: 700;">
                <tr>
                    <td></td>
                    <td colspan="2">Montant total</td>
                    <td></td>
                    <td><?php echo $montant_total . " DH"; ?></td>
                </tr>
                </tfoot>
            </table>
        <?php endif; ?>

    </div>
    <h2>3 - Adhésion :</h2>
    <div class="col-md-12 row">
        <div class="col-md-12">
        <?php $adhesion = get_adhesion_id($_SESSION['user_id']); ?>
            <p style="color:#f8b41e; font-weight: 700;">Souhaitez-vous adhérer à l'UCPE : <?php if($adhesion): echo 'Oui'; else: echo 'Non'; endif;?></p>
        </div>
        <?php if($adhesion) : ?>
        <form class="col-md-12 row" method="post" id="adherer_form">
            <input type="hidden">
            <div class="form-group col-md-4">
                <label for="nom_pere">Nom du père :</label>
                <input type="text" name="nom_pere" id="nom_pere" disabled value="<?php echo $adhesion->nom_pere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_pere">Téléphone du père :</label>
                <input type="text" name="tel_pere" id="tel_pere" disabled value="<?php echo $adhesion->tel_pere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_pere">Adresse mail du père :</label>
                <input type="text" name="email_pere" id="email_pere" disabled value="<?php echo $adhesion->mail_pere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="nom_mere">Nom du mère :</label>
                <input type="text" name="nom_mere" id="nom_mere" disabled value="<?php echo $adhesion->nom_mere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_mere">Téléphone du mère :</label>
                <input type="text" name="tel_mere" id="tel_mere" disabled value="<?php echo $adhesion->tel_mere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_mere">Adresse mail du mère :</label>
                <input type="text" name="email_mere" id="email_mere" disabled value="<?php echo $adhesion->mail_mere ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-6">
                <label for="delegue">Souhaitez-vous être parent délégué de la classe de votre enfant ? :</label> <b><?php echo delegue_option($adhesion->delegue) ?></b>
            </div>
            <div class="form-group col-md-6">
                <label for="montant_adh">Montant de l'adhésion :</label> <b><?php echo $adhesion->montant ?> DH</b>
            </div>
        </form>
        <?php endif; ?>
    </div>
    <h2>4 - Impression du bulletin :</h2>
    <div class="col-md-12 row parent_info">
        <button type="button" class="btn btn-warning">
            <a href="<?php bloginfo('url'); ?>/bulletin_equity/generate.php" style="color: white">Imprimer le bulletin</a>
        </button>
    </div>
    <?php
    get_footer();
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
endif;
