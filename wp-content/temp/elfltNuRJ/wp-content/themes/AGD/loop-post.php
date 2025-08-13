
<?php if(in_category('blog')){ ?>
	<div class="blog_wrap">
<?php } ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php if (isset($_POST['featurevid'])){ $custom = get_post_custom($post->ID); $featurevid = $custom['featurevid'][0]; } ?>


	
<?php if ( get_post_meta($post->ID, 'featurevid', true) ) { ?>
	<div class="feature-vid"><?php echo get_post_meta($post->ID, 'featurevid', true); ?></div>
<?php }elseif ( has_post_thumbnail() ) { ?>
	<div class="feature-img"><?php the_post_thumbnail('purpose-featured-large'); ?></div>
<?php } ?>

<!-- BEGIN .article -->
<div class="article">


	<?php the_content(); ?>

<!-- END .article -->
</div>

<?php wp_link_pages(array(
	'before' => '<p class="page-links"><span class="link-label">' . __('Pages:', 'organicthemes') . '</span>',
	'after' => '</p>',
	'link_before' => '<span>',
	'link_after' => '</span>',
	'next_or_number' => 'next_and_number',
	'nextpagelink' => __('Next', 'organicthemes'),
	'previouspagelink' => __('Previous', 'organicthemes'),
	'pagelink' => '%',
	'echo' => 1 )
); ?>

<?php /*?><!-- BEGIN .post-meta -->
<div class="post-meta">

	<p class="align-left"><i class="fa fa-bars"></i> &nbsp;<?php _e("Category:", 'organicthemes'); ?> <?php the_category(', '); ?></p>
	<p class="align-right"><?php $tag_list = get_the_tag_list( __( ", ", 'organicthemes' ) ); if ( ! empty( $tag_list ) ) { ?><i class="fa fa-tags"></i> &nbsp;<?php _e("Tags:", 'organicthemes'); ?> <?php the_tags(''); ?><?php } ?></p>

<!-- END .post-meta -->
</div><?php */?>

<!-- BEGIN .post-navigation -->
<div class="post_links">
	<div class="row">
    	<div class="previous-post"><?php previous_post_link('&larr; %link'); ?></div>
		<div class="next-post"><?php next_post_link('%link &rarr;'); ?></div>
	</div>
</div>

<?php /*?><?php if ( comments_open() || '0' != get_comments_number() ) comments_template(); ?><?php */?>

<div class="clear"></div>

<?php if(is_category('blog')){ ?>
	</div>
<?php } ?>

<?php endwhile; else: ?>

<p><?php _e("Sorry, no posts matched your criteria.", 'organicthemes'); ?></p>

<?php endif; ?>