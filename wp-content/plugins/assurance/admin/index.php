<?php
get_header();
if (current_user_can('edit_posts')): ?>
<div class="col-md-3 admin-sidebar">
    <?php include ('parts/sidebar.php'); ?>
</div>
<div class="col-md-9">
    <?php include ('parts/liste-commandes.php'); ?>
</div>
<?php else: ?>
    <div class="col-sm-12">
        <h1 style="text-align: center"> Vous n'avez pas les droit suffisants pour acceder à cette page, <a href="<?php bloginfo('url') ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>&reauth=1">Connectez-vous</a></h1>
    </div>
<?php endif; ?>
<?php get_footer(); ?>
