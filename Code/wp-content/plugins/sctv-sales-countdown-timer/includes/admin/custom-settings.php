<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Custom_Settings {
	protected $settings;

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 40 );
		add_action( 'admin_init', array( $this, 'save_settings' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 999999 );
	}

	public function admin_menu() {
		add_submenu_page(
			'sales-countdown-timer',
			__( 'Custom CSS', 'sales-countdown-timer' ),
			__( 'Custom CSS', 'sales-countdown-timer' ),
			'manage_options',
			'sales-countdown-timer-custom-settings',
			array( $this, 'settings_callback' )
		);
	}

	public function settings_callback() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Custom settings', 'sales-countdown-timer' ); ?></h2>
            <div class="vi-ui raised">
                <form class="vi-ui form" method="post">
					<?php wp_nonce_field( 'woo_ctr_settings_custom_action_nonce', '_woo_ctr_settings_custom_nonce' ); ?>
                    <div class="vi-ui vi-ui-main top tabular attached menu">
                        <a class="item active" data-tab="custom_css">
							<?php esc_html_e( 'Custom css', 'sales-countdown-timer' ) ?>
                        </a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="custom_css">
                        <table class="form-table">
                            <tbody>
                            <tr valign="top">
								<?php
								$custom_css = $this->settings->get_params( 'custom_css' );
								?>
                                <th>
                                    <label for="woo-stcr-custom-css">
										<?php esc_html_e( 'Custom css', 'sales-countdown-timer' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <textarea name="custom_css" id="woo-stcr-custom-css" class="woo-stcr-custom-css"
                                              rows="10"><?php echo esc_textarea( $custom_css ) ?></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>
                        <button class="vi-ui button primary woo-ctr-settings-custom-btnsave"
                                name="woo_ctr_settings_custom_btnsave">
							<?php esc_html_e( 'Save', 'sales-countdown-timer' ) ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
		<?php
	}

	public function save_settings() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		global $woo_ctr_settings;
		if ( $page !== 'sales-countdown-timer-custom-settings' ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['woo_ctr_settings_custom_btnsave'] ) || ! isset( $_POST['_woo_ctr_settings_custom_nonce'] ) || ! wp_verify_nonce( $_POST['_woo_ctr_settings_custom_nonce'],
				'woo_ctr_settings_custom_action_nonce' ) ) {
			return;
		}
		$args               = array();
		$args['custom_css'] = isset( $_POST['custom_css'] ) ? wp_kses_post( stripslashes( $_POST['custom_css'] ) ) : '';
		$args               = wp_parse_args( $args, get_option( 'sales_countdown_timer_params', $woo_ctr_settings ) );
		$woo_ctr_settings   = $args;
		update_option( 'sales_countdown_timer_params', $args );
	}

	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page === 'sales-countdown-timer-custom-settings' ) {
			global $wp_scripts;
			if ( isset( $wp_scripts->registered['jquery-ui-accordion'] ) ) {
				unset( $wp_scripts->registered['jquery-ui-accordion'] );
				wp_dequeue_script( 'jquery-ui-accordion' );
			}
			if ( isset( $wp_scripts->registered['accordion'] ) ) {
				unset( $wp_scripts->registered['accordion'] );
				wp_dequeue_script( 'accordion' );
			}
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) ) {
					preg_match( '/^(\/wp-content\/plugins|\/wp-content\/themes)/i', $script->src, $result1 );
					if ( count( array_filter( $result1 ) ) ) {
						wp_dequeue_script( $script->handle );
					}
				} else {
					if ( $script->handle != 'query-monitor' ) {
						wp_dequeue_script( $script->handle );
					}
				}
			}
			wp_enqueue_style( 'vi-sct-checkout-report-button', VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'button.min.css' );
			wp_enqueue_style( 'vi-sct-checkout-report-form', VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'form.min.css' );
			wp_enqueue_style( 'vi-sct-checkout-report-menu', VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'menu.min.css' );
			wp_enqueue_style( 'vi-sct-checkout-report-segment', VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'segment.min.css' );
		}

	}
}