<?php

use Includes\Modules\Layouts\Layouts;

/**
 * Template Name: Scrolling page
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
                <div class="columns is-multiline">
                    <div class="column is-12 is-3-desktop">
                        <?php include(locate_template('template-parts/sections/sidebar.php')); ?>
                    </div>
                    <div class="column is-12 is-9-desktop">
                        <div class="entry-content content <?= $hasSidebars ? 'has-sidebar' : ''; ?>">
                            <div class="sub-section">
                                <?php the_content(); ?>
                            </div>
                            <?php foreach(getPageChildren($post->post_title) as $child){ ?>
                                <div class="sub-section">
                                    <a name="<?= $child->post_name; ?>" class="pad-anchor"></a>
                                    <h2 class="title"><?= $child->post_title; ?></h2>
                                    <p><?= apply_filters('the_content', $child->post_content); ?></p>
                                    <a class="button is-info is-pulled-right" href="#" >To top</a>
                                </div>
                            <?php } ?>
                        </div>
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