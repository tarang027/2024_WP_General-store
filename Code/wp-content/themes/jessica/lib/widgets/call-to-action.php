<?php
/**
 * Modification of the Genesis Featured Page Widget
 * to add customizable text area option.
 *
 */

function jessica_register_cta_widget(){
	register_widget('WSM_CTA_Widget');
}
add_action( 'widgets_init', "jessica_register_cta_widget" );


class WSM_CTA_Widget extends WP_Widget {

	/**
	 * Constructor. Set the default widget options and create widget.
	 */
	function __construct() {
		$widget_ops = array( 'classname' => 'wsm-cta', 'description' => __( 'Displays backgrounds and customizable headline and Link', 'jessica' ) );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'wsm-cta-widget' );
		parent::__construct( 'wsm-cta-widget', __( 'Web Savvy - CTA Widget', 'jessica' ), $widget_ops, $control_ops );
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
			'wsm-icon' => '',
			'wsm-title' => '',
			'wsm-descriptiontext' => '',

		) );

		// WMPL
		/**
		 * Filter strings for WPML translation
     	 */
     	$instance['wsm-icon'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-icon'], 'Widgets', 'Web Savvy - CTA Widget - Icon' );
     	$instance['wsm-title'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-title'], 'Widgets', 'Web Savvy - CTA Widget - Title' );
     	$instance['wsm-descriptiontext'] = apply_filters( 'wpml_translate_single_string', $instance['wsm-descriptiontext'], 'Widgets', 'Web Savvy - CTA Widget - Description Text' );
     	// WPML

		echo '<div class="col-md-4 col-12 contact-item pt-3 pb-3 pl-5 pr-5 text-center">';
		echo $before_widget;

			// Set up the CTA

					
						if (!empty( $instance['wsm-icon'] ) ) {
						$icon = wp_kses_post($instance['wsm-icon']);
							echo '<div class="icon"><div class="icon-inner">';
							echo $icon ;
							echo '</div></div>';
						}
						echo '<div class="content mt-3">';
						if (!empty( $instance['wsm-title'] ) ) {
						$title = wp_kses_post($instance['wsm-title']);
							echo '<h6 class="title mb-2">';
							echo $title ;
							echo '</h6>';
						}
	               

		                if (!empty( $instance['wsm-descriptiontext'] ) ) {
		                	$descriptiontext = wp_kses_post($instance['wsm-descriptiontext']);
							echo $descriptiontext;
		                }
						echo '</div>';
		echo $after_widget;

        echo '</div>';
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

		$new_instance['wsm-icon'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-icon']) ) );

		$new_instance['wsm-title'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-title']) ) );

		$new_instance['wsm-descriptiontext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['wsm-descriptiontext']) ) ); 
		// WMPL
		/**
		 * register strings for translation
     	 */

	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - CTA Widget - Icon', $new_instance['wsm-icon'] );

	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - CTA Widget - Title', $new_instance['wsm-title'] );

	 	do_action( 'wpml_register_single_string', 'Widgets', 'Web Savvy - CTA Widget - Description Text', $new_instance['wsm-descriptiontext'] );
	 	// WMPL

		return $new_instance;
	}

	/** Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form($instance) {

		$instance = wp_parse_args( (array)$instance, array(

			'wsm-icon' => '',
			'wsm-title' => '',
			'wsm-descriptiontext' => '',

		) );

		$icon = esc_attr($instance['wsm-icon']);
		$title = esc_attr($instance['wsm-title']);
		$descriptiontext= esc_textarea($instance['wsm-descriptiontext']);

?>

		<p><label for="<?php echo $this->get_field_id('wsm-icon'); ?>"><?php _e( 'Icon ', 'jessica' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('wsm-icon'); ?>" name="<?php echo $this->get_field_name('wsm-icon'); ?>" value="<?php echo $icon; ?>" class="widefat" /></p>
		<p><label for="<?php echo $this->get_field_id('wsm-title'); ?>"><?php _e( 'Title ', 'jessica' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('wsm-title'); ?>" name="<?php echo $this->get_field_name('wsm-title'); ?>" value="<?php echo $title; ?>" class="widefat" /></p>

		<p><label for="<?php echo $this->get_field_id('wsm-descriptiontext'); ?>"><?php _e( 'Description Text ', 'jessica' ); ?></label>
			<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('wsm-descriptiontext'); ?>" name="<?php echo $this->get_field_name('wsm-descriptiontext'); ?>"><?php echo $descriptiontext; ?></textarea></p>

	<?php
	}
}