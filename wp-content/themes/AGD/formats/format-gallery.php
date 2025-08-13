<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php get_template_part( 'content/slider', 'gallery' ); ?>
	
	<!-- BEGIN .article -->
	<div class="article">
	
		<h2 class="headline small"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
		<?php the_content(__("Read More", 'organicthemes')); ?>
		
	<!-- END .article -->
	</div>

<!-- END .post class -->
</div>