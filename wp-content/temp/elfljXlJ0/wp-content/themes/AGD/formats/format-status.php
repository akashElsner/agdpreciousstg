<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<i class="format-icon fa fa-comment"></i>

	<!-- BEGIN .article -->
	<div class="article">
	
		<?php the_excerpt(); ?>
	
	<!-- END .article -->
	</div>

<!-- END .post class -->
</div>
