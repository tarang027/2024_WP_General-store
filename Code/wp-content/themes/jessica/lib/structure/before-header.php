<?php

/**
 * Header Functions
 *
 * This file controls the header display on the site to allow
 * social media icons in the header
 *
 * @category     Jessica
 * @package      Admin
 * @author       9seeds
 * @copyright    Copyright (c) 2018, 9seeds
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0.0
 *
 */

add_action('genesis_after_header', 'show_custom_title',30);

function show_custom_title($crumb){ 

global $wp_query, $page_title;
$page_id = empty($wp_query->queried_object_id) ? 0 : $wp_query->queried_object_id;
$page_title = get_the_title($page_id ); ?>
       <?php if(!is_front_page() && !is_page_template('templates/tpl-home2.php') && !is_page_template('templates/tpl-home3.php') && !is_page_template('templates/tpl-home4.php')) { ?>        	
	    <div class="container">
	      <div class="breadcrumb">
                <?php if(is_shop()) {
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". esc_html("Shop",'jessica') ."</span></li>
			          </ul>";  
                    
                   }  else if(is_search()){
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". esc_html("Search",'jessica') ."</span></li>
			          </ul>";                    
                                    
                   } else if(is_tag() || is_product_tag()){ 
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". single_tag_title( "", false ) ."</span></li>
			          </ul>";                     
                         
                   } else if(is_category() || is_product_category()){
                    $cat = get_the_category();  
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". single_cat_title("", false) ."</span></li>
			          </ul>";                   
                         
                   } else if(is_404() ) {
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". esc_html("404",'jessica') ."</span></li>
			          </ul>";  
                    
                   } else if(is_front_page()) {}
                   else { 
                    echo "<ul class='breadcrumb-nav'>
			            <li><a href=" . get_home_url() . "><i class='fas fa-home'></i></a></li>
			            <li><span>". esc_html($page_title) ."</span></li>
			          </ul>";                  
                  } ?>           
                  <?php if(is_home()){ 
                  }else{
                     ?>
                <?php } ?>                
	      </div>
	    </div>
<?php } }