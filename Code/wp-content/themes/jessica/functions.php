<?php
/**
 * Jessica.
 *
 * This file adds functions to the Jessica Theme.
 *
 * @package Jessica
 * @author  9seeds
 * @license GPL-2.0-or-later
 * @link    https://9seeds.com/
 */

add_action( 'after_setup_theme', 'jessica_i18n' );
/**
 * Load the child theme textdomain for internationalization.
 *
 * Must be loaded before Genesis Framework /lib/init.php is included.
 * Translations can be filed in the /languages/ directory.
 *
 * @since 1.2.0
 */
function jessica_i18n() {
	load_child_theme_textdomain( 'jessica', get_stylesheet_directory() . '/languages' );
}

// Start the Genesis engine.
require_once TEMPLATEPATH . '/lib/init.php';

// Load Jessica Init.
require_once 'lib/init.php';

// load helper.
require_once CHILD_DIR . '/lib/helper.php';

//Init the Redux Framework
if ( class_exists( 'ReduxFramework' ) && !isset( $redux_demo ) && file_exists( CHILD_DIR .'/theme-config.php' ) ) {
    require_once( CHILD_DIR .'/theme-config.php' );
}
//Include
if ( file_exists( CHILD_DIR .'/lib/styleswitcher.php' ) ) {
    require_once( CHILD_DIR .'/lib/styleswitcher.php' );
}

add_action( 'wp_enqueue_scripts', 'jessica_enqueue_assets' );
/**
 * Enqueue theme assets.
 */
function jessica_enqueue_assets() {
	global  $tx_ctm_opt;
	wp_enqueue_style( 'jessica', get_stylesheet_uri() );
	wp_style_add_data( 'jessica', 'rtl', 'replace' );


	wp_enqueue_script( 'jqueryzoom', get_stylesheet_directory_uri() . '/lib/js/jquery.zoom.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	

	wp_enqueue_script( 'font-awesome', get_stylesheet_directory_uri() . '/lib/js/fontawesome/all.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	
	wp_enqueue_style( 'font-awesome', get_bloginfo('stylesheet_directory') . '/lib/js/fontawesome/all.min.css');

	wp_enqueue_style('bootstrap', get_bloginfo('stylesheet_directory') . '/lib/js/bootstrap/bootstrap.min.css');
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/lib/js/bootstrap/bootstrap.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	



	wp_enqueue_script( 'circle', get_stylesheet_directory_uri() . '/lib/js/circle-progress.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	 

    wp_enqueue_style('fancybox', get_bloginfo('stylesheet_directory') . '/lib/js/fancybox/jquery.fancybox.min.css');

	wp_enqueue_script( 'fancybox', get_stylesheet_directory_uri() . '/lib/js/fancybox/jquery.fancybox.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	 


	wp_enqueue_style('slick', get_bloginfo('stylesheet_directory') . '/lib/js/slick/slick.css');
	wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/lib/js/slick/slick.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	

	wp_enqueue_script( 'pace', get_stylesheet_directory_uri() . '/lib/js/pace.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	
      
	wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/lib/js/main.js', array( 'jquery' ), CHILD_THEME_VERSION, true );	


		
	// Compile Less to CSS
	$previewpreset = (isset($_REQUEST['preset']) ? $_REQUEST['preset'] : null);
		//get preset from url (only for demo/preview)
	if($previewpreset){
		$_SESSION["preset"] = $previewpreset;
	}
	if(!isset($_SESSION["preset"])){
		$_SESSION["preset"] = 1;
	}
	if($_SESSION["preset"] != 1) {
		$presetopt = $_SESSION["preset"];
	} else { /* if no preset varialbe found in url, use from theme options */
		$presetopt = $tx_ctm_opt['preset_option'];
	}
	if(!isset($presetopt)) $presetopt = 1; /* in case first time install theme, no options found */
	
	// Load main theme css style
	wp_enqueue_style( 'themextra-css', get_bloginfo('stylesheet_directory') . '/css/theme'.$presetopt.'.css', array(), '1.0.0' );
	
	if($tx_ctm_opt['enable_sswitcher']){

	wp_enqueue_style( 'styleswitcher-css', get_bloginfo('stylesheet_directory') . '/css/styleswitcher.css', array(), '1.0.0' );

	wp_enqueue_script( 'styleswitcher-js', get_stylesheet_directory_uri() . '/lib/js/styleswitcher.js', array( 'jquery' ), CHILD_THEME_VERSION, false );	
   }
}

//add custom css
function tx_custom_code_header() {
	global $tx_ctm_opt;

	if ( isset($tx_ctm_opt['custom_css']) && $tx_ctm_opt['custom_css']!='') { ?>
		<style><?php echo esc_html($tx_ctm_opt['custom_css']); ?></style>
	<?php } ?>
	<script type="text/javascript">
	var tx_brandnumber = <?php if(isset($tx_ctm_opt['brandnumber'])) { echo esc_js($tx_ctm_opt['brandnumber']); } else { echo '5'; } ?>,
		tx_brandscroll = <?php echo esc_js($tx_ctm_opt['brandscroll'])==1 ? 'true': 'false'; ?>,
		tx_brandscrollnumber = <?php if(isset($tx_ctm_opt['brandscrollnumber'])) { echo esc_js($tx_ctm_opt['brandscrollnumber']); } else { echo '1';} ?>,
		tx_brandpause = <?php if(isset($tx_ctm_opt['brandpause'])) { echo esc_js($tx_ctm_opt['brandpause']); } else { echo '3000'; } ?>,
		tx_brandanimate = <?php if(isset($tx_ctm_opt['brandanimate'])) { echo esc_js($tx_ctm_opt['brandanimate']); } else { echo '700';} ?>;
	var tx_blogscroll = <?php echo esc_js($tx_ctm_opt['blogscroll'])==1 ? 'true': 'false'; ?>,
		tx_blogpause = <?php if(isset($tx_ctm_opt['blogpause'])) { echo esc_js($tx_ctm_opt['blogpause']); } else { echo '3000'; } ?>,
		tx_bloganimate = <?php if(isset($tx_ctm_opt['bloganimate'])) { echo esc_js($tx_ctm_opt['bloganimate']); } else { echo '700'; } ?>;
	</script>
	<?php
}
add_action( 'wp_head', 'tx_custom_code_header');
/**
 * Load Editor Scripts
 */
function jessica_editor_scripts() {
	wp_enqueue_script( 'jessica-editor', get_stylesheet_directory_uri() . '/editor.js', array( 'wp-blocks', 'wp-dom' ), filemtime( get_stylesheet_directory() . '/editor.js' ), true );
}
add_action( 'enqueue_block_editor_assets', 'jessica_editor_scripts' );


// Calls the theme's constants & files.
jessica_init();

// Editor Styles.
add_theme_support( 'editor-styles' );
add_editor_style( 'style-editor.css' );

// Adds support for accessibility.
add_theme_support( 'genesis-accessibility', genesis_get_config( 'accessibility' ) );

// add gutebnerg support.
add_theme_support( 'gutenberg' );
add_theme_support( 'align-wide' );
add_theme_support( 'responsive-embeds' );

// disable custom color.
add_theme_support( 'disable-custom-colors' );


// Add new image sizes.
add_image_size( 'Blog Thumbnail', 162, 159, true );
add_image_size( 'store', 217, 312, true );


// * Customize the entry meta in the entry header (requires HTML5 theme support).
remove_action( 'genesis_entry_header', 'genesis_post_info',12 );
add_action( 'genesis_entry_header', 'genesis_post_info',12 );
add_action( 'genesis_entry_content', 'genesis_post_info', 6 );
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter( $post_info ) {
	$post_info = '[post_date format="j M"]'.'<span class="author-link"><i class="fas fa-pen"></i>[post_author]</span><span class="comment"><i class="fas fa-comments"></i>[post_comments]</span>';
	return $post_info; 	
}


// * Reposition the post image (requires HTML5 theme support).
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 5 );

add_action( 'genesis_entry_header', 'genesis_do_post_icon', 5 );
function genesis_do_post_icon() {
   if(!is_singular( 'post' )) { ?>
	      <div class="blog-icon">
            <ul>
                <li class="zoom"><a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>" data-fancybox="blog" data-caption="image"><i class="fas fa-search-plus"></i></a></li>
                <li class="more"><a href="<?php the_permalink(); ?>"><i class="fas fa-link"></i></a></li>
            </ul>
          </div>
<?php } }
remove_action( 'genesis_entry_header', 'genesis_do_post_title', 5 );
add_action( 'genesis_entry_content', 'genesis_do_post_title', 5 );

// Customize the Search Box.
add_filter( 'genesis_markup_search-form-submit_open', 'custom_search_button_text' );
function custom_search_button_text() {

	$search_button_text = apply_filters( 'genesis_search_button_text', esc_attr__( 'Search', 'genesis' ) );

	$searchicon = '<i class="fas fa-fw fa-search"></i>';

	return sprintf( '<button type="submit" name="submit" value="Go" class="search-icon" aria-label="Search">%s<span class="screen-reader-text">%s</span></button>', $searchicon, $search_button_text );

}

// Modify the author box display.
add_filter( 'genesis_author_box', 'jessica_author_box' );
function jessica_author_box() {
	$authinfo = '';
	$authdesc = get_the_author_meta( 'description' );

	if ( ! empty( $authdesc ) ) {
		$authinfo .= sprintf( '<section %s>', genesis_attr( 'author-box' ) );
		$authinfo .= '<h3 class="author-box-title">' . __( 'About the Author', 'jessica' ) . '</h3>';
		$authinfo .= get_avatar( get_the_author_meta( 'ID' ), 90, '', get_the_author_meta( 'display_name' ) . '\'s avatar' );
		$authinfo .= '<div class="author-box-content" itemprop="description">';
		$authinfo .= '<p>' . get_the_author_meta( 'description' ) . '</p>';
		$authinfo .= '</div>';
		$authinfo .= '</section>';
	}
	if ( is_author() || is_single() ) {
		echo $authinfo;
	}
}

// Customize the post meta function.

// Add Read More button to blog page and archives.
add_filter( 'excerpt_more', 'jessica_add_excerpt_more' );
add_filter( 'get_the_content_more_link', 'jessica_add_excerpt_more' );
add_filter( 'the_content_more_link', 'jessica_add_excerpt_more' );
function jessica_add_excerpt_more( $more ) {
	return '...';
}

add_filter( 'excerpt_length', 'sp_excerpt_length' );
function sp_excerpt_length( $length ) {
	return 30; // pull first 50 words
}


function featured_post_image() {
	
	if ( ! is_singular( 'post' ) ) return; ?>
	<?php the_post_thumbnail('post-image') ?>
<?php }

// Display featured image below title on the single post in Genesis 

add_action( 'genesis_entry_header', 'featured_post_image', 8 );



/*
 * Add support for targeting individual browsers via CSS
 * See readme file located at /lib/js/css_browser_selector_readm.html
 * for a full explanation of available browser css selectors.
 */
add_action( 'get_header', 'jessica_load_scripts' );
function jessica_load_scripts() {
	wp_enqueue_script( 'browserselect', CHILD_URL . '/lib/js/css_browser_selector.js', array( 'jquery' ), '0.4.0', true );
}

// Structural Wrap.
add_theme_support(
	'genesis-structural-wraps', array(
		'subnav',
		'site-inner',
		'footer-widgets',
		'footer',
	)
);


// Renames primary and secondary navigation menus.
add_theme_support( 'genesis-menus', genesis_get_config( 'menus' ) );

// * Add menu description.
add_filter( 'walker_nav_menu_start_el', 'be_add_description', 10, 2 );
function be_add_description( $item_output, $item ) {
	$description = $item->post_content;
	if ( ' ' !== $description ) {
		return preg_replace( '/(<a.*?>[^<]*?)</', '$1' . '<span class="menu-description">' . $description . '</span><', $item_output );
	} else {
		return $item_output;
	}
}

// * Remove the secondary navigation menu.

remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
// * Unregister Layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// * Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 4 );

// Setup Sidebars.
unregister_sidebar( 'sidebar-alt' );



genesis_register_sidebar(
	array(
		'id'          => 'rotator',
		'name'        => __( 'Rotator', 'jessica' ),
		'description' => __( 'This is the image rotator section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-1',
		'name'        => __( 'Home Category', 'jessica' ),
		'description' => __( 'This is the home category section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-2',
		'name'        => __( 'Home Catgeory Tab Wise Products', 'jessica' ),
		'description' => __( 'This is the home category tab wise products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home4-page-category',
		'name'        => __( 'Home4 Catgeory Tab Wise Products', 'jessica' ),
		'description' => __( 'This is the home category tab wise products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-3',
		'name'        => __( 'Home Parallax Block', 'jessica' ),
		'description' => __( 'This is the home parallax section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-4',
		'name'        => __( 'Home Testimonial', 'jessica' ),
		'description' => __( 'This is the home testimonial section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-5',
		'name'        => __( 'Home Brands logo', 'jessica' ),
		'description' => __( 'This is the Brand Logo section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-6',
		'name'        => __( 'Home Products', 'jessica' ),
		'description' => __( 'This is the Products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home3-page-service',
		'name'        => __( 'Home3 Service Block', 'jessica' ),
		'description' => __( 'This is the Service section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home4-page-product',
		'name'        => __( 'Home4 Feature Products', 'jessica' ),
		'description' => __( 'This is the Products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home5-page-product',
		'name'        => __( 'Home5 Feature Products', 'jessica' ),
		'description' => __( 'This is the Products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-7',
		'name'        => __( 'Home Blog', 'jessica' ),
		'description' => __( 'This is the blog section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-8',
		'name'        => __( 'Home Special Products', 'jessica' ),
		'description' => __( 'This is the Special product section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'home-page-9',
		'name'        => __( 'Home Service banner Section', 'jessica' ),
		'description' => __( 'This is the home category tab wise products section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'contact-page',
		'name'        => __( 'Contact Page', 'jessica' ),
		'description' => __( 'This is the contact page section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'about-video',
		'name'        => __( 'About Page', 'jessica' ),
		'description' => __( 'This is the before about page section.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'blog-sidebar',
		'name'        => __( 'Blog Sidebar', 'jessica' ),
		'description' => __( 'This is the Blog Page Sidebar.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'page-sidebar',
		'name'        => __( 'Page Sidebar', 'jessica' ),
		'description' => __( 'This is the Page Sidebar.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'store-sidebar',
		'name'        => __( 'Store Sidebar', 'jessica' ),
		'description' => __( 'This is the Store Page Sidebar.', 'jessica' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'footer-bottom-social',
		'name'        => __( 'Footer Social Bottom', 'jessica' ),
		'description' => __( 'This is the footer bottom social section.', 'jessica' ),
	)
);
// Return the array of "homepage sidebars" for this theme

function jessica_get_homepage_sidebar_array() {
	return array(
		'rotator',
		'home-nav',
		'home-page-1',
		'home-page-2',
		'home-page-3',
		'home-page-4',
		'home-page-5',
		'home-page-6',
		'home-page-7',
		'contact-page',
		'about-video',
		'home-page-8',
	);
}

// Remove edit link from TablePress tables for logged in users.
add_filter( 'tablepress_edit_link_below_table', '__return_false' );

// * Modify breadcrumb arguments..
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
function sp_breadcrumb_args( $args ) {
	$args['home']                = 'Home';
	$args['sep']                 = ' <sep>|</sep> ';
	$args['labels']['prefix']    = '';
	$args['labels']['author']    = '';
	$args['labels']['category']  = ''; // Genesis 1.6 and later.
	$args['labels']['tag']       = '';
	$args['labels']['date']      = '';
	$args['labels']['search']    = '';
	$args['labels']['tax']       = '';
	$args['labels']['post_type'] = '';

	return $args;
}

// * Insert SPAN tag into widgettitle.
add_filter( 'dynamic_sidebar_params', 'jessica_wrap_widget_titles', 20 );
function jessica_wrap_widget_titles( array $params ) {
	$widget                 =& $params[0];
	$widget['before_title'] = '<h4 class="widgettitle widget-title"><span class="sidebar-title">';
	$widget['after_title']  = '</span></h4>';

	return $params;
}


// remove option logo header / logo text from genesis on customizer.
add_action( 'customize_register', 's9_theme_customize_register', 99 );
function s9_theme_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'blog_title' );
}

// remove opstion logo header / logo text from genesis options.
add_action( 'genesis_theme_settings_metaboxes', 's9_remove_logo_metaboxes' );
function s9_remove_logo_metaboxes( $_genesis_admin_settings ) {
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
}

// show notice when logo failed to copy on upload folder.
function s9_failed_copy_notice() {
	echo '<div class="notice notice-error is-dismissible"><p>' . __( 'Failed to move site logo image, please upload your logo manually via appearance -> customise -> site identity', 'jessica' ) . '</p></div>';
}

// remove header-image body class.
add_filter( 'body_class', 'remove_header_image_class', 20, 2 );
function remove_header_image_class( $wp_classes ) {
	foreach ( $wp_classes as $key => $value ) {
		if ( $value == 'header-image' ) {
			unset( $wp_classes[ $key ] );
		}
	}

	return $wp_classes;
}

// Unregisters the header right widget area.
unregister_sidebar( 'header-right' );


// Determine what pages the "Blog Sidebar" should load on

function jessica_load_blog_sidebar() {
	// Legacy conditional check.  All these get the Blog Sidebar
	if( is_archive() || is_single() || is_category() || is_page_template( 'page_blog.php' ) ) {
		return true;
	}

	/*
	 * On the Page for Posts, get_the_id() will return the ID of the first post in the Loop, ***NOT***
	 * the ID of the Page itself, because reasons.  get_queried_object_id() will give us the _page's_
	 * ID.  Compare that to the page_for_posts option.  If they match, load the Blog Sidebar.
	 */

	$page_for_posts_id = (int) get_option( 'page_for_posts' );
	if( $page_for_posts_id !== 0 && ( $page_for_posts_id === get_queried_object_id() ) ) {
		return true;
	}
	
	// Everybody else gets the Page Sidebar
	return false;
}

//* Add the page widget in the content - HTML5
add_action( 'genesis_footer', 'genesis_social_links' );
function genesis_social_links() {
	dynamic_sidebar('footer-bottom-social');
}

/**gutenberg **/
add_filter('use_block_editor_for_post', '__return_false');


remove_action( 'genesis_attr_sidebar-primary', 'genesis_attributes_sidebar_primary', 8 );
add_filter( 'genesis_attr_sidebar-primary', 'genesis_attributes_sidebar_primary_child' );
/**
 * Add attributes for primary sidebar element.
 *
 * @since 2.0.0
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function genesis_attributes_sidebar_primary_child( $attributes ) { 
	$attributes['class'] = 'sidebar sidebar-primary widget-area left-column'; 
	return $attributes; 
}


remove_action( 'genesis_attr_content', 'genesis_attributes_content', 8 );
add_filter( 'genesis_attr_content', 'genesis_attributes_content_child' );
function genesis_attributes_content_child( $attributes ) { 
	
	$attributes['class'] = 'content right-column'; 
	return $attributes; 
	
}

add_filter( 'genesis_comment_list_args', 'topleague_comments_gravatar' );
function topleague_comments_gravatar( $args ) {
	$args['avatar_size'] = 100; // change the number depending on your requirement 
	return $args;
}

// Remove Comment Time & Link Inside the Date Field in Genesis (h/t: http://www.jowaltham.com/customising-comment-date-genesis/)
add_filter( 'genesis_show_comment_date', 'topleague_remove_comment_time_and_link' );
function topleague_remove_comment_time_and_link( $comment_date ) {
	printf( '<p %s>', genesis_attr( 'comment-meta' ) );
	printf( '<time %s>', genesis_attr( 'comment-time' ) );
	echo    esc_html( get_comment_date() );
	echo    '</time></p>';
	
	// Return false so that the parent function doesn't also output the comment date and time
	return false;
}


function jessica_preload() {  ?>
<div class="wrapper <?php if($tx_ctm_opt['page_layout']=='box'){echo 'box-layout';}?>">

 <div id="preloader"></div>


<?php }
add_action('genesis_before', 'jessica_preload');

function jesscia_scroll_to_top() { 
 echo '
   </div><div class="scroll-to-top">
      <span class="scroll-icon">
        <i class="fas fa-angle-up"></i>
      </span>
    </div>
 ';
}
add_action('genesis_after', 'jesscia_scroll_to_top');


//* Remove the site Header
remove_action('genesis_header', 'genesis_do_header');
remove_action('genesis_header', 'genesis_header_markup_open', 5);
remove_action('genesis_header', 'genesis_header_markup_close', 15);


add_filter( 'body_class', 'sp_body_class' );
function sp_body_class( $classes ) {
	global $tx_ctm_opt; 
	if ( $tx_ctm_opt['footer_layout']=='second') { 
		$classes[] = 'footer2';
	}else if ( $tx_ctm_opt['footer_layout']=='third') { 
		$classes[] = 'footer3';
	}
	
	if( $tx_ctm_opt['header_layout']=='second') { 
		$classes[] = 'index2';
	}else if ( $tx_ctm_opt['header_layout']=='third') { 
		$classes[] = 'index3';
	}else if ( $tx_ctm_opt['header_layout']=='fourth') { 
		$classes[] = 'index4';
	}


	if ( is_page_template('templates/tpl-home2.php') ) {
		$classes[] = 'home2 index2 ';
	}else if ( is_page_template('templates/tpl-home3.php') ) { 
		$classes[] = 'home3 index3 footer2';
	}else if ( is_page_template('templates/tpl-home4.php') ) { 
		$classes[] = 'home4 index4 footer3';
	}

	return $classes;
	
}

/**team member**/
add_shortcode('team_page', 'team_page');
function team_page() {
	ob_start();
	?>
    <section class="team-section section-padding">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-12">
              <div class="row justify-content-between"> 
              	<?php
				    query_posts('order=DESC&post_type=teams');
				    if (have_posts()) : while (have_posts()) : the_post();
					$designation = get_post_meta( get_the_ID(), 'designation', true );  ?>
	                  <div class="col-lg-4 col-sm-6 mb-lg-0 mb-md-3">
	                      <div class="team-single text-center">
	                          <div class="thumb">
	                              <?php the_post_thumbnail(); ?>
	                          </div>
	                          <div class="content text-center">
	                              <h5 class="name"><?php the_title(); ?></h5>
	                              <span class="designation"><?php echo $designation; ?></span>
	                              <?php the_content(); ?>
	                          </div>
	                      </div>
	                  </div>    
                    <?php endwhile; endif; ?>                                                    
              </div>  
          </div>       
        </div>
      </div>
    </section>	
    
<?php 
return ob_get_clean();

}


/**testimonial home**/
add_shortcode('testimonial_front_page', 'testimonial_front_page');
function testimonial_front_page() {
	ob_start();
	?>
        <div class="row justify-content-between align-items-center position-relative">              
            <div class="col-12 m-auto">           
                  <div class="testimonial-slider">
                  	<?php
				    query_posts('order=DESC&post_type=testimonials');
				    if (have_posts()) : while (have_posts()) : the_post();
					$designation = get_post_meta( get_the_ID(), 'designation', true );  ?>
                    <div class="testimonial-slide">
                      <div class="testimonial-slider-header d-flex">
                        <div class="client-thumb">
                           <?php the_post_thumbnail(); ?>
                        </div> 
                        <div class="testimonial-slide-body">
                          <?php the_content(); ?>
                        <div class="testi-content">
                          <h5 class="name"><?php the_title(); ?></h5>
                          <h5 class="name desig">- <?php echo $designation; ?></h5>
                        </div>
                        </div>
                      </div>
                    </div>
                    <?php endwhile; endif; ?>
                  </div>
                  <div class="testiarrow custom-slick-nav"></div>
            </div>
        </div>
    
<?php 
return ob_get_clean();

}

/**blog home**/
add_shortcode('blog_front_page', 'blog_front_page');
function blog_front_page() {
	ob_start();
	?>


      
              <div class="blog-slider row justify-content-between align-items-center position-relative">
				    <?php
				    query_posts('order=DESC&post_type=post');
				    if (have_posts()) : while (have_posts()) : the_post();
					$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>  
                    <div class="col-lg-4 col-12">
                        <div class="blog-slide">
                          <div class="blog-slider-header d-flex">
                            <div class="blog-img">
                                <?php the_post_thumbnail(); ?>
                                <div class="blog-meta"><span class="date"><?php echo date('j'); echo "<br/>".date('F'); ?></span></div>
                                <div class="blog-icon">
                                  <ul>
                                    <li class="zoom"><a href="<?php echo $image_url[0] ?>" data-fancybox="blog" data-caption="blog 1"><i class="fas fa-search-plus"></i></a></li>
                                    <li class="more"><a href="<?php the_permalink(); ?>"><i class="fas fa-link"></i></a></li>
                                  </ul>
                                </div>
                            </div>                 
                            <div class="blog-slide-body"> 
                                <h6 class="blog-title"> 
                                 <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                </h6>
                                <div class="blog-meta">
                                  <span><i class="fas fa-pen"></i> <?php echo do_shortcode('[post_author]'); ?></span>
                                  <span><i class="fas fa-comments"></i> <?php echo do_shortcode('[post_comments]'); ?></span>
                                </div>
                                <?php the_excerpt();?>
                            </div>
                          </div>
                        </div>
                    </div>    
				    <?php endwhile; endif; ?>
              </div>
             
              <div class="blogarrow custom-slick-nav"></div>
    
<?php 
return ob_get_clean();

}



add_shortcode( 'ourbrands', 'tx_brands_shortcode' );
function tx_brands_shortcode() {
    ob_start();
	global $tx_ctm_opt;
    $brand_index = 0;
	$brandfound=count($tx_ctm_opt['brand_logos']);

	
	
	if($tx_ctm_opt['brand_logos']) { ?>
		 	<div class="row justify-content-between brand-slider"> 
			<?php foreach($tx_ctm_opt['brand_logos'] as $brand) {
				if(is_ssl()){
					$brand['image'] = str_replace('http:', 'https:', $brand['image']);
				}

				?>
				<div class="brand-box">
				<?php 
				echo '<a href="'.$brand['url'].'" title="'.$brand['title'].'" class="slide-inner">';
					echo '<img src="'.$brand['image'].'" alt="'.$brand['title'].'" />';
				echo '</a>';
				?>
				</div>

				
			<?php } ?>
		 	</div> 

			
	<?php
	}
	
	return ob_get_clean();
}