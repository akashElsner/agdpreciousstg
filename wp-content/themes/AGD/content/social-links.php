<div class="social-icons">

	<?php if( '' != of_get_option( 'link_instagram' ) ) { ?>
		<span><a class="link-instagram" href="<?php echo esc_url( of_get_option( 'link_instagram' ) ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_facebook' ) ) { ?>
		<span><a class="link-facebook" href="<?php echo esc_url( of_get_option( 'link_facebook' ) ); ?>" target="_blank"><i class="fa fa-facebook"></i></a></span>
	<?php } ?>
    
	<?php if( '' != of_get_option( 'link_pinterest' ) ) { ?>
		<span><a class="link-pinterest" href="<?php echo esc_url( of_get_option( 'link_pinterest' ) ); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_twitter' ) ) { ?>
		<span><a class="link-twitter" href="<?php echo esc_url( of_get_option( 'link_twitter' ) ); ?>" target="_blank"><i class="fa fa-twitter"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_linkedin' ) ) { ?>
		<span><a class="link-linkedin" href="<?php echo esc_url( of_get_option( 'link_linkedin' ) ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_googleplus' ) ) { ?>
		<span><a class="link-google" href="<?php echo esc_url( of_get_option( 'link_googleplus' ) ); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_youtube' ) ) { ?>
		<span><a class="link-youtube" href="<?php echo esc_url( of_get_option( 'link_youtube' ) ); ?>" target="_blank"><i class="fa fa-youtube"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_github' ) ) { ?>
		<span><a class="link-github" href="<?php echo esc_url( of_get_option( 'link_github' ) ); ?>" target="_blank"><i class="fa fa-github"></i></a></span>
	<?php } ?>

	<?php if( '' != of_get_option( 'link_email' ) ) { ?>
		<span><a class="link-email" href="<?php echo purpose_get_email_link(); ?>" target="_blank"><i class="fa fa-envelope"></i></a></span>
	<?php } ?>
	
</div>