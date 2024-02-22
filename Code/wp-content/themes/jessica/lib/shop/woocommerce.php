<?php

/*
 * Jessica Child File
 *
 * This file calls the WooCommerce Specific Code
 *
 * @category     jessica
 * @package      Admin
 * @author       9seeds
 * @copyright    Copyright (c) 2018, 9seeds
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0.0

*/


// Explicitly declare WooCommerce Support
add_action( 'after_setup_theme', 'wsm_woocommerce_support' );
function wsm_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_theme_support( 'genesis-connect-woocommerce' );
// always display rating stars
function filter_woocommerce_product_get_rating_html( $rating_html, $rating, $count ) { 
    $rating_html  = '<div class="star-rating">';
    $rating_html .= wc_get_star_rating_html( $rating, $count );
    $rating_html .= '</div>';

    return $rating_html; 
};  
add_filter( 'woocommerce_product_get_rating_html', 'filter_woocommerce_product_get_rating_html', 10, 3 ); 
// ---------------------------------------------
// Remove Cross Sells From Default Position 
 remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
 add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
 add_filter( 'woocommerce_cross_sells_columns', 'bbloomer_change_cross_sells_columns' );
 
function bbloomer_change_cross_sells_columns( $columns ) {
return 3;
}
 
 

add_filter( 'woocommerce_upsell_display_args', 'custom_woocommerce_upsell_display_args' );  
add_filter( 'woocommerce_output_related_products_args', 'custom_woocommerce_upsell_display_args' );  
function custom_woocommerce_upsell_display_args( $args ) {  
      $args['columns']        = 3;
       return $args;  
}

// Change number or products per row to 3
add_filter( 'loop_shop_columns', 'wsm_woo_loop_columns' );
function wsm_woo_loop_columns() {
	return 3;
}

add_filter ( 'woocommerce_product_thumbnails_columns', 'wsm_woo_thumb_cols' );
function wsm_woo_thumb_cols() {
	return 3;
}

// Remove WooCommerce Related Commerce
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action('woocommerce_before_shop_loop_item_title' , 'wsm_add_demo_link_thumb',10 );
function wsm_add_demo_link_thumb() {
	echo '</a><div class="prod-content text-left">';
}


add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_short_description', 5);
function woocommerce_after_shop_loop_item_title_short_description() {


    global $product;
    $limit = 60;
    $wc_product = wc_get_product( $product );

    if ( ! $wc_product ) {
        return false;
    }
   
   $short_description = $wc_product->get_short_description();
   if (str_word_count($short_description, 0) > $limit) {
        $arr = str_word_count($short_description, 2);
        $pos = array_keys($arr);
        $short_description = substr($short_description, 0, $pos[$limit]) . '...';
        // $text = force_balance_tags($text); // may be you dont need this…
    }
    if ( '' !== $short_description ) {
        echo '<div class="blog-description">' . do_shortcode( wpautop( wptexturize( $short_description ) ) ) . '</div>';
    }  

}
function bbloomer_woocommerce_short_description_truncate_read_more() { 
   wc_enqueue_js('
      var show_char = 40;
      var ellipses = "... ";
      var content = $(".woocommerce-product-details__short-description").html();
      if (content.length > show_char) {
         var a = content.substr(0, show_char);
         var b = content.substr(show_char - content.length);
         var html = a + "<span class=\'truncated\'>" + ellipses + "<a class=\'read-more\'>Read more</a></span><span class=\'truncated\' style=\'display:none\'>" + b + "</span>";
         $(".woocommerce-product-details__short-description").html(html);
      }
      $(".read-more").click(function(e) {
         e.preventDefault();
         $(".woocommerce-product-details__short-description .truncated").toggle();
      });
   ');
}

add_action('woocommerce_after_shop_loop_item_title' , 'wsm_add_demo_link_icon',12 );
function wsm_add_demo_link_icon() {
    echo '<div class="product-icons">';
}

add_action('woocommerce_after_shop_loop' , 'wsm_add_demo_link_content',10 );
function wsm_add_demo_link_content() {
	echo '';
}


/*STEP 1 - REMOVE ADD TO CART BUTTON ON PRODUCT ARCHIVE (SHOP) */

function remove_loop_button(){
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');

/*STEP 2 -ADD NEW BUTTON THAT LINKS TO PRODUCT PAGE FOR EACH PRODUCT */

add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
function replace_add_to_cart() {
global $product;
$link = $product->get_permalink();
echo do_shortcode('<a href="'.$link.'" class="button cart"><i class="fas fa-shopping-cart"></i> Add To Cart</a>');
}
              // < 2.1
add_action( 'woocommerce_after_add_to_cart_button', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
  
function woo_custom_single_add_to_cart_text() {

    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}
// Reposition result count
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 5 );

// Display 6 products per page.
function jessica_loop_shop_per_page($cols){
	return 9;
}
add_filter( 'loop_shop_per_page', 'jessica_loop_shop_per_page', 20 );

//* Force full-width-content product layout setting
add_filter( 'genesis_site_layout', 'jessica_woo_product_layout' );
function jessica_woo_product_layout() {
	if ( is_singular( 'product' ) ) {
    	if( 'product' == get_post_type() ) {
        	return 'full-width-content';
    	}
	}
}

//  Reposition products star rating
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'woocommerce_my_single_title',10);

   function woocommerce_my_single_title() {
?>
            <h2 class="woocommerce-loop-product__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<?php
    }

add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );

/**
 * woo_custom_product_searchform
*/
function woo_custom_product_searchform( $form ) {

	if( version_compare( PARENT_THEME_VERSION, '2.2.0', '>=' ) && get_theme_support( 'genesis-accessibility', 'search-form' ) ) {

		$search_text = get_search_query() ? apply_filters( 'the_search_query', get_search_query() ) : apply_filters( 'genesis_search_text', __( 'Search Here', 'jessica' ) . ' &#x02026;' );

		$button_text = apply_filters( 'genesis_search_button_text', esc_attr__( 'Go', 'jessica' ) );

		$onfocus = "if ('" . esc_js( $search_text ) . "' === this.value) {this.value = '';}";
		$onblur  = "if ('' === this.value) {this.value = '" . esc_js( $search_text ) . "';}";

		//* Empty label, by default. Filterable.
		$label = apply_filters( 'genesis_search_form_label', '' );

		$value_or_placeholder = ( get_search_query() == '' ) ? 'placeholder' : 'value';

		$form  = sprintf( '<form %s>', genesis_attr( 'search-form' ) );

		if ( '' == $label )  {
			$label = apply_filters( 'genesis_search_text', __( 'Search Here', 'jessica' ) );
		}

		$form_id = uniqid( 'searchform-' );

		$form .= sprintf(
			'<div><meta itemprop="target" content="%s"/><label class="search-form-label screen-reader-text" for="%s">%s</label><input itemprop="query-input" type="search" name="s" id="%s" %s="%s" /><input type="submit" value="%s" /><input type="hidden" name="post_type" value="product" /></div></form>',
			home_url( '/?s={s}' ),
			esc_attr( $form_id ),
			esc_html( $label ),
			esc_attr( $form_id ),
			$value_or_placeholder,
			esc_attr( $search_text ),
			esc_attr( $button_text )
		);

	} else {

		$form = '<div class="search-container"><form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
			<div>
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search Here...', 'jessica' ) . '" />
				<input type="submit" id="searchsubmit" value="'. esc_attr__( 'GO', 'jessica' ) .'" />
				<input type="hidden" name="post_type" value="product" />
				</div>
		</form></div>';
	}

	return $form;

}

// Remove product ordering
add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_grid_list', 25 );
function woocommerce_grid_list(){
  echo '<div class="collection-view">
                    <ul>
                      <li class="grid-layout-view active"><i class="fas fa-th"></i></li>
                      <li class="list-layout-view"><i class="fas fa-list-ul"></i></li>
                    </ul>
          </div> ';
} 

 
add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {
    
    // Change In Stock Text
    if ( $_product->is_in_stock() ) {
        $availability['availability'] = __('<i class="fas fa-check-circle stock-availabel"></i> <span class="stock">In Stock</span>', 'woocommerce');
    }
    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = __('Availability: <span class="stock">Out Of Stock</span>', 'woocommerce');
    }
    return $availability;
}


add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
    function loop_columns() {
        return 2; // 3 products per row
    }
}



add_filter( 'loop_shop_per_page', 'bbloomer_redefine_products_per_page', 9999 );
 
function bbloomer_redefine_products_per_page( $per_page ) {
  $per_page = 12;
  return $per_page;
}



add_shortcode ('custom_mini_cart', 'custom_mini_cart' );
/**
 * Create Shortcode for WooCommerce Cart Menu Item
 */
function custom_mini_cart() { 
	echo '<div class="cart-block"><div class="d-flex"><div class="cart-icon"><i class="fas fa-shopping-basket"></i></div>';
    echo '<a href="'. wc_get_cart_url(). '" class="dropdown-back cart-head" > ';
    echo esc_html("Shopping Cart",'jessica');
        echo '<span class="cart-items-count count">';
            echo WC()->cart->get_cart_contents_count() . " items ";
        echo '<span>';
        echo WC()->cart->get_cart_subtotal();
        echo '</span></span>';
    echo '</a></div>';

    echo '<ul class="dropdown-menu-mini-cart">';
        echo '<li> <div class="widget_shopping_cart_content">';
                  woocommerce_mini_cart();	
            echo '</div></li></ul>';    
    echo '</div>';

}

// Customize the Search Box.
add_filter( 'get_product_search_form', 'custom_search_button_text__' );
function custom_search_button_text__() {

        $form = '<div class="search-container"><form class="search-form" role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
                                <label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>
                                <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search Terms', 'woocommerce' ) . '" />
                                <button type="submit" name="submit" value="'. esc_attr__( 'Search', 'woocommerce' ) .'" class="search-icon" aria-label="Search"><i class="fas fa-fw fa-search"></i></buton>
                                <input type="hidden" name="post_type" value="product" />
                </form></div>';
        return $form;   


}

/** Home Categories**/
add_shortcode('product_front_page_category', 'product_front_page_category');
function product_front_page_category() {
	ob_start();
    $catTerms = get_terms('product_cat', array('orderby' => 'ASC' , 'number' => '1'));
    foreach($catTerms as $catTerm) : ?>
    <?php $thumbnail_id = get_woocommerce_term_meta( $catTerm->term_id, 'thumbnail_id', true ); 
    $image = wp_get_attachment_url( $thumbnail_id );  ?>  
              <div class="col-lg-8 col-md-6 col-12">
                <div class="thumb">
                  <img src="<?php echo $image; ?>" alt="image">
                  <h5 class="category-name"><a href="<?php echo site_url();?>/product-category/<?php echo $catTerm->slug; ?>"><?php echo $catTerm->name; ?></a></h5>
                </div>
              </div>           
    <?php endforeach; ?>
<?php
return ob_get_clean();
}


add_shortcode('product_front_page_category_second', 'product_front_page_category_second');
function product_front_page_category_second() {
	ob_start(); ?>
    <div class="row justify-content-between  offercms-block-left">
    <?php if ( is_page_template('templates/tpl-home4.php') ) {  
      $catTerms_second = get_terms('product_cat', array('orderby' => 'ASC' , 'number' => '2' ,'offset' => '1'));
      foreach($catTerms_second as $catTerm_s) : ?>
      <?php $thumbnail_id = get_woocommerce_term_meta( $catTerm_s->term_id, 'thumbnail_id', true ); 
      $image_s = wp_get_attachment_url( $thumbnail_id );  ?>
                <div class="col-sm-6 col-12 ">
                  <div class="thumb">
                    <img src="<?php echo $image_s; ?>" alt="image">
                    <h5 class="category-name"><a href="<?php echo site_url();?>/product-category/<?php echo $catTerm_s->slug; ?>"><?php echo $catTerm_s->name; ?></a></h5>
                  </div>
                </div> 
      <?php endforeach; ?>      
      <?php } else { ?> 
      <?php
      $catTerms_second = get_terms('product_cat', array('orderby' => 'ASC' , 'number' => '3' ,'offset' => '1'));
      foreach($catTerms_second as $catTerm_s) : ?>
      <?php $thumbnail_id = get_woocommerce_term_meta( $catTerm_s->term_id, 'thumbnail_id', true ); 
      $image_s = wp_get_attachment_url( $thumbnail_id );  ?>
                <div class="col-lg-4 col-md-6 col-12">
                  <div class="thumb">
                    <img src="<?php echo $image_s; ?>" alt="image">
                    <h5 class="category-name"><a href="<?php echo site_url();?>/product-category/<?php echo $catTerm_s->slug; ?>"><?php echo $catTerm_s->name; ?></a></h5>
                  </div>
                </div> 
      <?php endforeach; ?>
    <?php } ?>
    </div> 
    <?php
return ob_get_clean();
}