<?php

use Includes\Modules\Layouts\Layouts;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = get_the_excerpt();

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
    <div class="sticky-header-pad support"></div>
        <section class="support-header">
            <div class="container">
                <h1 class="title"><?php echo $headline; ?></h1>
                <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
            </div>
        </section>
        <?php include(locate_template('template-parts/sections/breadcrumbs.php')); ?>
        <section id="content" class="section support">
            <div class="container" style="padding:0 .5rem;">
                <div class="columns is-multiline">
                    <div class="column is-12 is-8-desktop">
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
                    <div class="column is-12 is-4-desktop" style="padding: 0 1.5rem;">
                    <?php
                        $news = get_posts(array(
                            'posts_per_page' => $a['limit'],
                            'exclude'        => $post->ID,
                            'offset'         => 0,
                            'order'          => 'DESC',
                            'orderby'        => 'date',
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                        ));

                        if($news){
                            echo '<h2 class="title" style="margin:1rem 0;">More Articles</h2>';

                            foreach ($news as $item) {
                                echo '<div id="post' . $item->ID . '" class="recent-news-row" style="margin: .5rem 0" >';

                                echo '<p style="font-size:16px; margin-bottom: 5px;"><em style="font-size:16px;">'.date( 'F j, Y', strtotime($item->post_date)).'</em><br> 
                                    <a href="'.get_the_permalink($item->ID).'"><strong>'.get_the_title($item->ID).':</strong>
                                    '.get_the_excerpt($item->ID).'</a></p><hr>';

                                echo '</div>';
                            }
                        ?>
                        <ul id="more-news" class="sidebarlinks">
                        <li class="sidebar-link"><a class="button is-primary" href="/news-events/news/" title="All News">More Articles</a></li>
                        </ul>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </section>
    </article>

</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php')); ?>