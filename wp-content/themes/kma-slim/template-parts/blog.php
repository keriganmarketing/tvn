<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : get_the_archive_title());
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : get_the_archive_description());

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="sticky-header-pad support"></div>
        <section class="support-header">
            <div class="container">
                <h1 class="title">From the Desk of Dr. Rifai</h1>
            </div>
        </section>
        <?php include(locate_template('template-parts/sections/breadcrumbs.php')); ?>
        <section id="content" class="section support">
            <div class="container">
                <div class="columns is-multiline">
                <?php

                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/partials/mini-article', get_post_format() );

                    endwhile;

                ?>
                </div>
            </div>
        </section>
    </article><!-- #post-## -->
</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>