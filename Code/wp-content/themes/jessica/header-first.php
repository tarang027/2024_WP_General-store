<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Templates
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

/**
 * Fires at start of header.php, immediately before `genesis_title` action hook to render the Doctype content.
 *
 * @since 1.3.0
 */
global $tx_ctm_opt; 
if(is_ssl()){
	$tx_ctm_opt['logo_main']['url'] = str_replace('http:', 'https:', $tx_ctm_opt['logo_main']['url']);
}
?>

    <header class="header-section">
        <div class="container">
          <div class="row">
            <div class="col-12">      
              <div class="header-banner">
                <div class="d-flex header-padding">   
                      <?php if( isset($tx_ctm_opt['logo_main']['url']) ){ ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="site-logo site-title" rel="home"><img src="<?php echo esc_url($tx_ctm_opt['logo_main']['url']); ?>" alt="" /></a>
                      <?php
                      } else { ?>
                                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="site-logo site-title" rel="home"><?php bloginfo( 'name' ); ?></a>
                                  <?php
                      } ?>         
                    <div class="search-block">            
                      <div class="header-search-block">
                            <?php get_product_search_form(); ?> 
                      </div> 
                    </div>
                    
                    <div class="contact-block d-flex">
                      <div class="cart-icon"><i class="fas fa-mobile"></i></div>

                      <a href="<?php echo esc_html($tx_ctm_opt['header_phone']); ?>">
                        <?php echo esc_html($tx_ctm_opt['header_phone_Text']); ?>
                            <span class="count"><?php echo __('Call : ' ,'jessica'); ?><?php echo esc_html($tx_ctm_opt['header_phone']); ?></span>
                      </a> 
                     
                    </div>
                                                                      
                    <?php echo do_shortcode('[custom_mini_cart]'); ?>   
                </div>
              </div>
              <div class="header-top">
                  <div class="d-flex">
                        <nav class="navbar navbar-expand-xl mr-auto">

                        <div class="verticle-menu mr-auto">        
                          <div class="navbar-toggler mr-auto visible-small">
                                <div class="mobile-menu">
                                    <div class="mbmenu-toggler"><i class="fas fa-list"></i></div>
                                      <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'mobile-menu-container', 'menu_class' => 'nav-menu' ) ); ?>
                                  </div>
                            </div>                     
                        </div>
                          
                           <?php wp_nav_menu( array( 'theme_location' => 'primary',  'container_class' => 'collapse navbar-collapse', 'container_id' => 'navbarSupportedContent', 'menu_class' => 'menu genesis-nav-menu menu-primary js-superfish sf-js-enabled sf-arrows main-menu ml-auto' ) ); ?>      
                                                            
                        </nav>
                          <div class="d-flex headericon">
                            <div class="header-search-block">
                                            <button class="search-toggle search-icon icon-search" data-toggle="collapse" data-target="#searchtoggle"><i class="fas fa-fw fa-search"></i></button>
                                                
                                              <div class="header-search collapse widget-area" id="searchtoggle">
                                  <?php get_product_search_form(); ?> 
                                            </div>
                              </div>                            
                              <div class="wishlist">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>wishlist">
                                  <i class="fas fa-heart"></i><?php echo __('Whishlist' ,'jessica'); ?>
                                </a>
                              </div>  
                              <div class="my-account"> 
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>my-account" class="user"><i class="fas fa-user"></i><?php echo __('My Account' ,'jessica'); ?></a>
                              </div> 

                          </div>
                  </div> 
              </div>                     
            </div> 
          </div> 
        </div> 
    </header>

