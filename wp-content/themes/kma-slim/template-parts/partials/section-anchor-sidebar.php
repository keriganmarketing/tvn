<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/19/2017
 * Time: 12:58 PM
 */
?>
<nav class="panel">
    <p class="panel-heading" style="border-radius:0;">
        On this page:
    </p>
    <?php foreach(getPageChildren($post->post_title) as $child){ ?>
    <a class="panel-block" href="#<?= $child->post_name; ?>" ><?= $child->post_title; ?></a>
    <?php } ?>
</nav>