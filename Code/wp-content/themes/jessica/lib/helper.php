<?php
// add compatibilty for genesis_get_config
if(!function_exists("genesis_get_config")){
    function genesis_get_config( $config ) {

        $parent_file = sprintf( '%s/config/%s.php', get_template_directory(), $config );
        $child_file  = sprintf( '%s/config/%s.php', get_stylesheet_directory(), $config );
      
        $data = array();
      
        if ( is_readable( $child_file ) ) {
          $data = require $child_file;
        }
      
        if ( empty( $data ) && is_readable( $parent_file ) ) {
          $data = require $parent_file;
        }
      
        return (array) $data;
      
      }
}


/**
 * No Header Right widget area
 *
 * @since 1.0.0
 * @param array $widgets
 * @return array $widgets
 */
function seeds_starter_remove_header_right( $widgets ) {
	$widgets['header-right'] = array();
	return $widgets;
}

// get genesis style selecttion on genesis->theme setting
function jessica_get_style_selection(){
    return genesis_get_option( 'style_selection' );
}

// get default link color
function jessica_get_default_link_color(){
    return '#959595';
}

// get default accent color
function jessica_get_default_accent_color(){
    return '#f23276';
}

// get link color
function jessica_get_link_color(){
    return (get_theme_mod("jessica_link_color") != "") ? get_theme_mod("jessica_link_color") : jessica_get_default_link_color();
}

// get accent color
function jessica_get_accent_color(){
    return (get_theme_mod("jessica_accent_color") != "") ? get_theme_mod("jessica_accent_color") : jessica_get_default_accent_color();
}

function darken_color($rgb, $darker=2){
    // value darker between 1 to 4, can be decimal
    $hash = (strpos($rgb, '#') !== false) ? '#' : '';
    $rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
    if(strlen($rgb) != 6) return $hash.'000000';
    $darker = ($darker > 1) ? $darker : 1;

    list($R16,$G16,$B16) = str_split($rgb,2);

    $R = sprintf("%02X", floor(hexdec($R16)/$darker));
    $G = sprintf("%02X", floor(hexdec($G16)/$darker));
    $B = sprintf("%02X", floor(hexdec($B16)/$darker));

    return $hash.$R.$G.$B;
}

// render custom css
function jessica_get_custom_css(){
    $link_color = jessica_get_link_color();
    $link_hover_color = darken_color($link_color);
    $accent_color = jessica_get_accent_color();
    $accent_hover_color = darken_color($accent_color);
return <<<CSS
a,
.text-btn:hover,
.site-title::first-letter,
.menu-primary li.active > a,
.menu-primary li a:hover,
header .my-account a.user:hover,
header .cart-block a:hover,
header .my-account .dropdown li:hover,
.banner-content .title span,
.shopnow a:hover ,
.team-single:hover .content .name,
.tlp-team:hover h3,
.contact-item a:hover,
.breadcrumb-nav span,
.product-single:hover .content .name a,
.product-single .regular-price,
.wpsp-product-price,
.blog-pagination nav li .page-link.active ,.blog-pagination nav li .page-link:hover,
.blog-pagination nav li .page-link:hover a , .blog-pagination nav li .page-link.active,
.blog-pagination nav li .page-numbers.current, .blog-pagination nav li .page-numbers:hover,
.archive-pagination.pagination ul li.active a,
.archive-pagination.pagination ul li:hover a,
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current,
.blog-pagination nav li.page-arrow:hover span,
.archive-pagination.pagination ul li.pagination-next:hover a,
.archive-pagination.pagination ul li.pagination-prev:hover a,
.blog-detail-block:hover .title ,
.right-column article .entry-content .blog-btn:hover,
.blog-slide.blog-right .date,
.blog-slide.blog-right .date span,
.blog-slide .date,
.left-column li a:hover,
.left-blog-block:hover .left-blog-detail .title,
.tagcloud a:hover,
.size-selector ul li:hover,
.collection-view li:hover,
.collection-view li.active,
.category-banner .content span,
.category-banner .content .shopnow:hover,
.footer-widgets li:hover a,
.footer-widgets #menu-footer-menu li:hover a:before,
.footer-widgets li .address-icon,
.entry-title a:hover,
.footer-widgets .widget_tag_cloud .tagcloud a:hover,
.widget_tag_cloud .tagcloud a:hover,
body.woocommerce-page ul.products li.product h2.woocommerce-loop-product__title a:hover,
.woocommerce ul.products li.product .price ins,
.woocommerce ul.products li.product .price span,
body.woocommerce-page div.product p.price,
body.woocommerce-page #content div.product span.price,
body.woocommerce-page #content div.product p.price,
body.woocommerce-page div.product .summary .price span,
body.woocommerce-page .summary .product_meta > span,
.widget.woocommerce ul.product_list_widget li:hover span.product-title,
.widget.woocommerce ul.product_list_widget li span.woocommerce-Price-amount.amount,
.widget.woocommerce ul.product_list_widget li span.woocommerce-Price-amount.amount span ,
.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item:hover,
div.default_product_display a.additional_description_link:link,
div.default_product_display a.additional_description_link:visited,
table.list_productdisplay h2.prodtitle a:hover,
.entry-content .product_grid_display h2.prodtitle a:hover,
.product_grid_display h2.prodtitle a:hover,
.woocommerce-account .woocommerce-MyAccount-navigation ul > li a:hover,
div.shopping-cart-wrapper .shoppingcart table tbody td.product-name a:hover,
.nav-primary .genesis-nav-menu li:hover a span,
.nav-primary .genesis-nav-menu .sub-menu li:hover a span,
.home-mid li a:hover,
.content .entry-content .more-link a:hover,
.comment-header a:hover,
.comment-reply a.comment-reply-link:hover ,
.sidebar .widget_recent_comments a,
.breadcrumb a{
    color:$link_color;
}
.breadcrumb a:hover,
.sidebar li a:hover,
.entry-title a:hover,
.sidebar .widget-title a:hover,
.wpsc-breadcrumbs a:hover,
.content .entry-content .more-link a:hover,
a:hover,
.entry-header .entry-meta a:hover,
.sidebar .widget_recent_comments a:hover,
.archive-pagination a:hover,
.bottom-widgets li a:hover,
.widget_tag_cloud .tagcloud a:hover,
.footer-widgets, .footer-widgets a:hover,
.home-mid-nav .menu .menu-item a:hover{
    color: $link_hover_color;
}

.pace .pace-progress,
.header-banner ,
.cart-block .count,
.custom-slick-nav .slick-arrow:hover,
.banner-section .slick-arrow:hover,
.shopnow:hover:before ,
.team-social-link li a,
.tlp-team .layout4 .tpl-social a, .tlp-team .layout4 .tpl-social a:hover .fa,
.contact-item .icon-inner,
ul.slick-dots li.slick-active button,
.product-single .new,
.wpsf-product .new,
div.woocommerce span.onsale, body.woocommerce-page span.onsale,
.product-single .product-icons li:hover,
.right-column article .entry-content .blog-btn:hover:before,
.right-column .entry-header .entry-meta ,
.blog-slide  .date,
.faq-inner-pages .nav-tabs li:hover a,
.faq-inner-pages .nav-tabs li a.active,
.faq-inner-pages .nav-tabs li.active a.active,
.left-title::after,
.left-column .widget-title::after {
    background-color: $accent_color;
}

.tx-ctm-btn,
.scroll-to-top ,
div.woocommerce #respond input#submit.alt,
div.woocommerce a.button.alt,
div.woocommerce button.button.alt,
div.woocommerce input.button.alt,
div.woocommerce a.button,
body.woocommerce-page a.button,
body.woocommerce-page button.button,
body.woocommerce-page input.button,
body.woocommerce-page #respond input#submit,
body.woocommerce-page #content input.button,
body.woocommerce-page button.button.alt,
body.woocommerce-page input.button.alt,
body.woocommerce-page #respond input#submit.alt,
body.woocommerce-page #content input.button.alt,
body.woocommerce-page div.product form.cart .button,
body.woocommerce-page #content div.product form.cart .button,
.comment-form .submit {
    background-color: $accent_hover_color;
}


.form-control:focus,
li.menu_has_children:hover:after,
li.menu_has_children:focus:after,
header .my-account .dropdown li:hover,
.testimonial-slide  .client-thumb,
.faq-inner-pages .nav-tabs li:hover a,
.faq-inner-pages .nav-tabs li a.active,
.faq-inner-pages .nav-tabs li.active a.active,
.tagcloud a:hover,
.size-selector ul li:hover ,
.footer-widgets .widget_tag_cloud .tagcloud a:hover,
.widget_tag_cloud .tagcloud a:hover,
.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item:hover {
    border-color: $accent_color;
}

body.woocommerce-page .woocommerce-message:before, 
body.woocommerce-page .woocommerce-info:before, 
body.woocommerce-page .woocommerce-error:before, 
body.woocommerce-page .woocommerce-message:before, 
body.woocommerce-page .woocommerce-info:before{
    background-color:transparent;
}

.wp-block-button .has-background.has-link-color-background-color{
    background-color: $link_color;
}
.wp-block-button .has-background.has-link-color-background-color:hover{
    background-color: $link_hover_color;
}
.wp-block-button .has-text-color.has-link-color-color{
    color: $link_color
}

.wp-block-button .has-background.has-accent-color-background-color{
    background-color: $accent_color;
}
.wp-block-button .has-background.has-accent-color-background-color:hover{
    background-color: $accent_hover_color;
}
.wp-block-button .has-text-color.has-accent-color-color{
    color: $accent_color;
}

CSS;
}