<?php
/**
 * HEAD
 */

function fc_wp_head() { ?>
  <!-- Typekit -->
  <script>
    (function(d) {
      var config = {
        kitId: 'yzy0zix',
        scriptTimeout: 3000
      },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document);
  </script>

  <!-- favicons -->
  <link rel="apple-touch-icon" sizes="57x57" href="http://foggedclarity.com/icons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="114x114" href="http://foggedclarity.com/icons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="72x72" href="http://foggedclarity.com/icons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="http://foggedclarity.com/icons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="http://foggedclarity.com/icons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="120x120" href="http://foggedclarity.com/icons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="76x76" href="http://foggedclarity.com/icons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="152x152" href="http://foggedclarity.com/icons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="http://foggedclarity.com/icons/apple-touch-icon-180x180.png">
  <link rel="shortcut icon" href="http://foggedclarity.com/icons/favicon.ico">
  <link rel="icon" type="image/png" href="http://foggedclarity.com/icons/favicon-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="http://foggedclarity.com/icons/favicon-160x160.png" sizes="160x160">
  <link rel="icon" type="image/png" href="http://foggedclarity.com/icons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="http://foggedclarity.com/icons/favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="http://foggedclarity.com/icons/favicon-32x32.png" sizes="32x32">
  <meta name="msapplication-TileColor" content="#2b5797">
  <meta name="msapplication-TileImage" content="http://foggedclarity.com/icons/mstile-144x144.png">
  <meta name="msapplication-config" content="http://foggedclarity.com/icons/browserconfig.xml">
<?php }

add_action( 'wp_head', 'fc_wp_head' );



/**
 * Scripts and Styles 
 */
function fc_handle_scripts_and_styles() {
  wp_dequeue_style('mediaelement');
  wp_dequeue_style('wp-mediaelement');
  wp_deregister_style('wp-mediaelement');
  wp_deregister_style('mediaelement');

  wp_dequeue_style( 'screen' );
  wp_deregister_style( 'screen' );
  wp_register_script( 'fc_scripts', get_template_directory_uri() . '/dist/scripts/production.min.js', array('jquery'), '', false );
  wp_enqueue_script( 'fc_scripts' );
}
add_action( 'wp_enqueue_scripts', 'fc_handle_scripts_and_styles', 1 );

// grab production css instead of default
function fc_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri ) {
    return $stylesheet_dir_uri . '/dist/styles/style.min.css';
}
add_filter( 'stylesheet_uri', 'fc_stylesheet_uri', 1, 2 );



/**
 * custom excerpt and read more
 */
function fc_excerpt_more( $more ) {
  return '&hellip;';
}
add_filter('excerpt_more', 'fc_excerpt_more');

function fc_excerpt_read_more_link( $output ) {
  global $post;
  return $output . ' <a href="' . get_permalink( $post->ID ) . '" class="moretag" title="Read More">More</a>';
}
add_filter( 'get_the_excerpt', 'fc_excerpt_read_more_link' );



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
 * FC header - hero, carousel and nav
 */
function fc_header_top() {
  if( is_front_page() || get_current_type() === "tax" ) { ?>
    <div class="hero hero-main" data-speed="1.6" data-type="scroll">
      <div class="hero-inner">
        <a class="hero-logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><img src="<?php get_template_directory_uri(); ?>/wp-content/themes/fogged-clarity/dist/images/fc_logo.png" width="156" height="156" alt="fc logo"></a>
        <div class="hero-copy">
          <h1 class="blog-name"><?php bloginfo( 'name' ); ?></h1>
            <hr class="divider">
            <h3><?php echo get_term_by( 'slug', $_COOKIE['issuem_issue'], 'issuem_issue' )->name; ?></h3>
        </div>
      </div>
    </div>
  <?php } else { ?>
      <div class="hero hero-main hero-sm" data-speed="1.6" data-type="scroll">
        <div class="hero-inner">
          <a class="hero-logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><img src="<?php get_template_directory_uri(); ?>/wp-content/themes/fogged-clarity/dist/images/fc_logo.png" width="156" height="156" alt="fc logo"></a>
        </div>
      </div>
  <?php } ?>
 
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
  // wp_print_r ( get_current_type() );
  if( is_front_page() || get_current_type() === "tax" ) { ?>
    <div class="hero hero-carousel loading">
      <div class="feature-rotator">
        <?php echo do_shortcode('[issuem_featured_rotator]'); ?>
      </div>
    </div>
  <?php } else {
    global $post;
    if( !is_front_page() && is_page() ) { ?>
      <h1 class="page-title"><?php the_title( $post->id ); ?></h1>
      <div class="entry-meta">
        <hr class="divider">
        <?php if( is_page( 'Contributors' ) ) { ?><h3><?php echo get_issuem_issue_title(); } ?></h3>
        <?php if ( function_exists( 'sharing_display' ) ) { sharing_display( '', true ); } ?>
      </div><?php
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
    if( function_exists( 'tptn_show_pop_posts' ) ) tptn_show_pop_posts();
  }
}
add_action( 'tha_footer_before', 'fc_footer_before');



/**
 * Add Google Analytics tracking
 *
 */
function fc_footer_after() { ?>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-7349365-1', 'auto');
    ga('send', 'pageview');
  </script>
<?php }
add_action( 'tha_footer_after', 'fc_footer_after' );



/**
 * Embed disqus comments system
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
 * Add sharedaddy scripts manually 
 */
function jetpack_remove_share() {
  remove_filter( 'the_content', 'sharing_display', 19 );
  remove_filter( 'the_excerpt', 'sharing_display', 19 );
  if ( class_exists( 'Jetpack_Likes' ) ) {
      remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
  }
}
add_action( 'loop_start', 'jetpack_remove_share' );

// get jetpack sharedaddy scripts manually
function fc_add_sharedaddy() {
  wp_enqueue_script( 'sharing-js', WP_SHARING_PLUGIN_URL . 'sharing.js', array( ), 3 );
  $sharing_js_options = array(
    // 'lang'   => get_base_recaptcha_lang_code(),
    'counts' => apply_filters( 'jetpack_sharing_counts', true )
  );
  wp_localize_script( 'sharing-js', 'sharing_js_options', $sharing_js_options);
}
add_action( 'wp_enqueue_scripts', 'fc_add_sharedaddy' );



/**
 * helper functions
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



/**
 * register custom footer widgets
 */

function fc_widgets_init() {
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
 * make WordPress faster
 */

// remove query strings from static resources so they get cached
function ewp_remove_script_version( $src ){
  return remove_query_arg( 'ver', $src );
}
add_filter( 'script_loader_src', 'ewp_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'ewp_remove_script_version', 15, 1 );



/**
 * make WordPress client friendly
 */

// custom login page
function custom_login_css() {
  echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/library/assets/css/custom-login.min.css" />';
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
  #issuem_rss_item, #welcome-panel {
    display: none;
    visibility: hidden;
  }
  </style>';
}
add_action('admin_head', 'fc_hide_admin_nags');

// custom admin dashboard logo
// function custom_admin_logo()
// {
//     echo '<style type="text/css">
//        #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
//          content: url(' . get_bloginfo('stylesheet_directory') . '/images/fc_logo_tiny.png) !important;
//          width: 20px !important;
//        }</style>';
// }
// add_action('admin_head', 'custom_admin_logo');



/**
 * Customize backend for non-admins
 */

// $admins = array( 'RyanDaly' ); // add more comma separated usernames here if needed
// $current_user = wp_get_current_user();
// // echo 'Username: ' . $current_user->user_login . '<br />';

// // if not admin, add filters and actions
// if( !in_array( $current_user->user_login, $admins ) ) {
//     //add_filter( 'parse_query', 'fc_hide_admin_pages' );
//     //add_action('admin_notices', 'fc_hide_update_notification');
//     //add_action('admin_menu', 'fc_hide_admin_menus', 999);
// }

// // remove wp upgrade notification
// function fc_hide_update_notification() {  
//   remove_action('admin_notices', 'update_nag', 3);
// }

// // remove some admin menu items
// function fc_hide_admin_menus() {
//     // remove_submenu_page( 'jetpack', 'jetpack' );
//     // remove_menu_page( 'jetpack' );
//     remove_menu_page( 'wpcf7' ); // Contact Form 7
//     remove_menu_page( 'edit.php?post_type=cfs' ); // Custom Field Suite
    
//     global $menu;
//     $restricted = array(__('Media', 'foggedclarity'), __('Appearance', 'foggedclarity'), __('Plugins', 'foggedclarity'), __('Tools', 'foggedclarity'), __('Settings', 'foggedclarity'), __('Roles', 'foggedclarity'), __('Links', 'foggedclarity'), __('Comments', 'foggedclarity'));
//     end ($menu);
//     while (prev($menu)){
//         $value = explode(' ',$menu[key($menu)][0]);
//         if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
//     }
// }

// // remove some pages
// function fc_hide_admin_pages($query) {
//     if ( ! is_admin() )
//         return $query;
//     global $pagenow, $post_type;
//         if ( !current_user_can( 'administrator' ) && is_admin() && $pagenow == 'edit.php' && $post_type == 'page' )
//             $query->query_vars['post__not_in'] = array( '32', '44' ); // Enter more page IDs here
// }