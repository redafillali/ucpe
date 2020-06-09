<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
    $commande = get_commande($_SESSION['user_id']);
    if($commande && $commande->etat == '3') wp_redirect(get_bloginfo('url').'/souscription/confirmation/');
    get_header(); ?>
    <div class="row col-md-12">
        <ul id="progressbar">
            <li>Création du compte</li>
            <li>Informations sur les enfants & adhésion</li>
            <li class="active">Confirmation & Paiement</li>
            <li>Impression du bulletin</li>
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
                <td><?php echo get_data_by_id('assurance', '1')->prix ?></td>
                <td><?php echo $data['assur1'] ?></td>
                <td><?php echo get_data_by_id('assurance', '1')->prix * $data['assur1'] ?></td>
            </tr>
            <tr>
                <td>Assurance Globale Plus</td>
                <td><?php echo get_data_by_id('assurance', '2')->prix ?></td>
                <td><?php echo $data['assur2'] ?></td>
                <td><?php echo get_data_by_id('assurance', '2')->prix * $data['assur2'] ?></td>
            </tr>
            <tr>
                <td>Adhésion</td>
                <td></td>
                <td></td>
                <td><?php echo $data['adhesion'] ?></td>
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
        <button class="btn btn-warning" type="submit" form="paiement" style="display: inline-block;">
            <a href="<?php bloginfo('url'); ?>/famille/" style="color: white;">Modifier</a>
        </button>
        <button class="btn btn-warning" type="submit" form="paiement" style="display: inline-block;">
            Procéder au paiement
        </button>
        <?php

        $orgClientId  =   "600001730";
        $orgAmount = $total;
        $orgOkUrl =  get_bloginfo('url')."/cmi/ok-fail.php";
        $orgFailUrl = get_bloginfo('url')."/cmi/ok-fail.php";
        $shopurl = get_bloginfo('url');
        $orgTransactionType = "PreAuth";
        $orgRnd =  microtime();
        $orgCallbackUrl = get_bloginfo('url')."/cmi/callback.php";
        $orgCurrency = "504";
        $parent = get_client_by_ID($_SESSION['user_id']);
        $commande = get_commande($_SESSION['user_id']);
        ?>
        <form method="post" id="paiement" action="/cmi/SendData.php">
            <input type="hidden" name="clientid" value="<?php echo $orgClientId ?>">
            <input type="hidden" name="amount" value="<?php echo $orgAmount ?>">
            <input type="hidden" name="okUrl" value="<?php echo $orgOkUrl ?>">
            <input type="hidden" name="failUrl" value="<?php echo $orgFailUrl ?>">
            <input type="hidden" name="TranType" value="<?php echo $orgTransactionType ?>">
            <input type="hidden" name="callbackUrl" value="<?php echo $orgCallbackUrl ?>">
            <input type="hidden" name="shopurl" value="<?php echo $shopurl ?>">
            <input type="hidden" name="currency" value="<?php echo $orgCurrency ?>">
            <input type="hidden" name="rnd" value="<?php echo $orgRnd ?>">
            <input type="hidden" name="storetype" value="3D_PAY_HOSTING">
            <input type="hidden" name="hashAlgorithm" value="ver3">
            <input type="hidden" name="lang" value="fr">
            <input type="hidden" name="refreshtime" value="5">
            <input type="hidden" name="BillToName" value="<?php echo $parent->nom ?>">
            <input type="hidden" name="BillToCountry" value="504">
            <input type="hidden" name="email" value="<?php echo $parent->email ?>">
            <input type="hidden" name="tel" value="<?php echo $parent->tel ?>">
            <input type="hidden" name="encoding" value="UTF-8">
            <input type="hidden" name="oid" value="<?php echo $commande->numero  ?>"> <!-- La valeur du paramètre oid doit être unique par transaction -->

        </form>
    </div>
    <?php get_footer();
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
endif;