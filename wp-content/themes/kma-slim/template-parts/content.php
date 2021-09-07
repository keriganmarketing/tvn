<?php

use Includes\Modules\Layouts\Layouts;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

function posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'kmaslim' ),
		'' . $time_string . ''
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> by Dr. Rifai</span>'; // WPCS: XSS OK.
}

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
        <?php include(locate_template('template-parts/sections/breadcrumbs.php')); ?>
        <section id="content" class="section support">
            <div class="container" style="padding:0 .5rem;">
                <div class="entry-content content <?= $hasSidebars ? 'has-sidebar' : ''; ?>">
                    <?php if ( 'post' === get_post_type() ) : ?>
                        <div class="entry-meta">
                            <p><em><?php posted_on(); ?></em></p>
                        </div>
                    <?php endif; ?>
                    <?php
                    the_content( sprintf(
                    /* translators: %s: Name of current post. */
                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'kmaslim' ), array( 'span' => array( 'class' => array() ) ) ),
                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                    ) );
                    ?>
                </div>
            </div>
        </section>
    </article>

</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php')); ?>