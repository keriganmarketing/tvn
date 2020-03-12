<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead  = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section home-header">
            <div class="sticky-header-pad"></div>
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-6 video-holder is-first-desktop">
                        <div class="home-video-container image is-16by9">
                            <iframe title="tvn youtube video" width="483" height="243" src="https://www.youtube.com/embed/ItM4_3fsknI?rel=0&amp;showinfo=0" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="column is-6 home-headline has-text-centered has-text-left-desktop">
                        <div class="headline">
                            <h1 class="title">
                                Kidney disease<br>
                                explained...<br>
                                <em>simplified.</em>
                            </h1>
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
                            <p><a class="button is-transparent" href="/about-us/our-services/" >About Dr. Rifai</a></p>
                        </div>
                    </div>
                    <div class="column is-5 doctor-photo-container">
                        <div class="doctor-photo-container">
                            <img src="<?= get_template_directory_uri() . '/img/dr-rifai@2x.png'; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php')); ?>
