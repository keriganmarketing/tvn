<?php

use Includes\Modules\Layouts\Layouts;

/**
 * Template Name: Index page
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
get_header();
while (have_posts()) : the_post();

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

$layouts = new Layouts();
$hasSidebars = $layouts->hasSidebars($post);

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
        <?php include(locate_template('template-parts/sections/breadcrumbs.php')); ?>
        <section id="content" class="section support">
            <div class="container">
                <div class="entry-content content <?= $hasSidebars ? 'has-sidebar' : ''; ?>">
                    <?php the_content(); ?>
                    <div class="columns is-8 is-multiline is-centered">
                        <?php foreach(getPageChildren($post->post_title) as $child){ ?>
                            <div class="column is-4 has-text-centered">
                                <div class="card home-module">
                                    <div class="card-content">
                                        <h2 class="title"><?= $child->post_title; ?></h2>
                                        <p><?= $child->page_information_preview_text; ?></p>
                                    </div>
                                    <div class="card-cta">
                                        <a href="<?= get_permalink($child->ID); ?>" class="button is-small" >Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </article>

</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php'));
endwhile;
get_footer();
?>