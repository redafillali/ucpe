<?php
/**
 * index.php
 *
 * @since       2017-05-08
 * @category    Library
 * @package     Pdf
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2002-2017 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-pdf
 *
 * This file is part of tc-lib-pdf software library.
 */
session_start();
// autoloader when using Composer
require 'dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
require '../wp-load.php';
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :
$commande = get_commande($_SESSION['user_id']);
if($commande && $commande->etat != '3') wp_redirect(get_bloginfo('url').'/souscription/recap/');

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$parent = get_client_by_ID($_SESSION['user_id']);
$enfants = get_enfants($_SESSION['user_id']);
$data = get_recap_data($_SESSION['user_id']);
$adhesion = get_adhesion_id($_SESSION['user_id']);
$montant_adh = $data['adhesion'];
$montant_stn = 0;
$commande = get_commande($_SESSION['user_id']);
if($montant_adh) {
    $adh = 'checked';
    if($montant_adh > '100') {
        $soutien = 'checked';
        $montant_stn = $montant_adh-100;
    } else {
        $soutien = 'empty';
    }
} else {
    $adh = 'empty';
}
if($adhesion) {
    if($adhesion->delegue == '1') {
        $delegue1 = 'checked';
        $delegue2 = 'empty';
        $delegue3 = 'empty';
    }
    if($adhesion->delegue == '2') {
        $delegue1 = 'empty';
        $delegue2 = 'checked';
        $delegue3 = 'empty';
    }
    if($adhesion->delegue == '3') {
        $delegue1 = 'empty';
        $delegue2 = 'empty';
        $delegue3 = 'checked';
    }
    $nom_pere = $adhesion->nom_pere;
    $tel_pere = $adhesion->tel_pere;
    $mail_pere = $adhesion->mail_pere;
    $nom_mere = $adhesion->nom_mere;
    $tel_mere = $adhesion->tel_mere;
    $mail_mere = $adhesion->mail_mere;
} else {
    $delegue1 = 'empty';
    $delegue2 = 'empty';
    $delegue3 = 'empty';
    $nom_pere = '';
    $tel_pere = '';
    $mail_pere = '';
    $nom_mere = '';
    $tel_mere = '';
    $mail_mere = '';
    $soutien = 'empty';
}
$body = "<html><head>
<title>Bulletin</title>
<style>
body, html {
padding:0;
margin: 0;
}
table {
width: 100%;
font-size: 13px;
}
</style>
</head>
<body>";
$parent_mail = $parent->email;
foreach ($enfants as $enfant):
    $ecole = get_data_by_id('ecole', $enfant->ecole_id);
    if($enfant->type_assur == '1') {
        $assure1 = 'X';
        $assure2 = '';
    } elseif($enfant->type_assur == '2') {
        $assure1 = '';
        $assure2 = 'X';
    }
$body .= "<table style='width: 100%;' border='1' cellpadding='0' cellspacing='0'>
    <tr>
        <td style='width: 10%; border-right: 2px dashed'></td>
        <td style='padding: 15px'>
        <table style='width: 1029px; border: 2px solid'>
        <tr style='width: 30%'>
        <td align='center' valign='middle'>
        <img src='pdf/logo-1.png' alt=''><br/>
        </td>
        <td align='center' style='width: 45%'>
        <img src='pdf/logo-2.png' alt=''><br />
        </td>
        <td align='center'  valign='middle'  style='width: 25%'>
        <img src='pdf/logo-3.png'>
        </td>
</tr>
        <tr valign='middle' style='width: 30%'>
        <td align='center'>
        <b>ANNÉE SCOLAIRE  2020 / 2021</b>
        </td>
        <td align='center' style='width: 45%'>
        <b>BULLETIN D’ADHÉSION N°: $commande->numero</b>
        </td>
        <td align='center' style='width: 25%'></td>
</tr>
</table>
<table style='width: 980px; border: 2px solid; margin-top: -1px' border='2' cellspacing='0' cellpadding='0'>
<tr style='border: 2px solid;'>
<td align='center' style='border: 2px solid; width: 490px; padding: 5px'><b>ASSURANCE SCOLAIRE</b></td>
<td align='center' style='border: 2px solid; width: 490px padding: 5px'><b>ADHÉSION FAMILIALE À L’UCPE</b></td>
</tr>
<tr>
<td style='border: 2px solid; padding-bottom: 45px; padding-top: 25px'>
<table>
<tr>
<td style='padding-left: 12px'>ÉTABLISSEMENT : <b>$ecole</td>
<td style='padding-left: 12px'>CLASSE : <b>$enfant->niveau</b></td>
</tr>
</table>
<div style='padding-left: 15px'>
<p>NOM ET PRÉNOM  DE L’ÉLÈVE : <b>$enfant->nom</b> <b>$enfant->prenom1 $enfant->prenom2</b></p>
<p>DATE DE NAISSANCE : <b>$enfant->dateNaissance</b></p>
<p>EMAIL DES PARENTS : <b>$parent_mail</b></p>
</div>
<table >
<tr>
<td style='padding-left: 12px'>TÉL PÈRE: <b>$parent->tel</b></td>
<td style='padding-left: 12px'>TÉL MÈRE: <b>$parent->tel_mere</b></td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' border='0' style='margin-top: 25px'>
<tr>
<td style='width: 50%' valign='top'>
<table style='width: 99%; margin-top:55px;' border='2' cellpadding='0' cellspacing='0'>
<tr>
<td align='center' style='width: 90%'>GLOBALE</td>
<td align='center'>$assure1</td>
</tr>
<tr>
<td align='center' style='width: 90%'>GLOBALE PLUS</td>
<td align='center'>$assure2</td>
</tr>
</table>
</td>
<td style='width: 50%; padding: 0'><table style='width: 100%; margin-right:-1px' border='2' cellpadding='0' cellspacing='0'>
<tr>
<td align='center'><img src='pdf/assur.png' style='max-width: 190px'></td>
</tr>
<tr>
<td align='center'style='padding: 5px'><img src='pdf/detail-assure.png' style='max-width: 210px'></td>
</tr>
</table></td>
</tr>
</table>
<div style='padding: 3px; margin-top: 15px'>
<img src='pdf/date-sgnature.jpg' style='width: 490px'>
</div>
</td>
<td style='border: 2px solid;' valign='top'>
<table style='font-size: 13px' >
<tr>
<td><img src='pdf/$adh.jpg' style='vertical-align: bottom' /> ADHÉSION  ANNUELLE : 100,00</td>
<td>
<span style='float: right'><img src='pdf/$soutien.jpg' style='vertical-align: bottom' /> ADHÉSION DE SOUTIEN : $montant_stn,00</span></td>
</tr>
</table>
<table border='2' style='border-color: black; margin-left:-2px; margin-bottom: 25px; margin-top: 10px; margin-right: -2px' cellspacing='0' cellpadding='0'>
<tr style='font-weight: bold'>
<td align='center' width='150' style='padding: 5px'>Nom & Prénom</td>
<td align='center'>Tél</td>
<td align='center'>Email</td>
</tr>
<tr>
<td height='25' align='center'>$nom_pere</td>
<td align='center'>$tel_pere</td>
<td align='center'>$mail_pere</td>
</tr>
<tr>
<td height='25' align='center'>$nom_mere</td>
<td align='center'>$tel_mere</td>
<td align='center'>$mail_mere</td>
</tr>
</table>

<table border='2' cellspacing='0' cellpadding='0' style='border-color: black; margin-left:-2px; margin-bottom: 25px; margin-top: 10px; margin-right: -2px'>
<tr style='font-weight: bold'>
<td align='center' width='150' style='padding: 5px'>AUTRES ENFANTS</td>
<td align='center' style='padding: 5px'>ÉTABLISSEMENT</td>
<td align='center'>CLASSE</td>
</tr>";
foreach ($enfants as $enfant):
$ecole = get_data_by_id('ecole', $enfant->ecole_id);
$body .= "<tr>
<td align='center' >$enfant->nom $enfant->prenom1 $enfant->prenom2</td>
<td align='center'>$ecole</td>
<td align='center'>$enfant->niveau</td>
</tr>";
endforeach;
$body .= "</table>
<table>
<tr>
<td align='center'><b>Je souhaite</b></td>
<td>
<span><img src='pdf/$delegue1.jpg' style='vertical-align: bottom' /> Participer au Bureau UCPE / Activités UCPE<br />
<span><img src='pdf/$delegue2.jpg' style='vertical-align: bottom' /> Assister au conseil d’école / établissement <br />
<span><img src='pdf/$delegue3.jpg' style='vertical-align: bottom' /> Assister au conseil de classe<br />
</td>
</tr>
</table>
<div style=padding: 5px>
<img src='pdf/date-sign.png' width='490' />
</div>
</td>
</tr>
<tr>
<td colspan='2'>
<div style='padding: 3px'>
<img src='pdf/footer.jpg' style='width: 1000px'>
</div>
</td>
</tr>
</table></td>
    </tr>
</table>";
endforeach;
$body .= "<img src='pdf/finale-page.png' style='width: 100%' />

</body></html>";
$dompdf->loadHtml($body);


// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

//echo $body;
// Output the generated PDF to Browser
$dompdf->stream('bulletin.pdf', array("Attachment" => false));
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
    endif;
?>