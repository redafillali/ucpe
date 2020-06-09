<?php
function welcome($nom, $email, $pwd) {
    $url = get_bloginfo('url')."/connexion/";
    $objet = "Création du compte UCPE";
    $message = "<p>Bonjour $nom,<p>
    <p>Félicitations! Votre profil a été créé avec succès.<br>
    <ul>
    <li>Votre identifiant : $email</li>
    <li>Votre mot de passe : $pwd</li>
    </ul><br />
    Vous pouvez accéder à votre compte pour poursuivre les étapes de votre souscription en cliquant sur le lien suivant :<b /><a href='$url'>$url</a>
    </p>
    <p>Cordialement,</p>
    <p><b>Association UCPE</b></p>";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $send_mail = wp_mail($email, $objet, $message,$headers);
}

function paiement_bulletin($nom, $email) {
    $url = get_bloginfo('url')."/confirmation/";
    $objet = "Félicitations! Votre souscription a été confirmée!";
    $message = "<p>Bonjour $nom,<p>
    <p>Félicitations! Votre souscription a été confirmée.<br>
    Nous vous invitons à récupérer votre bulletin d’inscription en cliquant sur le lien suivant: <b /><a href='$url'>$url</a>
    </p>
    <p>Cordialement,</p>
    <p><b>Association UCPE</b></p>";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $send_mail = wp_mail($email, $objet, $message,$headers);
}
function relance_paiement($nom, $email) {
    $url = get_bloginfo('url')."/famille/";
    $objet = "Plus qu’une étape avant la finalisation de votre souscription!";
    $message = "<p>Bonjour $nom,<p>
    <p>Plus qu’une étape avant la finalisation de votre souscription!<br>
    Pour continuer, vous pouvez accéder en cliquant sur le lien suivant: <b /><a href='$url'>$url</a>
    </p>
    <p>Cordialement,</p>
    <p><b>Association UCPE</b></p>";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $send_mail = wp_mail($email, $objet, $message,$headers);
}