<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package foggedclarity
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function foggedclarity_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'colophon',
    'render'    => 'fc_infinite_scroll_render'
	) );
}

function fc_infinite_scroll_render() {
  if ( have_posts() ) : while ( have_posts() ) : the_post();
    get_template_part( 'page-templates/partials/content-entry', get_post_format() );
  endwhile;
  endif;
}

add_action( 'after_setup_theme', 'foggedclarity_jetpack_setup' );
