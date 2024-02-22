<?php
/**
 * Jessica.
 *
 * A template to force full-width layout, remove breadcrumbs, and remove the page title.
 *
 * Template Name: Landing
 *
 * @package Jessica
 * @author  9seeds
 * @since   2.0.0
 * @license GPL-2.0-or-later
 * @link    https://9seeds.com/
 */

// Force Full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove default Genesis elements
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_before_header', 'jessica_do_before_header' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Remove Jessica Specific hooks
remove_action( 'genesis_footer', 'jessica_do_footer' );

// No Nav Extras in Menu (ex: search)
add_filter( 'genesis_pre_get_option_nav_extras_enable', '__return_false' );

// Remove Header Nav
if ( function_exists( 'Gamajo\GenesisHeaderNav\get_plugin' ) ) {
	remove_action( 'genesis_header', array( Gamajo\GenesisHeaderNav\get_plugin(), 'show_menu' ), apply_filters( 'genesis_header_nav_priority', 12 ) );
}

add_filter( 'sidebars_widgets', 'seeds_starter_remove_header_right' );

genesis();
