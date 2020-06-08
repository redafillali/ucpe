<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) :

?>
    <?php

    $orgClientId  =   "600001730";
    $orgAmount = "10.25";
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
<center>

    <form method="post" action="/cmi/SendData.php">
        <table>
            <tr>

                <td align="center" colspan="2"><input type="submit"
                                                      value="Complete Payment" /></td>
            </tr>

        </table>

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
        <input type="hidden" name="oid" value="<?php echo str_pad($commande->numero, 6, '0', STR_PAD_LEFT);  ?>"> <!-- La valeur du paramètre oid doit être unique par transaction -->

    </form>

</center>
<?php
else:
    wp_redirect(get_bloginfo('url') . '/connexion/');
endif;
