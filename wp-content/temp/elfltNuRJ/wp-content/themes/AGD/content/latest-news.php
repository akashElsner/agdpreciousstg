<?php $latestNews = new WP_Query( array('post_type'=>'post', 'showposts' => '3') ); ?>
<div class="latest_news_table">
<?php if ($latestNews->have_posts()) : while ($latestNews->have_posts()) : $latestNews->the_post(); ?>
	<div class="latest_news_cell">
    	<div class="latest_news_holder">
        	<h6 class="latest_news_title"><a href="<?php the_permalink(); ?>"><strong><?php the_title(); ?></strong></a></h6>
            <div class="latest_news_desc">
				<?php the_excerpt(); ?>
            	<h6><a href="<?php the_permalink(); ?>"><strong>Read more</strong></a></h6>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php endif; ?>