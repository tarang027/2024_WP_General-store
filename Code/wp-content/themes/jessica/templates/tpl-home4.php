<?php
/*
 * Template Name: Home Page 4
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
  get_header('fourth');
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
    'home4-page-category', array(
      'before' => '<section class="woocommerce category product-section pb-100"><div class="container"><div class="row justify-content-between">',
      'after'  => '</div></div></section>',
    )
  );
  genesis_widget_area(
    'home-page-3', array(
      'before' => '<section class="parallax-section section-padding"><div class="container">',
      'after'  => '</div></section>',
    )
  );

   echo '<section class="feature-product-slider pt-100 pb-80">
         <div class="container">
              <div class="col-12">
                <div class="row justify-content-between">';

  genesis_widget_area(
    'home4-page-product', array(
      'before' => '',
      'after'  => '',
    )
  );
  
  echo '</div>
         </div>
      </div>  
   </section>  ';

                  
  genesis_widget_area(
    'home-page-4', array(
      'before' => '<section class="testimonial-section section-padding"><div class="container">',
      'after'  => '</div></section>',
    )
  );


  genesis_widget_area(
    'home-page-8', array(
      'before' => '<section class="specialproduct-section section-padding"><div class="container">',
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
      'before' => '<section class="brand-logo"><div class="container">',
      'after'  => '</div></section>',
    )
  );



get_footer(); ?>