<?php
/**
 * Modification of the Genesis Featured Page Widget
 * to add customizable text area option.
 *
 */

function jessica_register_banner_widget(){
	register_widget('WSM_Banner_Widget');
}

add_action( 'widgets_init', "jessica_register_banner_widget" );


class WSM_Banner_Widget extends WP_Widget {

	/**
	 * Constructor. Set the default widget options and create widget.
	 */
	function __construct() {
		$widget_ops = array( 'classname' => 'wsm-banner', 'description' => __ ('Displays banner slider backgrounds and customizable headline and Links', 'jessica' ) );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'wsm-banner-widget' );
		parent::__construct( 'wsm-banner-widget', __( 'Web Savvy - Banner Widget', 'jessica' ), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget($args, $instance) {
		extract($args);

		$instance = wp_parse_args( (array) $instance, array(
			'wsm-offertext' => '',
			'wsm-title' => '',
			'wsm-moretext1' => '',
			'wsm-morelink1' => '',
			'wsm-banner-text' => '',
			'wsm-img-url' => '',
		) );


		// WMPL
		/**
		 * Filter strings for WPML translation
     	 */
		$instance['wsm-offertext'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-offertext'], 'Widgets', 'Web Savvy - Banner Widget - Offer' );
     	$instance['wsm-title'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-title'], 'Widgets', 'Web Savvy - Banner Widget - Title' );
     	$instance['wsm-moretext1'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-moretext1'], 'Widgets', 'Web Savvy - Banner Widget - More Text' );
     	$instance['wsm-morelink1'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-morelink1'], 'Widgets', 'Web Savvy - Banner Widget - More URL' );
     	$instance['wsm-banner-text'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-banner-text'], 'Widgets', 'Web Savvy - Banner Widget - Custom Text' );
     	$instance['wsm-img-url'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-img-url'], 'Widgets', 'Web Savvy - Banner Widget - Image URL' );
     	// WPML

		echo $before_widget;
        ?>

        <div class="single-banner">
	        <div class="d-table">
	            <div class="d-table-cell">
	              <div class="banner-content-area has_bg_image" data-background="<?php echo esc_attr( $instance['wsm-img-url'] ); ?>">
	                  <div class="container">
	                      <div class="row">
	                          <div class="col-md-12">
	                              <div class="banner-content">
	                              	<?php
						 		        if (!empty( $instance['wsm-offertext'] ) ) {
												$offertext = wp_kses_post($instance['wsm-offertext']);
												echo '<div class="title_outer">'. $offertext . '</div>';
										}
										if (!empty( $instance['wsm-title'] ) ) {
												$title = wp_kses_post($instance['wsm-title']);
												echo '<h1 class="title">';
												echo $title;
												echo '</h1>';
										} 

										if (!empty( $instance['wsm-banner-text'] ) ) {
												$text = wp_kses_post($instance['wsm-banner-text']);
												echo '<p class="mt-3">'. $text . '</p>';
										}
						            
										if (!empty( $instance['wsm-moretext1'] ) ) {
										if (!empty( $instance['wsm-morelink1'] ) ) :
												echo '<span class="shopnow"><a href="'. esc_attr( $instance['wsm-morelink1'] ) . '" title="" class="tx-ctm-btn">'.esc_attr( $instance['wsm-moretext1'] ) .'</a></span>';
										else :

												echo '<span class="shopnow"><a href="#" title="" class="tx-ctm-btn">'.esc_attr( $instance['wsm-moretext1'] ) .'</a></span>';

										endif;  
									   }
									?>     
	                              </div>
	                          </div>
	                      </div>
	                  </div>
	              </div>  
	         </div>
	        </div> 
        </div> 

        <?php

		echo $after_widget;
		wp_reset_query();
	}

	/** Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update($new_instance, $old_instance) {
		$new_instance['wsm-offertext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-offertext']) ) );
		$new_instance['wsm-title'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-title']) ) );
		$new_instance['wsm-banner-text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-banner-text']) ) );
		$new_instance['wsm-moretext1'] = strip_tags( $new_instance['wsm-moretext1'] );
		$new_instance['wsm-morelink1'] = strip_tags( $new_instance['wsm-morelink1'] );
		$new_instance['wsm-img-url'] = strip_tags( $new_instance['wsm-img-url'] );

		// WMPL
		/**
		 * register strings for translation
     	 */
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - Offer', $new_instance['wsm-offertext'] );
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - Title', $new_instance['wsm-title'] );
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - Custom Text', $new_instance['wsm-banner-text'] );
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - More Text', $new_instance['wsm-moretext1'] );
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - More Link', $new_instance['wsm-morelink1'] );
	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - Banner Widget - Image URL', $new_instance['wsm-img-url'] );
	 	// WMPL

		return $new_instance;
	}

	/** Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form($instance) {

		$instance = wp_parse_args( (array)$instance, array(
			'wsm-offertext' => '',
			'wsm-title' => '',
			'wsm-banner-text' => '',
			'wsm-moretext1' => '',
			'wsm-morelink1' => '',
			'wsm-img-url' => '',
		) );

		$title = esc_attr($instance['wsm-title']);
			$text = esc_textarea($instance['wsm-banner-text']);



		echo '<p><label for="' . $this->get_field_id('wsm-offertext') . '">' . __( 'Offer Text:', 'jessica' ) . '</label>';
		echo '<input type="text" id="' . $this->get_field_id('wsm-offertext') . '" name="' . $this->get_field_name('wsm-offertext') . '" value="' . esc_attr( $instance['wsm-offertext'] ) . '" class="widefat" /></p>';

		echo '<p><label for="' . $this->get_field_id('wsm-title') . '">' . __( 'Title:', 'jessica' ) . '</label>';
		echo '<input type="text" id="' . $this->get_field_id('wsm-title') . '" name="' . $this->get_field_name('wsm-title') . '" value="' . $title . '" class="widefat" /></p>';


		echo '<p><label for="' . $this->get_field_id( 'wsm-banner-text' ) . '">' . __( 'Custom Text:', 'jessica' ) . '</label>';
		echo '<textarea class="widefat" rows="4" cols="20" id="' . $this->get_field_id( 'wsm-banner-text' ) . '" name="' . $this->get_field_name( 'wsm-banner-text' ) . '">' . $text . '</textarea></p>';

		echo '<p><label for="' . $this->get_field_id('wsm-moretext1') . '">' . __( 'More Text:', 'jessica' ) . '</label>';
		echo '<input type="text" id="' . $this->get_field_id('wsm-moretext1') . '" name="' . $this->get_field_name('wsm-moretext1') . '" value="' . esc_attr( $instance['wsm-moretext1'] ) . '" class="widefat" /></p>';

		echo '<p><label for="' . $this->get_field_id('wsm-morelink1') . '">' .__( 'More URL:', 'jessica' ) . '</label>';
		echo '<input type="text" id="' . $this->get_field_id('wsm-morelink1') . '" name="' . $this->get_field_name('wsm-morelink1') . '" value="' . esc_attr( $instance['wsm-morelink1'] ) . '" class="widefat" /></p>';

		echo '<p><label for="' . $this->get_field_id('wsm-img-url') . '">' .__( 'Image URL:', 'jessica' ) . '</label>';
		echo '<input type="text" id="' . $this->get_field_id('wsm-img-url') . '" name="' . $this->get_field_name('wsm-img-url') . '" value="' . esc_attr( $instance['wsm-img-url'] ) . '" class="widefat" /></p>';

	}

}