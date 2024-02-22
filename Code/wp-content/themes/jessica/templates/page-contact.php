<?php
/*
 * Template Name: Contact Page
 *  The template for dispalying the page.
 *
 *  @package WordPress
 *  @subpackage illdy
 */
?>
<?php get_header(); ?>

    <!--================= contact section start =================-->
     <section class="contact pt-100 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12">  
 
              <?php 
        			if ( have_posts() ) {
        			while ( have_posts() ) : the_post();
          
                    genesis_widget_area(
                      'contact-page', array(
                        'before' => '<div class="contact-bg"><div class="container"><div class="row justify-content-between">',
                        'after'  => '</div></div></div>',
                      )
                    );

                    the_content(); 
                 
                    ?> 
                      <?php 
                      endwhile;
        			}
        			?> 
            
          </div>                   
        </div>
      </div>
    </section> 
    
    <!--================= contact section end =================-->
    <?php 
      genesis_widget_area(
        'home-page-4', array(
          'before' => '<section class="testimonial-section section-padding"><div class="container">',
          'after'  => '</div></section>',
        )
      );
    ?>    

  
<?php get_footer(); ?>