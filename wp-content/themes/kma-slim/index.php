<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.3
 */
get_header();
if (have_posts()) :
    if (is_home()) { //multipart template, archive or whatever

        get_template_part('template-parts/blog', get_post_format());
    } else {
        while (have_posts()) :
            the_post();

            if (is_front_page()) {
                get_template_part('template-parts/home');
            } elseif(is_single()) {
                get_template_part('template-parts/content', get_post_type());
            }else{
                get_template_part('template-parts/content', $post->post_name);
            }
        endwhile;

        the_posts_navigation();
    }
else :
    if (is_tax()) {
        get_template_part('template-parts/taxonomy', get_query_var('taxonomy'));
    } else {
        get_template_part('template-parts/content', 'none');
    }
endif;

get_footer();

