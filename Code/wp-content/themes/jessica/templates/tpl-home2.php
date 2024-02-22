<?php
/*
 * Template Name: Home Page 2
 *  The template for dispalying the page.
 *
 *  @package WordPress
 *  @subpackage illdy
 */
?>
<?php  


do_action( 'genesis_doctype' );

/**
 * Fires immediately after `genesis_doctype` action hook, in header.php to render the document title.
 *
 * @since 1.0.0
 */
do_action( 'genesis_title' );

/**
 * Fires immediately after `genesis_title` action hook, in header.php to render the meta elements.
 *
 * @since 1.0.0
 */
do_action( 'genesis_meta' );

wp_head(); // We need this for plugins.
?>
</head>
<?php
genesis_markup(
  [
    'open'    => '<body %s>',
    'context' => 'body',
  ]
);

if ( function_exists( 'wp_body_open' ) ) {
  wp_body_open();
}

/**
 * Fires immediately after the `wp_body_open` action hook.
 *
 * @since 1.0.0
 */
do_action( 'genesis_before' );

genesis_markup(
  [
    'open'    => '<div %s>',
    'context' => 'site-container',
  ]
);

/**
 * Fires immediately after the site container opening markup, before `genesis_header` action hook.
 *
 * @since 1.0.0
 */
do_action( 'genesis_before_header' );

/**
 * Fires to display the main header content.
 *
 * @since 1.0.2
 */
do_action( 'genesis_header' );
  global $tx_ctm_opt; 
  get_header('second');
/**
 * Fires immediately after the `genesis_header` action hook, before the site inner opening markup.
 *
 * @since 1.0.0
 */
do_action( 'genesis_after_header' );

genesis_markup(
  [
    'open'    => '<div %s>',
    'context' => 'site-inner',
  ]
);
genesis_structural_wrap( 'site-inner' );

 ?>
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-12">
                    <div class="category verticle-menu"> 
                      <nav class='animated bounceInDown'>
                      <div class="vertical-title" data-toggle="collapse" href="#vertival-collapse" role="button" aria-expanded="false" aria-controls="vertival-collapse" >
                          <span class="menu-toggle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/vertical-icon.png" alt="vertical-icon"><?php echo  __('Shop By Category', 'jessica'); ?></span>
                      </div>   
                      <div class="collapse show" id="vertival-collapse">
                                    <?php wp_nav_menu( 
                                      array( 
                                        'theme_location' => 'secondary',
                                        'container' => '',
                                        'menu_class' => genesis_get_option('nav_superfish') ? 'genesis-nav-menu superfish' : 'genesis-nav-menu')); ?>         
                      </div> 

                      </nav>
                     </div>          
        </div> 
        <div class="col-lg-9 col-12">
           <?php
            genesis_widget_area(
              'rotator', array(
                'before' => '<section class="banner-section">',
                'after'  => '</section>',
              )
            );
          ?>
        </div>
      </div>
    </div>
<?php

  genesis_widget_area(
    'home-page-1', array(
      'before' => '<section class="offercms-section pb-100"><div class="container">',
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
    'home-page-8', array(
      'before' => '<section class="specialproduct-section pb-130"><div class="container">',
      'after'  => '</div></section>',
    )
  );

  ?>
      <div class="container">
      <div class="row">
        <div class="col-md-4 col-12 mb-md-0 mb-sm-3">
          <?php 
            genesis_widget_area(
              'home3-page-service', array(
                'before' => '<div class="service-block">',
                'after'  => '</div>',
              )
            );
          ?>
        </div>
        <div class="col-md-8 col-12">
          <?php 
            genesis_widget_area(
              'home-page-3', array(
                'before' => '<section class="parallax-section section-padding"><div class="container">',
                'after'  => '</div></section>',
              )
            );

          ?>
        </div>
      </div>
    </div>
  <?php


  echo '<section class="feature-product-slider pt-100">
         <div class="container">
              <div class="col-12">
                <div class="row justify-content-between">';

              genesis_widget_area(
                'home5-page-product', array(
                  'before' => '',
                  'after'  => '',
                )
              );
  
  echo '</div>
      </div>
    </div>  
   </section>  ';



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
?>

  <div class="container pb-80">
    <div class="row">
      <div class="col-md-8 col-12 mb-md-0 mb-sm-3">
        <?php
                        
        genesis_widget_area(
          'home-page-4', array(
            'before' => '<section class="testimonial-section"><div class="container">',
            'after'  => '</div></section>',
          )
        );
        ?>
        </div>
        <div class="col-md-4 col-12">
        <?php
        genesis_widget_area(
          'home-page-9', array(
            'before' => '<div class="special-banner">',
            'after'  => '</div>',
          )
        );

        ?>
        </div>
    </div>
  </div>

  <?php

get_footer();