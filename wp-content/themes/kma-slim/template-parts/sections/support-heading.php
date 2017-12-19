<div class="sticky-header-pad support"></div>
<section class="support-header">
    <div class="container">
        <h1 class="title"><?php echo $headline; ?></h1>
        <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php //kmaslim_posted_on(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>