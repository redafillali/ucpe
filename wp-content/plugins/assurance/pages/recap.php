<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :

    get_header(); ?>
    <div class="row col-md-12">
        <ul id="progressbar">
            <li>Création du compte</li>
            <li>Informations sur les enfants & adhésion</li>
            <li class="active">Confirmation & Paiement</li>
        </ul>
    </div>
    <h2>Récapitulatif avant paiement :</h2>
    <div class="col-sm-12">
        <?php $data = get_recap_data($_SESSION['user_id']); ?>
        <table class="table enfants">
            <thead>
            <tr>
                <th scope="col">Désignation</th>
                <th scope="col">Tarif unitaire</th>
                <th scope="col">Quantité</th>
                <th scope="col">Tarif total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Assurance Globale</td>
                <td><?php echo get_data_by_id('assurance', '1')->prix ?> DH</td>
                <td><?php echo $data['assur1'] ?></td>
                <td><?php echo get_data_by_id('assurance', '1')->prix * $data['assur1'] ?> DH</td>
            </tr>
            <tr>
                <td>Assurance Globale Plus</td>
                <td><?php echo get_data_by_id('assurance', '2')->prix ?> DH</td>
                <td><?php echo $data['assur2'] ?></td>
                <td><?php echo get_data_by_id('assurance', '2')->prix * $data['assur2'] ?> DH</td>
            </tr>
            <tr>
                <td>Adhésion</td>
                <td></td>
                <td></td>
                <td><?php echo $data['adhesion'] ?> DH</td>
            </tr>
            </tbody>
            <tfoot style="background-color: #f1f1f1; font-weight: 700;">
            <tr>
                <td>Montant Total</td>
                <td></td>
                <td></td>
                <td>
                    <?php
                    $total = get_data_by_id('assurance', '2')->prix * $data['assur2'];
                    $total += $data['adhesion'];
                    $total += get_data_by_id('assurance', '1')->prix * $data['assur1'];
                    echo $total;
                    ?> DH
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-12">
        <button class="btn btn-warning" type="submit" form="adherer_form">
            Procéder au paiement
        </button>
    </div>
    <?php get_footer();
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
endif;