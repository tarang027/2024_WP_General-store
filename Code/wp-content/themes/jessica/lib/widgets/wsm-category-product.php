<?php
/**
 * Modification of the Genesis Featured Page Widget
 * to add customizable text area option.
 *
 */


function wsmCategoryProductWidget() {
    register_widget( 'wsm_category_product_widget' );
}
add_action( 'widgets_init', 'wsmCategoryProductWidget' );

class wsm_category_product_widget extends WP_Widget {

   function __construct() { 
        parent::__construct('wsm_category_product_widget', __('Web Savvy - Category Product Widget', 'jessica'), 
            array( 'description' => __( 'Your description', 'jessica' ), ) 
        );
    }

public function form( $instance ) {
    $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : 'Categories';
    $instance['postCats'] = !empty($instance['postCats']) ? explode(",",$instance['postCats']) : array();
    ?>

    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
        <input type="text" class="widfat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" style="width: 100%;" value="<?php echo $title; ?>"/>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'postCats' ); ?>"><?php _e( 'Select Categories you want to show:' ); ?></label><br />
        <?php $args = array(
                'post_type' => 'product',
                'taxonomy' => 'product_cat',
            );
            $terms = get_terms( $args );
        //print_r($terms);
         foreach( $terms as $id => $name ) { 
            $checked = "";
            if(in_array($name->name,$instance['postCats'])){
                $checked = "checked='checked'";
            }
        ?>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('postCats'); ?>" name="<?php echo $this->get_field_name('postCats[]'); ?>" value="<?php echo $name->name; ?>"  <?php echo $checked; ?>/>
            <label for="<?php echo $this->get_field_id('postCats'); ?>"><?php echo $name->name; ?></label><br />
        <?php } ?>
    </p>

    <?php

}

public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    $instance['postCats'] = !empty($new_instance['postCats']) ? implode(",",$new_instance['postCats']) : 0;
    return $instance;
}

public function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    $postCats = $instance[ 'postCats' ];
    $categories_list = explode(",", $postCats);

    echo $before_widget;

    if( $title ) {
        echo $before_title . $title . $after_title;
    }

    $args = array('post_type' => 'product','taxonomy' => 'product_cat');
    $terms = get_terms( $args );
    $i = 1;
    ?>
    <?php if ( is_page_template('templates/tpl-home4.php')  || is_page_template('templates/tpl-home2.php') ) {  ?>
  
    <div class="woocommerce">
            <div class="tabbable-line">                
                <ul class="nav nav-tabs">
                    <?php
                        foreach ($categories_list as $cat) {
                            foreach($terms as $term) {
                                if($cat === $term->name) { ?>    
                                   <li>
                                    <a href="#<?php echo $term->name; ?>" data-toggle="tab"><?php echo $term->name; ?></a>
                                  </li>                               
                                <?php }
                            }
                        }
                    ?>                                          
                </ul> 
            </div>  
            <div class="tab-content">

            <?php  foreach ($categories_list as $cat) {
                        foreach($terms as $term) { 
                         if($cat === $term->name) { ?>
                            <div id="<?php echo $term->name; ?>" class="tab-pane fade">
                                <div class="category-tab1-slider row justify-content-between">
                                    <ul class="products columns-3">
                                                    
                                            <?php
                                                $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => '3', 'orderby' =>'date','order' => 'DESC', 'product_cat' => $term->name );
                                                $loop = new WP_Query( $args );
                                                while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                                                
                                                    <li class="product product-single text-center">
                                                            <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                            <?php 
                                                                    echo $product->get_image('shop_catalog', array('class'=>'primary_image'));
                                                                    ?>
                                                                    
                                                            <?php if ( $product->is_on_sale() ) : ?>
                                                            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale', 'woocommerce' ) . '</span>', $post, $product ); ?>
                                                            <?php endif; ?>
                                                            </a>
                                                            <div class="prod-content text-left">
                                                                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                                                    <h2 class="woocommerce-loop-product__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                                                                      <span class="price">
                                                                                        <?php echo $product->get_price_html(); ?>
                                                                                      </span>
                                                                    <div class="product-icons">
                                                                        
                                                                        <?php $link = $product->get_permalink();
                                                                        echo do_shortcode('<a class="button cart" href="'.$link.'"><i class="fas fa-shopping-cart"></i></a>'); ?>
                                                                        <?php echo do_shortcode("[yith_quick_view]"); ?>
                                                                        <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                                                    </div>
                                                            </div>            
                                                        <?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
                                                        <div class="clearfix"></div>
                                                        <?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>
                                                    </li>

                                                <?php endwhile; ?>
                                            <?php wp_reset_query(); ?>
                                    </ul>   
                                </div>
                            </div>
            <?php } } } ?>
            </div>
    </div>
    <?php if ( is_page_template('templates/tpl-home4.php')) { ?>
    <span class="shopnow"><a class="tx-ctm-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>shop"><?php echo __('View More', 'jessica'); ?></a></span>  
    <?php } ?>
    <?php } else { ?> 


    <div class="woocommerce">
            <div class="tabbable-line">                
                <ul class="nav nav-tabs">
                    <?php
                        foreach ($categories_list as $cat) {
                            foreach($terms as $term) {
                                if($cat === $term->name) { ?>    
                                   <li>
                                    <a href="#<?php echo $term->name; ?>" data-toggle="tab"><?php echo $term->name; ?></a>
                                  </li>                               
                                <?php }
                            }
                        }
                    ?>                                          
                </ul> 
            </div>  
            <div class="tab-content">

            <?php  foreach ($categories_list as $cat) {
                        foreach($terms as $term) { 
                         if($cat === $term->name) { ?>
                            <div id="<?php echo $term->name; ?>" class="tab-pane fade">
                                <div class="category-tab1-slider row justify-content-between">
                                    <ul class="products columns-3">
                                                    
                                            <?php
                                                $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => '6', 'orderby' =>'date','order' => 'DESC', 'product_cat' => $term->name );
                                                $loop = new WP_Query( $args );
                                                while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                                                
                                                    <li class="product product-single text-center">
                                                            <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                            <?php 
                                                                    echo $product->get_image('shop_catalog', array('class'=>'primary_image'));
                                                                    ?>
                                                                    
                                                            <?php if ( $product->is_on_sale() ) : ?>
                                                            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale', 'woocommerce' ) . '</span>', $post, $product ); ?>
                                                            <?php endif; ?>
                                                            </a>
                                                            <div class="prod-content text-left">
                                                                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                                                    <h2 class="woocommerce-loop-product__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                                                                      <span class="price">
                                                                                        <?php echo $product->get_price_html(); ?>
                                                                                      </span>
                                                                    <div class="product-icons">
                                                                        
                                                                        <?php $link = $product->get_permalink();
                                                                        echo do_shortcode('<a class="button cart" href="'.$link.'"><i class="fas fa-shopping-cart"></i></a>'); ?>
                                                                        <?php echo do_shortcode("[yith_quick_view]"); ?>
                                                                        <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                                                    </div>
                                                            </div>            
                                                        <?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
                                                        <div class="clearfix"></div>
                                                        <?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>
                                                    </li>

                                                <?php endwhile; ?>
                                            <?php wp_reset_query(); ?>
                                    </ul>   
                                </div>
                            </div>
            <?php } } } ?>
            </div>
    </div>
        
    <?php } ?>      
    <?php
    echo $after_widget;
}
}