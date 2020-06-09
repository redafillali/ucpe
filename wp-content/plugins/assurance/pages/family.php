<?php

// Always start this first
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
    $commande = get_commande($_SESSION['user_id']);
    $adhesion = get_adhesion_id($_SESSION['user_id']);
    if($commande && $commande->etat == 3) wp_redirect(get_bloginfo('url').'/confirmation/');
    if ($_POST) :
        if (isset($_POST['nom'])) {
            $data = array(
                'parent_id' => $_SESSION['user_id'],
                'nom' => $_POST['nom'],
                'prenom1' => $_POST['prenom1'],
                'prenom2' => $_POST['prenom2'],
                'dateNaissance' => $_POST['dateNaissance'],
                'ville_id' => $_POST['ville'],
                'ecole_id' => $_POST['etablissement'],
                'niveau' => $_POST['classe'],
                'type_assur' => $_POST['type_assur'],
            );
            create_enfant($data);
        };
        if (isset($_POST['nom_pere']) && !empty($_POST['nom_pere'])) {
            $data = array(
                'family_id' => $_SESSION['user_id'],
                'nom_pere' => $_POST['nom_pere'],
                'nom_mere' => $_POST['nom_mere'],
                'tel_mere' => $_POST['tel_mere'],
                'tel_pere' => $_POST['tel_pere'],
                'mail_pere' => $_POST['email_pere'],
                'mail_mere' => $_POST['email_mere'],
                'delegue' => $_POST['delegue'],
                'montant' => $_POST['montant_adh'],
            );
            $montantCmd = $_POST['montant_enfants'] + $_POST['montant_adh'];
            if($adhesion) {
                if($_POST['adherer'] == 'oui') update_adhesion($adhesion->ID, $data);
                if($_POST['adherer'] == 'non') delete_adhesion($adhesion->ID);
            } else {
                create_adhesion($data);
            }
            create_commande($_SESSION['user_id'], $montantCmd);
        } elseif($_POST['adherer'] == 'non') {
            if($adhesion) delete_adhesion($adhesion->ID);
            create_commande($_SESSION['user_id'], $_POST['montant_enfants']);
        }
    endif;
    if ($_GET) :
        if (isset($_GET['action']) && isset($_GET['child']) && $_GET['action'] == 'delete') {
            delete_enfant($_GET['child']);
        }
    endif;
    $adhesion = get_adhesion_id($_SESSION['user_id']);
    get_header(); ?>
    <div class="row col-md-12">
        <ul id="progressbar">
            <li>Création du compte</li>
            <li class="active">Informations sur les enfants & adhésion</li>
            <li>Confirmation & Paiement</li>
            <li>Impression du bulletin</li>
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
            Téléphone du père : <b><?php echo $parent->tel ?></b>
        </div>
        <div class="col-md-6">
            Téléphone du mère : <b><?php echo $parent->tel_mere ?></b>
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
                    <th scope="col"></th>
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
                        <td>
                            <a class=""
                               href="<?php the_permalink(); ?>?action=delete&child=<?php echo $enfant->ID ?>">
                                <svg class="bi bi-x-square" width="1em" height="1em" viewBox="0 0 16 16"
                                     fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                    <path fill-rule="evenodd"
                                          d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                    <path fill-rule="evenodd"
                                          d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot style="background-color: #f1f1f1; font-weight: 700;">
                <tr>
                    <td></td>
                    <td colspan="3">Montant total</td>
                    <td><?php echo $montant_total . " DH"; ?></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        <?php endif; ?>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ajouterenfant" style="margin-left: 0">
            Ajouter un enfant
        </button>
        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="ajouterenfant" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ajouter un enfant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="enfant" class="row col-sm-12">
                            <div class="form-group col-md-6">
                                <label for="nom">Nom de l'enfant :</label>
                                <input id="nom" class="form-control" type="text" name="nom" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nom">Prénom 1 de l'enfant :</label>
                                <input id="nom" class="form-control" type="text" name="prenom1" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nom">Prénom 2 de l'enfant * :</label>
                                <input id="nom" class="form-control" type="text" name="prenom2"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tel">Date de naissance :</label>
                                <input id="tel" class="form-control" type="date" name="dateNaissance" min="1990-01-01" max="2020-01-01" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Ville :</label>
                                <select name="ville" id="ville" class="form-control" required>
                                    <option value="">Choisissez une ville</option>
                                    <?php
                                    $villes = get_data('ville');
                                    foreach ($villes as $ville) : ?>
                                        <option value="<?php echo $ville->ID ?>"><?php echo $ville->ville ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Etablissement :</label>
                                <select name="etablissement" id="etablissement" class="form-control" required disabled>
                                    <option value="">Choisissez une établissement</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Niveau :</label>
                                <select name="classe" id="classe" class="form-control" required disabled>
                                    <option value="">Choisissez un niveau</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Option de l'assurance :</label>
                                <select name="type_assur" id="option" class="form-control" required>
                                    <option value="">Choisissez une option</option>
                                    <?php $assurances = get_data('assurance');
                                    foreach ($assurances as $assurance):?>
                                        <option value="<?php echo $assurance->ID ?>"><?php echo $assurance->assurance . " - " . $assurance->prix . " DH" ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button class="form-control btn btn-primary" form="enfant" type="submit">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2>3 - Adhésion :</h2>
    <div class="col-md-12 row">
        <div class="col-md-12">
            <p style="color:#f8b41e; font-weight: 700;">Souhaitez-vous adhérer à l'UCPE <input class="" type="radio" name="adherer"
                                                      id="adherer" value="oui" <?php if($adhesion) echo 'checked' ?> required>
                <label style="color: #474747" class="form-check-label" for="adherer">
                    Oui
                </label> <input class="" type="radio" name="adherer"
                                id="adherer" <?php if(!$adhesion) echo 'checked' ?>  value="non" required>
                <label style="color: #474747" class="form-check-label" for="adherer">
                    Non
                </label>
            </p>
        </div>
        <form class="col-md-12 row" method="post" id="adherer_form" style="<?php if(!$adhesion) echo 'display:none' ?>">
            <input type="hidden">
            <div class="form-group col-md-4">
                <label for="nom_pere">Nom du père :</label>
                <input type="text" name="nom_pere" id="nom_pere" <?php if($adhesion) echo "value='$adhesion->nom_pere'"; ?>" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_pere">Téléphone du père :</label>
                <input type="text" name="tel_pere" minlength="10" pattern=".{10,10}"   id="tel_pere"<?php if($adhesion) echo "value='$adhesion->tel_pere'"; ?> class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_pere">Adresse mail du père :</label>
                <input type="text" name="email_pere" id="email_pere"<?php if($adhesion) echo "value='$adhesion->mail_pere'"; ?> class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="nom_mere">Nom du mère :</label>
                <input type="text" name="nom_mere"<?php if($adhesion) echo "value='$adhesion->nom_mere'"; ?> id="nom_mere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_mere">Téléphone du mère :</label>
                <input type="text" name="tel_mere"<?php if($adhesion) echo "value='$adhesion->tel_mere'"; ?> id="tel_mere" minlength="10" pattern=".{10,10}"  class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_mere">Adresse mail du mère :</label>
                <input type="text" name="email_mere" <?php if($adhesion) echo "value='$adhesion->mail_mere'"; ?> id="email_mere" class="form-control"/>
            </div>
            <div class="form-group col-md-6">
                <label for="delegue">Souhaitez-vous être parent délégué de la classe de votre enfant ?</label>
                <select name="delegue" class="form-control" id="delegue">
                    <option value="">Etes-vous intéréssé?</option>
                    <option value="1" <?php if($adhesion && $adhesion->delegue == '1') echo "selected"; ?>>Participer au Bureau UCPE / Activités UCPE</option>
                    <option value="2" <?php if($adhesion && $adhesion->delegue == '1') echo "selected"; ?>>Assister au conseil d’école / établissement</option>
                    <option value="3" <?php if($adhesion && $adhesion->delegue == '1') echo "selected"; ?>>Assister au conseil de classe</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="montant_adh">Montant de l'adhésion :</label>
                <input type="number" step="10" min="100" name="montant_adh" <?php if($adhesion) echo "value='$adhesion->montant'"; ?>  id="montant_adh"  class="form-control"/>
            </div>
            <input type="hidden" name="montant_enfants" value="<?php if($montant_total) echo $montant_total ?>">
            <input type="hidden" id="adherer_hidden" name="adherer" <?php if($adhesion) {echo "value='oui'";} else {echo "value='non'";} ?> >
        </form>
        <div class="col-md-12 passer" style="">
            <button class="btn btn-warning" type="submit" form="adherer_form">
                Valider
            </button>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            jQuery('input#adherer').on('change', function () {
                if (jQuery("input#adherer:checked").val() === 'oui') {
                    jQuery('#adherer_form input, #adherer_form select').not("input[type=hidden]").prop('disabled', false).prop('required', true);
                    jQuery('#adherer_form').fadeIn();
                    jQuery('#adherer_hidden').val('oui');
                } else {
                    jQuery('#adherer_form input, #adherer_form select').not("input[type=hidden]").prop('required', false).prop('disabled', true);
                    jQuery('#adherer_form').fadeOut();
                    jQuery('#adherer_hidden').val('non');
                }
                jQuery(".passer").fadeIn();
            });
            jQuery('select#ville').on('change', function () {
                jQuery('select#etablissement').empty().append('<option value="">Choisissez une établissement</option>').prop('disabled', true);
                jQuery.ajax({
                    type: "POST",
                    url: "<?php bloginfo('url');?>/?page=ajax.data",
                    data: {'type': 'ecole', 'id': jQuery('select#ville').val()},
                    success: function (data) {
                        // Parse the returned json data
                        var opts = jQuery.parseJSON(data);
                        // Use jQuery's each to iterate over the opts value
                        jQuery.each(opts, function (i, d) {
                            // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                            jQuery('select#etablissement').append('<option value="' + d.ID + '">' + d.etablissement + '</option>');
                        });
                        jQuery('select#etablissement').prop('disabled', false);
                    }
                });
            })
            jQuery('select#etablissement').on('change', function () {
                jQuery('select#classe').empty().append('<option value="">Choisissez un niveau</option>').prop('disabled', true);
                jQuery.ajax({
                    type: "POST",
                    url: "<?php bloginfo('url');?>/?page=ajax.data",
                    data: {'type': 'niveau', 'id': jQuery('select#etablissement').val()},
                    success: function (data) {
                        // Parse the returned json data
                        var opts = jQuery.parseJSON(data);
                        // Use jQuery's each to iterate over the opts value
                        jQuery.each(opts, function (i, d) {
                            // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                            var niveaux = d.niveaux.split(',');
                            jQuery.each(niveaux, function (index, value) {
                                jQuery('select#classe').append('<option value="' + value + '">' + value + '</option>');
                            });
                            jQuery('select#classe').prop('disabled', false);
                        });
                    }
                });
            })
        });
    </script>
    <?php
    get_footer();
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
endif;
