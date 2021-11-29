<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead  = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

$news = get_posts(array(
    'posts_per_page' => 3,
    'exclude'        => $post->ID,
    'offset'         => 0,
    'order'          => 'DESC',
    'orderby'        => 'date',
    'post_type'      => 'post',
    'post_status'    => 'publish',
));

$recent = $news[0];

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section home-header">
            <div class="sticky-header-pad"></div>
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-6 video-holder is-first-tablet">
                        <div class="home-video-container image is-16by9">
                            <iframe title="tvn youtube video" width="483" height="243" src="https://www.youtube.com/embed/ItM4_3fsknI?rel=0&amp;showinfo=0" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="column is-6 home-headline has-text-centered has-text-left-tablet">
                        <div class="hero-column-wrapper">
                            <div class="headline">
                                <h1 class="title">
                                    Kidney disease<br>
                                    explained...<br>
                                    <em>simplified.</em>
                                </h1>
                            </div>
                            <div class="new-article" >
                                <p>
                                    <a href="<?php echo get_the_permalink($recent->ID); ?>"><?php echo get_the_title($recent->ID); ?>...<br>
                                    <span>read the full article</span></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section home-modules">
            <div class="container pad">
                <div class="columns is-8 is-multiline is-centered support-modules">
                <?php foreach(getPageChildren('Specialties') as $child){ ?>
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

        <div class="section doctor-bio">
            <div class="container pad">
                <div class="columns is-8 is-multiline is-centered">
                    <div class="column is-7" >
                        <div class="doctor-bio-container">
                            <h2>The Virtual Nephrologist is your gateway to optimal health.</h2>
                            <p>Think about a routine Doctor's office visit. You get there and wait in a large waiting room. It is a WAITING room. By the time you see the physician after the wait for a few hours, you realize there are a few more questions to ask and you are still thirsty for answers.</p>
                            <div class="columns">
                                <div class="column is-narrow">
                                    <a class="button is-transparent" href="/about-us/our-services/" >About Dr. Rifai</a>
                                </div>
                                <div class="column is-narrow">
                                    <a class="button is-transparent" href="/shop/tvn-amazon-store/" >Health Products</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-5 doctor-photo-container">
                        <div class="doctor-photo-container">
                            <img alt="dr.rifai" src="<?= get_template_directory_uri() . '/img/Dr-34.jpg'; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section news-section">
            <div class="container pad">
                <h2 class="title" >From the Desk of Dr. Rifai</h2>
                <div class="columns">
                <?php foreach ($news as $item) { ?>
                    <div class="column is-4">
                        <div class="card is-fullheight has-shadow">
                            <div class="card-image">
                                <figure class="image is-16by3">
                                    <a href="<?php echo get_the_permalink($item->ID); ?>" rel="prettyPhoto">
                                        <?php echo get_the_post_thumbnail($item->ID, 'medium'); ?>
                                    </a>
                                </figure>
                            </div>
                            <div class="card-content">
                                <em><?php echo date( 'F j, Y', strtotime($item->post_date)); ?></em>
                                <h3><strong class="has-text-primary has-medium-font-size"><?php echo $item->post_title; ?></strong></h3>
                                <?php echo get_the_excerpt($item->ID); ?>
                            </div>
                            <div class="card-footer">
                                <a class="card-footer-item" href="<?php echo get_the_permalink($item->ID); ?>">Read Article</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
                <div class="has-text-centered cta-section">
                    <a class="button is-outlined is-white is-uppercase cta-button" href="/blog/" >More Helpful Articles</a>
                </div>
            </div>
        </div>
    </article>
</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php')); ?>
