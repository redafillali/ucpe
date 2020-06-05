<?php $commandes = get_all_commandes(); ?>
<h2>Liste des commandes</h2>
<table class="table">
    <thead>
    <tr>
        <th scope="col">N°</th>
        <th scope="col">Nom</th>
        <th scope="col">Montant</th>
        <th scope="col">Etat</th>
        <th scope="col">Transaction</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($commandes as $commande):
        $parent = get_client_by_ID($commande->parent_id);
        ?>
    <tr>
        <td><?php echo $commande->numero ?></td>
        <td><?php echo $parent->nom ?></td>
        <td><?php echo $commande->montant ?></td>
        <td><?php echo $commande->etat ?></td>
        <td><?php echo $commande->transaction ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>