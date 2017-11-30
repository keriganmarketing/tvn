<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 7/13/2017
 * Time: 12:02 PM
 */

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

?>
<div class="column is-4">
	<div class="card">
		<div class="card-image">
			<figure class="image is-16by3">
			<img src="http://bulma.io/images/placeholders/640x360.png">
			</figure>
		</div>
		<div class="card-content">
			<h2 class="title"><?php echo $headline; ?></h2>
			<?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
			<?php
			the_content( sprintf(
			/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'kmaevent' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
			?>
		</div>
		<div class="card-footer">
		    <a class="card-footer-item" href="<?php echo get_the_permalink(); ?>">Read More</a>
	      <span class="card-footer-item" >
		      Share:&nbsp;
		      <a class="icon" href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
		      <a class="icon" href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
		      <a class="icon" href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
	      </span>
		</div>
	</div>
</div>
