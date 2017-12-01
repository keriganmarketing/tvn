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
                        <img src="<?= get_template_directory_uri() . '/img/video.png'; ?>" >
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
            <div class="container">
                <div class="columns is-8 is-multiline is-centered">
                <?php for($i = 0; $i < 6; $i++){ ?>
                <div class="column is-4 has-text-centered">
                    <div class="card home-module">
                        <div class="card-content">
                            <h2 class="title">Some Name</h2>
                            <p>Id has tota impedit disputationi, no fugit facilis mel. Facer quaestio prodesset pri te, essent rationibus ea vis. Nisl utroque sed ex. Cu agam ubique mei, ad sit ferri animal. Eu elitr utinam discere duo. Eam sadipscing instructior te, aliquid corpora usu id, qui ne graeco mnesarchum instructior.</p>
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

    </article>
</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>
