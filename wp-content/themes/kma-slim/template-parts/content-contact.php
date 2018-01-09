<?php

use Includes\Modules\Layouts\Layouts;
use Includes\Modules\leads\SimpleContact;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

$formSubmitted = (isset($_POST['sec']) ? ($_POST['sec'] == '' ? true : false) : false );
if($formSubmitted){
    $leads = new SimpleContact;
    $leads->handleLead($_POST);
}

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
        <?php include(locate_template('template-parts/sections/breadcrumbs.php')); ?>
        <section id="content" class="section support">
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-12 is-4-desktop">
                        <?php the_content(); ?>
                    </div>
                    <div class="column is-12 is-8-desktop">
                        <div class="entry-content content has-sidebar">

                            <div class="contact-form">
                                <form method="post" >
                                    <input type="text" name="sec" value="" class="sec-form-code" style="position: absolute; left:-10000px; top:-10000px; height:0px; width:0px;" >
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <input type="text" name="first_name" class="input" placeholder="First Name" required>
                                        </div>
                                        <div class="column is-6">
                                            <input type="text" name="last_name" class="input" placeholder="Last Name" required>
                                        </div>
                                        <div class="column is-12">
                                            <input type="email" name="email_address" class="input email" placeholder="Email Address" required>
                                        </div>
                                        <div class="column is-12">
                                            <textarea class="textarea" name="message" placeholder="Type your message here."></textarea>
                                        </div>
                                        <div class="column is-12">
                                            <button class="button is-primary" type="submit">submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </article>

</div>
<?php include(locate_template('template-parts/partials/page-cta.php')); ?>
<?php include(locate_template('template-parts/sections/bot.php')); ?>