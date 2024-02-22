<?php
/**
 * Jessica Front/Home page template for either widget based home page, or block based homepage.
 *
 * @package Jessica
 * @author  9seeds
 * @license GPL-2.0-or-later
 * @link    https://9seeds.com/
 */

/*
 * We want to load a widgetized home page if both are true:
 * 
 * 1) At least one homepage sidebar has a widget in it
 * 2) A static front page (Settings / Reading / Your homepage displays / A static page / Homepage ) is *not* set.
 *    Note: Static front pages should always take priority over widgets and Posts pages.
 * 
 * If both conditionals are true, load the widgetized version of the homepage.
 */

if( get_option( 'page_on_front' ) == 0 && jessica_is_homewidget_active() ) {
	jessica_do_widgetized_home();
} else {
	jessica_do_static_home();
}

function jessica_is_homewidget_active(){
	$active_homepage_sidebars = FALSE;
	$homepage_sidebars = jessica_get_homepage_sidebar_array();

	foreach( $homepage_sidebars as $homepage_sidebar ) {
		if( is_active_sidebar( $homepage_sidebar ) ) {
			$active_homepage_sidebars = TRUE;
		}
	}

	return $active_homepage_sidebars;
}

/**
 * Fallback to page templates if home page set to static page
 * This is to allow for traditional templates and block based, or page builder based
 * Front Pages.
 *
 * @return void
 */
function jessica_do_static_home() {
	global $post;
	$template = get_page_template_slug( $post->ID );

	if ( ! empty( $template ) ) {
		switch ( $template ) {
			// Templates from Genesis parent folder.
			case 'page_archive.php':
			case 'page_blog.php':
				// check if template exist, if not render as widget home
				if(!empty(locate_template(get_template_directory() . '/' . $template))){
					load_template( get_template_directory() . '/' . $template );
					exit;
				}else{
					jessica_do_widgetized_home();
				}
			// Templates from Genesis child folder.
			default:
				// check if template exist, if not render as widget home
				if(!empty(locate_template(__DIR__ . '/' . $template))){
					load_template( __DIR__ . '/' . $template );
					exit;
				}else{
					jessica_do_widgetized_home();
				}
		}
	}
}

/**
 * Traditional Genesis Widget based Home Page output
 *
 * @return void
 */
function jessica_do_widgetized_home() {
	// default wp homepage.
	do_action( 'genesis_home' );

	// * Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	// Remove the standard loop.
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	// Execute custom child loop.
	add_action( 'genesis_loop', 'jessica_home_loop_helper' );

	add_action( 'genesis_before_footer', 'jessica_bottom_widgets', 5 );

}

/**
 * Jessica Home Loop Helper
 *
 * @return void
 */
function jessica_home_loop_helper() {

	genesis_widget_area(
		'rotator', array(
			'before' => '<section class="banner-section">',
			'after'  => '</section>',
		)
	);

	genesis_widget_area(
		'home-page-1', array(
			'before' => '<section class="offercms-section section-padding"><div class="container">',
			'after'  => '</div></section>',
		)
	);

	genesis_widget_area(
		'home-page-2', array(
			'before' => '<section class="woocommerce category product-section pb-100"><div class="container"><div class="row justify-content-between"><div class="col-12">',
			'after'  => '</div></div></div></section>',
		)
	);
	genesis_widget_area(
		'home-page-3', array(
			'before' => '<section class="parallax-section section-padding"><div class="container">',
			'after'  => '</div></section>',
		)
	);



}

/**
 * Jessica Home Bottom Widget Area Output
 *
 * @return void
 */
function jessica_bottom_widgets() {
   echo '<section class="product-slider pt-100 pb-80">
     		 <div class="container">
          		<div class="col-12">
          			<div class="row justify-content-between">';

	genesis_widget_area(
		'home-page-6', array(
			'before' => '',
			'after'  => '',
		)
	);
	
	echo '</div>
         </div>
      </div>  
   </section>  ';


	genesis_widget_area(
		'home-page-8', array(
			'before' => '<section class="specialproduct-section pb-130"><div class="container">',
			'after'  => '</div></section>',
		)
	);

	genesis_widget_area(
		'home-page-7', array(
			'before' => '<section class="blog-section pb-100"><div class="container"><div class="row justify-content-between"><div class="col-12">',
			'after'  => '</div></div></div></section>',
		)
	);
         
	genesis_widget_area(
		'home-page-5', array(
			'before' => '<section class="brand-logo pb-50"><div class="container">',
			'after'  => '</div></section>',
		)
	);
	          			
	genesis_widget_area(
		'home-page-4', array(
			'before' => '<section class="testimonial-section section-padding"><div class="container">',
			'after'  => '</div></section>',
		)
	);

}

genesis();