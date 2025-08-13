<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-medium' ) : false; ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<i class="format-icon fa fa-music"></i> 

	<?php if ( '' != get_the_post_thumbnail()) { ?>
		<div class="feature-img page-banner radius-top" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } ?>>
			<h1 class="headline small img-headline"><?php the_title(); ?></h1>
			<?php the_post_thumbnail( 'purpose-featured-medium' ); ?>
		</div>
	<?php } ?>
	
	<!-- BEGIN .article -->
	<div class="article">
		
		<?php if ( ! has_post_thumbnail() ) { ?>
			<h2 class="headline"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
		<?php } ?>
	
		<?php the_content(__("Read More", 'organicthemes')); ?>
		
	<!-- END .article -->
	</div>

<!-- END .post class -->
</div>