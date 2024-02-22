<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Admin {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );

		add_filter(
			'plugin_action_links_sctv-sales-countdown-timer/sctv-sales-countdown-timer.php',
			array(
				$this,
				'settings_link',
			)
		);

	}

	public function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=sales-countdown-timer" title="' . __( 'Settings',
				'sctv-sales-countdown-timer' ) . '">' . __( 'Settings', 'sctv-sales-countdown-timer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sctv-sales-countdown-timer' );
		load_textdomain( 'sctv-sales-countdown-timer',
			VI_SCT_SALES_COUNTDOWN_TIMER_LANGUAGES . "sctv-sales-countdown-timer-$locale.mo" );
		load_plugin_textdomain( 'sctv-sales-countdown-timer', false, VI_SCT_SALES_COUNTDOWN_TIMER_LANGUAGES );
	}

	public function init() {
		$this->load_plugin_textdomain();
	}

}