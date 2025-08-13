<style type="text/css" media="screen">
<?php
	$background_stretch = get_theme_mod('background_stretch');
	$nav_color = get_theme_mod('nav_color');
	$link_color = get_theme_mod('link_color');
	$heading_link_color = get_theme_mod('heading_link_color');
	$link_hover_color = get_theme_mod('link_hover_color');
	$heading_link_hover_color = get_theme_mod('heading_link_hover_color');
?>

body {
	<?php
		if ($background_stretch == '1') {
			echo '-webkit-background-size: cover;';
			echo '-moz-background-size: cover;';
			echo '-o-background-size: cover;';
			echo 'background-size: cover;';
		};
	?>
}

.container .menu ul.children, .container .menu ul.sub-menu {
<?php 
	if ($nav_color) {
	    echo 'background-color: ' .$nav_color. ';';
    }; 
?>
}

.container .menu .nav-arrow {
<?php 
	if ($nav_color) {
	    echo 'border-bottom-color: ' .$nav_color. ';';
    }; 
?>
}

.content a, .content a:link, .content a:visited, #wrap .widget ul.menu li a {
	<?php
		if ($link_color) {
			echo 'color: ' .$link_color. ';';
		};
	?>
}

.content a:hover, .content a:focus, .content a:active,
#wrap .widget ul.menu li a:hover, #wrap .widget ul.menu li ul.sub-menu li a:hover,
#wrap .widget ul.menu .current_page_item a, #wrap .widget ul.menu .current-menu-item a {
	<?php
		if ($link_hover_color) {
			echo 'color: ' .$link_hover_color. ';';
		};
	?>
}

.content h1 a, .content h2 a, .content h3 a, .content h4 a, .content h5 a, .content h6 a,
.content h1 a:link, .content h2 a:link, .content h3 a:link, .content h4 a:link, .content h5 a:link, .content h6 a:link,
.content h1 a:visited, .content h2 a:visited, .content h3 a:visited, .content h4 a:visited, .content h5 a:visited, .content h6 a:visited {
	<?php
		if ($heading_link_color) {
			echo 'color: ' .$heading_link_color. ';';
		};
	?>
}

.content h1 a:hover, .content h2 a:hover, .content h3 a:hover, .content h4 a:hover, .content h5 a:hover, .content h6 a:hover,
.content h1 a:focus, .content h2 a:focus, .content h3 a:focus, .content h4 a:focus, .content h5 a:focus, .content h6 a:focus,
.content h1 a:active, .content h2 a:active, .content h3 a:active, .content h4 a:active, .content h5 a:active, .content h6 a:active,
.slideshow .headline a:hover, .slideshow .headline a:focus, .slideshow .headline a:active {
	<?php
		if ($heading_link_hover_color) {
			echo 'color: ' .$heading_link_hover_color. ';';
		};
	?>
}
</style>