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
		'footer'    => false,
    'render'    => 'fc_infinite_scroll_render'
	) );
}

function fc_infinite_scroll_render() {
  // show infinite scroll on category archives, not on issue archives
  if ( is_category() ) {
    if ( have_posts() ) : while ( have_posts() ) : the_post();
      get_template_part( 'page-templates/partials/content-entry', get_post_format() );
    endwhile;
    endif;
  }
}

add_action( 'after_setup_theme', 'foggedclarity_jetpack_setup' );

/*
 * Disallow commenting on images contained in jetpack galleries
 */
function tweakjp_rm_comments_att( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'tweakjp_rm_comments_att', 10 , 2 );
