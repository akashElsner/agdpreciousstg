<?php $wp_query = new WP_Query(array('posts_per_page'=>10, 'category_name'=>'blog', 'paged'=>$paged)); ?>
<?php if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); ?>
<?php global $more; $more = 0; ?>

	<!-- BEGIN .blog-holder -->
	<div class="blog_holder">
		<?php if(has_post_thumbnail()){ ?>
		<div class="blog_col_1">
        	<?php the_post_thumbnail( 'medium' ); ?>
        </div>
        <div class="blog_col_2">
        	<h5><?php the_title(); ?></h5>
        	<p><?php the_excerpt(); ?></p>
        	<a class="arrowButton" href="<?php the_permalink(); ?>">Read more <span class="longArrow"></span></a>
        </div>
        <?php }else{ ?>
        <div class="blog_col_full">
        	<h5><?php the_title(); ?></h5>
        	<p><?php the_excerpt(); ?></p>
        	<a class="arrowButton" href="<?php the_permalink(); ?>">Read more <span class="longArrow"></span></a>
        </div>
        <?php }?>
	
	<!-- END .blog-holder -->
	</div>

<?php endwhile; ?>

	<?php if($wp_query->max_num_pages > 1) { ?>
		<!-- BEGIN .pagination -->
		<div class="pagination">
			<?php echo purpose_get_pagination_links(); ?>
		<!-- END .pagination -->
		</div>
	<?php } ?>

<?php else : ?>

	<div class="error-404">
		<h1 class="headline"><?php _e("No Posts Found", 'organicthemes'); ?></h1>
		<p><?php _e("We're sorry, but no posts have been found. Create a post to be added to this section, and configure your theme options.", 'organicthemes'); ?></p>
	</div>

<?php endif; ?>