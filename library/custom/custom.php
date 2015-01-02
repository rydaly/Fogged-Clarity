<?php

/**
 * Typekit
 */

function fc_typekit() {
  // TODO :: add production typekit link here 
  wp_enqueue_script( 'fc_typekit', '//use.typekit.net/yzy0zix.js'); 
}
add_action( 'wp_enqueue_scripts', 'fc_typekit' );

function fc_typekit_inline() {
  if ( wp_script_is( 'fc_typekit', 'done' ) ) { ?>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php }
}
add_action( 'wp_head', 'fc_typekit_inline' );


/**
 * Dequeue some things
 */
function fc_dequeue_styles() {
  wp_dequeue_style('mediaelement');
  wp_dequeue_style('wp-mediaelement');
  wp_deregister_style('wp-mediaelement');
  wp_deregister_style('mediaelement');
}
add_action( 'wp_enqueue_scripts', 'fc_dequeue_styles', 1 );



/**
 * Enqueue FC styles and scripts
 */
function fc_enqueue_scripts() {
  wp_dequeue_style( 'screen' );
  wp_deregister_style( 'screen' );
  // wp_enqueue_style( 'fc_styles', $src, $deps, $ver, $media );
  wp_register_script( 'fc_scripts', get_template_directory_uri() . '/dist/scripts/production.min.js', array(), '', false );
  wp_enqueue_script( 'fc_scripts' );
  // wp_enqueue_style( 'fc-styles', get_stylesheet_uri(), array(), '1.0' );
  // wp_enqueue_script( 'jquery' );
  // wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.min.js', array(), '1.0', true );
  // if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'fc_enqueue_scripts', 20 );



/*
  Plugin Name: HTML in Category Descriptions
  Description: Allows you to add HTML code in category descriptions
  Author: Arno Esterhuizen
*/
$filters = array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description');
foreach ( $filters as $filter ) {
  remove_filter($filter, 'wp_filter_kses');
}



/**
 * category color top border for articles
 */
function fc_header_before() {
  if(is_single()) {
    $output = '';
    
    global $post;
    $cat = get_the_category( $post->id )[0]->slug;

    $output = '<div class="fc-cat-color ' . $cat . '"></div>';
    echo $output;
  }
}
add_action( 'tha_header_before', 'fc_header_before' );



/**
 * FC header - Top bar, hero, carousel and nav
 */
function fc_header_top() { ?>
  <header class="top-bar">
    <div class="top-bar-wrapper">
      <a class="bar-logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
        <img src="<?php get_template_directory_uri(); ?>/wp-content/themes/some-like-it-neat/images/fc_logo_sm.png" width="55" height="55" alt="fc logo">
        <p class="logo-copy"><?php bloginfo( 'name' ); ?></p>
      </a>
      <!-- <i class="fa fa-list-ul"></i> -->
      <?php
        $output  = '<div class="bar-meta">';
        $output .= '<ul class="social-links"><li><a class="fb-link" href="https://www.facebook.com/FoggedClarity" target="_blank" title="Fogged Clarity on Facebook" alt="Fogged Clarity on Facebook"><i class="fa fa-facebook-square"></i></a></li><li><a class="twitter-link" href="https://twitter.com/foggedclarity" target="_blank" title="Fogged Clarity on Twitter" alt="Fogged Clarity on Twitter"><i class="fa fa-twitter-square"></i></a></li></ul>';
        $output .= '</div>';
        echo $output;
      ?>
    </div>
  </header>

  <?php
    if( is_front_page() || get_current_type() === "tax" ) { ?>
      <div class="hero hero-main" data-speed="1.6" data-type="scroll">
        <div class="hero-inner">
          <a class="hero-logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><img src="<?php get_template_directory_uri(); ?>/wp-content/themes/some-like-it-neat/images/fc_logo.png" width="156" height="156" alt="fc logo"></a>
          <div class="hero-copy">
            <h1 class="blog-name"><?php bloginfo( 'name' ); ?></h1>
              <hr class="divider">
              <p><?php echo get_term_by( 'slug', $_COOKIE['issuem_issue'], 'issuem_issue' )->name; ?></p>
          </div>
        </div>
      </div>
  <?php
    } else { ?>
      <div class="hero hero-main hero-sm" data-speed="1.6" data-type="scroll">
        <div class="hero-inner">
          <a class="hero-logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><img src="<?php get_template_directory_uri(); ?>/wp-content/themes/some-like-it-neat/images/fc_logo.png" width="156" height="156" alt="fc logo"></a>
        </div>
      </div>
  <?php
    }
  ?>
 
  <div class="js-menu-trigger sliding-menu-button" title="Main Menu">
    <!-- Look ma, no Snap! -->
    <svg version="1.1" id="nav-menu-btn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100%" height="100%" viewBox="0 0 60 64" xml:space="preserve">
      <g class="menu-group">
        <g class="top-group"><path class="top" fill="none" stroke="#555" stroke-width="2" stroke-linejoin="bevel" d="M0,19h60"/></g>
        <g class="mid-group"><path class="mid" fill="none" stroke="#555" stroke-width="2" stroke-linejoin="bevel" d="M0,32H109" stroke-dasharray="61 61" stroke-dashoffset="0"/></g>
        <g class="bot-group"><path class="bot" fill="none" stroke="#555" stroke-width="2" stroke-linejoin="bevel" d="M0,45h60"/></g>
      </g>
    </svg>
  </div>

  <!-- primary navigation -->
  <nav class="js-menu sliding-menu-content">
    
    <!-- Menu items -->
    <?php
      // get_search_form();
      $nav_menu = wp_nav_menu(
        array(
          'items_wrap' => '<ul class="%2$s">%3$s</ul>',
          'theme_location' => 'primary-navigation',
          'fallback_cb' => '__return_false'
        )
      );
    ?>
    
    <!-- background -->
    <div class="js-menu-screen menu-screen"></div>

  </nav>


<?php }

add_action( 'tha_header_top', 'fc_header_top' );

function fc_header_bottom() {
  if( is_front_page() || get_current_type() === "tax" ) { ?>
    <div class="hero hero-carousel">
      <div class="feature-rotator">
        <?php echo do_shortcode('[issuem_featured_rotator]'); ?>
      </div>
    </div>
  <?php } else {
    global $post;
    if( !is_front_page() && is_page() ) {
      $output = '<h1 class="page-title">' . get_the_title( $post->id ) . '</h1>';
      $output .= '<div class="entry-meta">';
      $output .= '<hr class="divider">'; // TODO :: add sharing here
      $output .= '</div>';
      echo $output;
    }
  }
}

add_action( 'tha_header_bottom', 'fc_header_bottom' );



/**
 * Add search form to menu
 *
 */
function add_search_box_to_menu( $items, $args ) {
  if( $args->theme_location == 'primary-navigation' )
      return $items.get_search_form();
  return $items;
}
add_filter('wp_nav_menu_items','add_search_box_to_menu', 10, 2);



/**
 * Add popular posts box above footer
 *
 */

function fc_footer_before() {
  if( is_page() ) {
    // $output  = '<div class="popular-module">';
    // $output .= '<h2>Popular on Fogged Clarity</h2>';
    // $output .= '<div class="buttons"><div class="button most-shared">Most Shared</div>';
    // $output .= '<div class="button most-read">Most Read</div></div>';
    // $output .= '<ul class="most-shared-list"><li>Item 1</li><li>Item 2</li><li>Item 3</li><li>Item 4</li><li>Item 5</li></ul>';
    // $output .= '<ul class="most-read-list"><li>Item 1</li><li>Item 2</li><li>Item 3</li><li>Item 4</li><li>Item 5</li></ul>';
    // $output .= '</div>';
    // echo $output;
    if( function_exists( 'tptn_show_pop_posts' ) ) tptn_show_pop_posts();
  }
}
add_action( 'tha_footer_before', 'fc_footer_before');



/**
 * Embed disqus comments
 *
 */

function disqus_embed($disqus_shortname) {
  global $post;
  wp_enqueue_script('disqus_embed','http://'.$disqus_shortname.'.disqus.com/embed.js');
  echo '<div id="disqus_thread"></div>
  <script type="text/javascript">
    var disqus_shortname = "'.$disqus_shortname.'";
    var disqus_title = "'.$post->post_title.'";
    var disqus_url = "'.get_permalink($post->ID).'";
    var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
  </script>';
}



/**
 * Remove default Jetpack sharing buttons ( adding to tha_entry_bottom )
 *
 */

function jetpack_remove_share() {
  remove_filter( 'the_content', 'sharing_display', 19 );
  remove_filter( 'the_excerpt', 'sharing_display', 19 );
  if ( class_exists( 'Jetpack_Likes' ) ) {
      remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
  }
}
 
add_action( 'loop_start', 'jetpack_remove_share' );

// get sharedaddy scripts manually
function fc_add_sharedaddy() {
  wp_enqueue_script( 'sharing-js', WP_SHARING_PLUGIN_URL . 'sharing.js', array( ), 3 );
  $sharing_js_options = array(
    'lang'   => get_base_recaptcha_lang_code(),
    'counts' => apply_filters( 'jetpack_sharing_counts', true )
  );
  wp_localize_script( 'sharing-js', 'sharing_js_options', $sharing_js_options);
}

add_action( 'wp_enqueue_scripts', 'fc_add_sharedaddy' );

function jetpack_remove_styles() {
  // wp_deregister_style('AtD_style'); // After the Deadline
  // wp_deregister_style('jetpack-carousel'); // Carousel
  // wp_deregister_style('grunion.css'); // Grunion contact form
  // wp_deregister_style('the-neverending-homepage'); // Infinite Scroll
  // wp_deregister_style('infinity-twentyten'); // Infinite Scroll - Twentyten Theme
  // wp_deregister_style('infinity-twentyeleven'); // Infinite Scroll - Twentyeleven Theme
  // wp_deregister_style('infinity-twentytwelve'); // Infinite Scroll - Twentytwelve Theme
  // wp_deregister_style('noticons'); // Notes
  // wp_deregister_style('post-by-email'); // Post by Email
  // wp_deregister_style('publicize'); // Publicize
  // wp_deregister_style('sharedaddy'); // Sharedaddy
  // wp_deregister_style('sharing'); // Sharedaddy Sharing
  // wp_deregister_style('stats_reports_css'); // Stats
  // wp_deregister_style('jetpack-widgets'); // Widgets
}
add_action('wp_print_styles', 'jetpack_remove_styles');



/**
 * helper functions for dev
 */



function get_current_type() {
  global $wp_query;
  $current_type = 'notfound';

  if ( $wp_query->is_page ) {
      $current_type = is_front_page() ? 'front' : 'page';
  } elseif ( $wp_query->is_home ) {
      $current_type = 'home';
  } elseif ( $wp_query->is_single ) {
      $current_type = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
  } elseif ( $wp_query->is_category ) {
      $current_type = 'category';
  } elseif ( $wp_query->is_tag ) {
      $current_type = 'tag';
  } elseif ( $wp_query->is_tax ) {
      $current_type = 'tax';
  } elseif ( $wp_query->is_archive ) {
    if ( $wp_query->is_day ) {
      $current_type = 'day';
    } elseif ( $wp_query->is_month ) {
      $current_type = 'month';
    } elseif ( $wp_query->is_year ) {
      $current_type = 'year';
    } elseif ( $wp_query->is_author ) {
      $current_type = 'author';
    } else {
      $current_type = 'archive';
    }
  } elseif ( $wp_query->is_search ) {
      $current_type = 'search';
  } elseif ( $wp_query->is_404 ) {
      $current_type = 'notfound';
  }
  return $current_type;
}

// trims the title to prevent really long titles from ruining layout
// function fc_title_trim($before = '', $after = '', $length = false) { 

//   $title = get_the_title();

//   // check for valid input
//   if ( $length && is_numeric($length) ) {
//     // compare title length to input
//     if ( strlen($title) > $length ) {
//       // perform trim and return
//       $pos = strpos( $title, ' ', $length );
//       $title = substr( $title, 0, $pos );
//       $title = apply_filters('fc_title_trim', $before . $title . $after, $before, $after);
//       return $title;
//     } else {
//       return $title;
//     }
//   }
// }



/**
 * register custom footer widgets
 */

function fc_widgets_init() {
  /**
  * Creates a sidebar
  * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
  */
  $args_left = array(
    'name'          => __( 'Footer Left', 'fogged_clarity' ),
    'id'            => 'fc_footer_left',
    'before_widget' => '<div class="footer-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widgettitle">',
    'after_title'   => '</h4>'
  );

  $args_center = array(
    'name'          => __( 'Footer Center', 'fogged_clarity' ),
    'id'            => 'fc_footer_center',
    'before_widget' => '<div class="footer-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widgettitle">',
    'after_title'   => '</h4>'
  );

  $args_right = array(
    'name'          => __( 'Footer Right', 'fogged_clarity' ),
    'id'            => 'fc_footer_right',
    'before_widget' => '<div class="footer-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widgettitle">',
    'after_title'   => '</h4>'
  );

  register_sidebar( $args_left );
  register_sidebar( $args_center );
  register_sidebar( $args_right );
}
add_action( 'widgets_init', 'fc_widgets_init' );



/**
 * plugins to make WordPress client friendly
 */

// custom login page
function custom_login_css() {
  echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'stylesheet_directory' ) . '/library/assets/css/custom-login.min.css" />';
}
add_action('login_head', 'custom_login_css');

// custom login logo link
function change_wp_login_url() 
{
  return get_home_url();
}
add_filter('login_headerurl', 'change_wp_login_url');
 
// custom logo alt text
function change_wp_login_title() 
{
  return get_option('blogname');
}
add_filter('login_headertitle', 'change_wp_login_title');

// custom dashboard footer
function remove_footer_admin () 
{
  echo '<span id="footer-thankyou">Designed & Developed by <a href="http://rydaly.com" target="_blank">Ryan Daly</a> | Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a></span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

// hide some annoying shit
function fc_hide_admin_nags() {
  echo '<style>
  #issuem_rss_item, .cf7com-links, #welcome-panel {
    display: none;
    visibility: hidden;
  }
  </style>';
}
add_action('admin_head', 'fc_hide_admin_nags');