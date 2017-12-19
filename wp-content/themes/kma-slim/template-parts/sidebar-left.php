<?php

use Includes\Modules\Layouts\Layouts;

/**
 * Template Name: Sidebar on left
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
                    <div class="column is-12 is-8-desktop is-second-desktop">
                        <div class="entry-content content <?= $hasSidebars ? 'has-sidebar' : ''; ?>">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <div class="column is-12 is-4-desktop is-first-desktop">
                        <?php include(locate_template('template-parts/sections/sidebar.php')); ?>
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