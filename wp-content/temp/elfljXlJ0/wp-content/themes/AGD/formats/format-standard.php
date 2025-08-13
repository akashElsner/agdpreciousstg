<?php if (isset($_POST['featurevid'])){ $custom = get_post_custom($post->ID); $featurevid = $custom['featurevid'][0]; } ?>
<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-medium' ) : false; ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	
	<?php if ( get_post_meta($post->ID, 'featurevid', true) ) { ?>
		<div class="feature-vid"><?php echo get_post_meta($post->ID, 'featurevid', true); ?></div>
	<?php } else { ?>
		<?php if ( '' != get_the_post_thumbnail()) { ?>
			<div class="feature-img page-banner radius-top" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } ?>>
				<h2 class="headline small img-headline"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
				<?php the_post_thumbnail( 'purpose-featured-medium' ); ?>
			</div>
		<?php } ?>
	<?php } ?>
	
	<!-- BEGIN .article -->
	<div class="article">
	
		<?php if ( ! has_post_thumbnail() ) { ?>
			<h2 class="headline small"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
		<?php } ?>
		<?php the_content(__("Read More", 'organicthemes')); ?>
		
	<!-- END .article -->
	</div>
	
	<?php $tag_list = get_the_tag_list( __( ", ", 'organicthemes' ) ); if ( ! empty( $tag_list ) || has_category() ) { ?>
	
	<!-- BEGIN .post-meta -->
	<div class="post-meta">
	
		<div class="align-left">
			<p><i class="fa fa-comment"></i> &nbsp;<a href="<?php the_permalink(); ?>#comments"><?php comments_number(__("Leave a Comment", 'organicthemes'), __("1 Comment", 'organicthemes'), __('% Comments' , 'organicthemes') ); ?></a></p>
			<p><i class="fa fa-clock-o"></i> &nbsp;<?php _e("Posted on", 'organicthemes'); ?> <?php the_time(__("F j, Y", 'organicthemes')); ?> <?php _e("by", 'organicthemes'); ?> <?php esc_url ( the_author_posts_link() ); ?></p>
		</div>
		
		<div class="align-right text-right">
			<p><i class="fa fa-bars"></i> &nbsp;<?php the_category(', '); ?></p>
			<p><?php $tag_list = get_the_tag_list( __( ", ", 'organicthemes' ) ); if ( ! empty( $tag_list ) ) { ?> &nbsp; &nbsp; <i class="fa fa-tags"></i> &nbsp;<?php the_tags(''); ?><?php } ?></p>
		</div>
	
	<!-- END .post-meta -->
	</div>
	
	<?php } ?>

<!-- END .post class -->
</div>