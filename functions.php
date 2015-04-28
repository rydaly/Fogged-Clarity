<?php
/**
 * foggedclarity functions and definitions
 *
 * @package foggedclarity
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1000; /* pixels */
}

if ( ! function_exists( 'foggedclarity_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function foggedclarity_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on foggedclarity, use a find and replace
	 * to change 'foggedclarity' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'foggedclarity', get_template_directory() . '/library/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_action( 'init', 'neat_add_editor_style' );
	/**
	 * Apply theme's stylesheet to the visual editor.
	 *
	 * @uses add_editor_style() Links a stylesheet to visual editor
	 * @uses get_stylesheet_uri() Returns URI of theme stylesheet
	 */
	function neat_add_editor_style() {

	    add_editor_style( get_stylesheet_uri() );

	}

	//register_nav_menu( 'main-menu', __( 'Your sites main menu', 'fc' ) );
	// This theme uses wp_nav_menu() in one location.
	if ( !function_exists('dg_register_nav_menus') ) :
		function dg_register_nav_menus() {

			register_nav_menu( 'primary-navigation', __( 'Primary Menu', 'foggedclarity' ) );

		}
		add_action( 'init', 'dg_register_nav_menus' );
	endif;

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status', 'gallery', 'chat', 'audio' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'foggedclarity_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // foggedclarity_setup
add_action( 'after_setup_theme', 'foggedclarity_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
if ( !function_exists('foggedclarity_widgets_init') ) :
	function foggedclarity_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'foggedclarity' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}
	add_action( 'widgets_init', 'foggedclarity_widgets_init' );
endif;

/**
 * Enqueue scripts and styles.
 */
if ( !function_exists('foggedclarity_scripts') ) :
	function foggedclarity_scripts() {
		if (!is_admin()) {
			wp_enqueue_script('jquery');
		}

		// Main Style
		// wp_enqueue_style( 'foggedclarity-style', get_stylesheet_uri() );
		wp_enqueue_style( 'foggedclarity-style', get_template_directory_uri() . '/dist/styles/style.min.css' );

		// Dashicons
		wp_enqueue_style( 'dashicons', get_stylesheet_directory_uri() . '/library/assets/css/dashicons.css' );

		// Flexnav Scripts
		// wp_register_script( 'flexnav', get_stylesheet_directory_uri() . '/library/assets/js/flexnav/jquery.flexnav.js', array(), '1.0.0', false );
		// wp_enqueue_script( 'flexnav' );

		// Modernizr
		wp_register_script( 'modernizr', get_stylesheet_directory_uri() . '/library/assets/js/modernizr/modernizr-2.7.1.js', array(), '2.7.1', false );
		wp_enqueue_script( 'modernizr' );

		// Selectivizr Scripts
		// wp_register_script( 'selectivizr', get_stylesheet_directory_uri() . '/library/assets/js/selectivizr/selectivizr.js', array(), '1.0.0', false );
		// wp_enqueue_script( 'selectivizr' );

		// Hover Intent Scripts
		// wp_register_script( 'hoverintent', get_template_directory_uri() . '/library/assets/js/hoverintent/hoverintent.js', array(), '1.0.0', false );
		// wp_enqueue_script( 'hoverintent' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'foggedclarity_scripts' );
endif;

/**
 * Including Theme Hook Alliance (https://github.com/zamoose/themehookalliance).
 */
include( 'library/vendors/tha-theme-hooks/tha-theme-hooks.php' );

/**
 * Including Kirki Advanced Theme Customizer (https://github.com/aristath/kirki).
 */
// include_once( dirname( __FILE__ ) . '/library/vendors/kirki/kirki.php' );

/**
 * WP Customizer
 */
// require get_template_directory() . '/library/vendors/wp-customizer/customizer.php';

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/library/vendors/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/library/vendors/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/library/vendors/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/library/vendors/jetpack.php';

/**
 * Including TGM Plugin Activation
 */
require_once( get_template_directory() . '/library/vendors/tgm-plugin-activation/required-plugins.php' );

/**
 * Custom Hooks and Filters
 */
if ( !function_exists('neat_add_breadcrumbs') ) :
	function neat_add_breadcrumbs() {
		if ( !is_front_page() ) {
			if (function_exists('HAG_Breadcrumbs')) { HAG_Breadcrumbs(); }
		}
	}
	add_action( 'tha_content_top', 'neat_add_breadcrumbs' );
endif;

if ( !function_exists('neat_optional_scripts') ) :
	function neat_optional_scripts() {
		// Font Awesome
		if( get_theme_mod( 'add_fontawesome_icons' ) == '') {

		 } else {
		 	echo '<link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css" rel="stylesheet">';
		 }
		 // Genericons
		 if( get_theme_mod( 'neat_add_genericon_icons' ) == '') {

		 } else {
		 	echo '<link href=" '.get_stylesheet_directory_uri().'/library/assets/css/genericons.css" rel="stylesheet">';
		 }

		 // Link Color
		 if( get_theme_mod( 'neat_add_link_color' ) == '') {

		 } else { ?>
			<style type="text/css">
				a { color: <?php echo get_theme_mod( 'neat_add_link_color' ); ?>; }
			</style>
		<?php }


	}
	add_action( 'wp_head', 'neat_optional_scripts' );
endif;

if ( !function_exists('neat_mobile_styles') ) :
	function neat_mobile_styles() {
		$value = get_theme_mod( 'neat_mobile_hide_arrow' );

		 if( get_theme_mod( 'neat_mobile_hide_arrow' ) == 0 ) { ?>
			<style>
				.menu-button i.navicon {
					display: none;
				}
			</style>
		<?php  } else {

		 }
	}
	add_action('wp_head', 'neat_mobile_styles' );
endif;

if ( !function_exists('neat_add_footer_divs') ) :
	function neat_add_footer_divs() { ?>

		<div class="footer-left">
			 <?php //echo get_theme_mod( 'neat_footer_left' ); ?>

		</div>
		<div class="footer-right">
			<?php //echo get_theme_mod( 'neat_footer_right' ); ?>
		</div>
<?php }
add_action( 'tha_footer_bottom', 'neat_add_footer_divs' );
endif;

add_action( 'tha_head_bottom', 'neat_add_selectivizr' );
function neat_add_selectivizr() { ?>
	<!--[if (gte IE 6)&(lte IE 8)]>
  		<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/selectivizr/selectivizr-min.js"></script>
  		<noscript><link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" /></noscript>
	<![endif]-->
<?php }



/******************************************************************************\
	FC Custom Functions
\******************************************************************************/


// include custom functions
include_once( get_stylesheet_directory() . '/library/custom/issuem_custom.php' );
include_once( get_stylesheet_directory() . '/library/custom/custom.php' );