<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Frontend_Countdown
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Frontend_Countdown {
	protected $settings;

	public function __construct() {
		add_action( 'wp_print_styles', array( $this, 'sctv_countdown_css' ) );
	}

	public function sctv_countdown_css() {
		$css = VI_SCT_SALES_COUNTDOWN_TIMER_Countdown_Style::get_frontend_countdown_css();
		if ( $css ) {
			echo '<style id="woo-sctr-frontend-countdown-style" type="text/css">' . $css . '</style>';
		}
	}
}