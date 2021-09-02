<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 7/13/2017
 * Time: 12:02 PM
 */

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

?>
<div class="column is-4">
    <div class="card">
        <div class="card-image">
            <figure class="image is-16by3">
                <a href="<?php echo get_the_permalink(); ?>" rel="prettyPhoto">
                    <?php the_post_thumbnail('medium'); ?>
                </a>
            </figure>
        </div>
        <div class="card-content">
            <h2 class="title is-3"><?php echo $headline; ?></h2>
            <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
            <?php the_excerpt(); ?>
        </div>
        <div class="card-footer">
            <a class="card-footer-item" href="<?php echo get_the_permalink(); ?>">Read More</a>
        </div>
    </div>
</div>
