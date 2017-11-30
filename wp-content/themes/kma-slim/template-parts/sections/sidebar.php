<div class="sidebar columns is-multiline">
<?php
$sidebars = $layouts->getSidebars($post);
foreach($sidebars as $sidebar){ ?>
    <div class="column is-6-tablet is-12-desktop">
        <div class="sidebar-section <?= $sidebar; ?>">
        <?php include(locate_template('template-parts/partials/' . $sidebar . '.php')); ?>
        </div>
    </div>
<?php } ?>
</div>
