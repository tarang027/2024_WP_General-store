<?php
/*
 * Template Name: About Page
 *  The template for dispalying the page.
 *
 *  @package WordPress
 *  @subpackage illdy
 */
?>
<?php get_header(); ?>

   <!--================= about section start =================-->
 
              <?php 
              if ( have_posts() ) {
              while ( have_posts() ) : the_post();
        

                    the_content(); 
                 
                    ?> 
                      <?php 
                      endwhile;
              }

                    genesis_widget_area(
                      'about-video', array(
                        'before' => '',
                        'after'  => '',
                      )
                    );

?>
    <?php 
      genesis_widget_area(
        'home-page-4', array(
          'before' => '<section class="testimonial-section section-padding"><div class="container">',
          'after'  => '</div></section>',
        )
      );
    ?>    

  
<?php get_footer(); ?>