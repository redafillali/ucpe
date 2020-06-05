<?php

// Always start this first
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
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
        }
        if(isset($_POST['nom_pere'])) {
            $data = array(
                'family_id' =>  $_SESSION['user_id'],
                'nom_pere' =>  $_POST['nom_pere'],
                'nom_mere' =>  $_POST['nom_mere'],
                'tel_mere' =>  $_POST['tel_mere'],
                'tel_pere' =>  $_POST['tel_pere'],
                'mail_pere' =>  $_POST['email_pere'],
                'mail_mere' =>  $_POST['email_mere'],
                'delegue' =>  $_POST['delegue'],
                'montant' =>  $_POST['montant_adh'],
            );
            create_adhesion($data);
        }
    endif;
    if ($_GET) :
        if (isset($_GET['action']) && isset($_GET['child']) && $_GET['action'] == 'delete') {
            delete_enfant($_GET['child']);
        }
    endif;
    get_header(); ?>
    <div class="col-md-12 row">
        <div class="col-md-12">
            <h1>Details du parent :</h1>
        </div>
        <?php
        $parent = get_client_by_ID($_SESSION['user_id']);
        ?>
        <div class="col-md-6">
            Nom : <?php echo $parent->nom ?>
        </div>
        <div class="col-md-6">
            Téléphone : <?php echo $parent->tel ?>
        </div>
        <div class="col-md-12">
            Email : <?php echo $parent->email ?>
        </div>
    </div>
    <div class="col-md-12 row" id="enfants">
        <div class="col-md-12">
            <h1>Informations enfants :</h1>
        </div>
        <?php
        $enfants = get_enfants($_SESSION['user_id']);
        $i = 0;
        if ($enfants) :
            ?>
            <table class="table table-striped">
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
                            <a class="btn btn-primary"
                               href="?page=family&type=details&action=delete&child=<?php echo $enfant->ID ?>">
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
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td align="right">Sous-total</td>
                    <td><?php echo $montant_total . " DH"; ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        <?php endif; ?>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajouterenfant">
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
                                <input id="tel" class="form-control" type="date" name="dateNaissance" required/>
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
                                <button class="form-control btn btn-primary" type="submit">Ajouter</button>
                            </div>
                            <div class="form-group col-md-12">
                                <button class="form-control btn btn-primary" type="submit"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 row">
        <h1>Adhésion :</h1>
        <div class="col-md-12">
            <p>Souhaitez-vous adhérer à l'UCPE <input class="" type="radio" name="adherer"
                                                      id="adherer" value="oui" required>
                <label class="form-check-label" for="adherer">
                    Oui
                </label> <input class="" type="radio" name="adherer"
                                id="adherer" value="non" required>
                <label class="form-check-label" for="adherer">
                    Non
                </label>
            </p>
        </div>
        <form class="col-md-12 row" method="post" id="adherer_form" style="display: none;">
            <input type="hidden">
            <div class="form-group col-md-4">
                <label for="nom_pere">Nom du père :</label>
                <input type="text" name="nom_pere" id="nom_pere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_pere">Téléphone du père :</label>
                <input type="text" name="tel_pere" id="tel_pere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_pere">Adresse mail du père :</label>
                <input type="text" name="email_pere" id="email_pere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="nom_mere">Nom du mère :</label>
                <input type="text" name="nom_mere" id="nom_mere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="tel_mere">Téléphone du mère :</label>
                <input type="text" name="tel_mere" id="tel_mere" class="form-control"/>
            </div>
            <div class="form-group col-md-4">
                <label for="email_mere">Adresse mail du mère :</label>
                <input type="text" name="email_mere" id="email_mere" class="form-control"/>
            </div>
            <div class="form-group col-md-6">
                <label for="delegue">Parent délégué de la classe de votre enfant :</label>
                <select name="delegue" class="form-control" id="delegue">
                    <option value="">Etes-vous intéréssé?</option>
                    <option value="oui">Intéressé</option>
                    <option value="Non">Pas intéressé</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="montant_adh">Montant de l'adhésion :</label>
                <input type="number" step="10" value="100" min="100" name="montant_adh" id="montant_adh" class="form-control"/>
            </div>
        </form>
        <div class="col-md-12 passer" style="display: none">
            <button class="btn btn-primary" type="submit" form="adherer_form">
                Passer à l'étape suivante
            </button>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            jQuery('input#adherer').on('change', function() {
                if(jQuery("input#adherer:checked").val() === 'oui') {
                    jQuery('#adherer_form input, #adherer_form select').prop('disabled', false).prop('required', true);
                    jQuery('#adherer_form').fadeIn();
                } else {
                    jQuery('#adherer_form input, #adherer_form select').prop('required', false).prop('disabled', true);
                    jQuery('#adherer_form').fadeOut();
                }
                jQuery(".passer").fadeIn();
            });
            jQuery('select#ville').on('change', function () {
                jQuery('select#etablissement').empty().append('<option value="">Choisissez une établissement</option>').prop('disabled', false);
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
                    }
                });
            })
            jQuery('select#etablissement').on('change', function () {
                jQuery('select#classe').empty().append('<option value="">Choisissez un niveau</option>').prop('disabled', false);
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
                            })


                        });
                    }
                });
            })
        });
    </script>
    <?php
    get_footer();
else:
    wp_redirect(get_bloginfo('url') . '/?page=connexion&type=parent');
endif;
