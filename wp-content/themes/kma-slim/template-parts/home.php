<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section home-header">
            <div class="sticky-header-pad"></div>
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-6 video-holder is-first-desktop">
                        <div class="home-video-container">
                            <img src="<?= get_template_directory_uri() . '/img/video.png'; ?>" >
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
                <div class="columns is-8 is-multiline is-centered">
                <?php //TODO: make this come from the sub-pages of Conditions get_pages($attr)
                for($i = 0; $i < 6; $i++){ ?>
                <div class="column is-4 has-text-centered">
                    <div class="card home-module">
                        <div class="card-content">
                            <h2 class="title">Some Name</h2>
                            <p>Id has tota impedit disputationi, no fugit facilis mel. Facer quaestio prodesset pri te, essent rationibus ea vis. Nisl utroque sed ex. Cu agam ubique mei, ad sit ferri animal. </p>
                        </div>
                        <div class="card-cta">
                            <a href="#" class="button is-small" >Read More</a>
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
                            <p><a class="button is-transparent" href="/about/about-dr-rifai/" >About Dr. Rifai</a></p>
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
<?php include(locate_template('template-parts/sections/bot.php')); ?>
