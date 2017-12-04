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

$layouts = new Layouts();
$hasSidebars = $layouts->hasSidebars($post);

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="sticky-header-pad"></div>
        <section id="content" class="section support">
            <div class="container">
                <h1 class="title"><?php echo $headline; ?></h1>
                <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
                <?php if($hasSidebars){ ?>
                    <div class="columns is-multiline">
                        <div class="column is-12 is-8-desktop">
                <?php } ?>
                            <div class="entry-content <?= $hasSidebars ? 'has-sidebar' : ''; ?>">
                                <?php if ( 'post' === get_post_type() ) : ?>
                                    <div class="entry-meta">
                                        <?php //kmaslim_posted_on(); ?>
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
                <?php if($hasSidebars){ ?>
                        </div>
                        <div class="column is-12 is-4-desktop">
                            <?php include(locate_template('template-parts/sections/sidebar.php')); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </article>

</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>