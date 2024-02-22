<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Shortcode {
	protected $settings;
	protected $data;

	public function __construct() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		/*Register scripts*/
		add_action( 'init', array( $this, 'shortcode_init' ) );
	}

	public function shortcode_init() {
		add_shortcode( 'sales_countdown_timer', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'shortcode_enqueue_script' ), 99 );
	}

	public function shortcode_enqueue_script() {
		if (isset( $_GET['sctv_countdown_job'] ) ){
			global $wp;
			exit( wp_redirect(home_url($wp->request)) );
		}
		if ( WP_DEBUG ) {
			if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-style', 'registered' ) ) {
				wp_enqueue_style( 'woo-sctr-shortcode-countdown-style',
					VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.css',
					array(),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			}
			if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-script', 'registered' ) ) {
				wp_enqueue_script( 'woo-sctr-shortcode-countdown-script',
					VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.js',
					array( 'jquery' ),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			}
		} else {
			if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-style', 'registered' ) ) {
				wp_enqueue_style( 'woo-sctr-shortcode-countdown-style',
					VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.min.css',
					array(),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			}
			if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-script', 'registered' ) ) {
				wp_enqueue_script( 'woo-sctr-shortcode-countdown-script',
					VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.min.js',
					array( 'jquery' ),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			}
		}
	}


	public function register_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'type'                          => '',
			'id'                            => '',
			'product_id'                    => '',
			'name'                          => '',
			'active'                        => 1,
			'message'                       => '{countdown_timer}',
			'message_position'              => 'default',
			'sale_from_date'                => '',
			'sale_to_date'                  => 0,
			'sale_from_time'                => 0,
			'sale_to_time'                  => 0,
			'checkout_time_minute'          => 0,
			'checkout_time_second'          => 0,
			'checkout_to_time'              => 0,
			'time_separator'                => 'blank',
			'time_units'                    => '',
			'datetime_format'               => 2,
			'datetime_format_custom_date'   => '',
			'datetime_format_custom_hour'   => '',
			'datetime_format_custom_minute' => '',
			'datetime_format_custom_second' => '',
			'animation_style'               => 'default',
			'layout_style'                  => '',
			'display_type'                  => '',
			'resize_archive_page_enable'    => '',
			'checkout_inline'               => '',
			'sale_countdown_timer_id_t'     => '',
		),
			$atts ) );

		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		global $sale_countdown_timer_count;
		$sale_countdown_timer_count ++;
		$show_countdown_check    = true;
		$sale_countdown_timer_id = $sale_countdown_timer_id_t ? $sale_countdown_timer_id_t . '_' . $sale_countdown_timer_count : $sale_countdown_timer_count;
		$now                     = current_time( 'timestamp' );
		$text_before             = $text_after = '';
		$day                     = $hour = $minute = $second = '';
		$is_event                = '';
		switch ( $type ) {
			case 'checkout':
				if ( $id ) {
					$index  = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
					$active = $this->settings->get_params( 'checkout_countdown_enable' );
				}
				if ( ! $active ) {
					$show_countdown_check = false;
				}
				if ( ! $checkout_to_time || $checkout_to_time < $now ) {
					$show_countdown_check = false;
				} else {
					$end_time = (int) $checkout_to_time - $now;
					$text     = $message;
				}
				break;
			default:
				if ( $id ) {
					$index     = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
					$sale_from = $sale_to = 0;
					if ( $index >= 0 ) {
						if ( $type === 'product' ) {
							$is_event = 1;
							if ( $term_countdown = get_transient( 'sales_countdown_timer_params_product_' . $product_id ) ) {
								$sale_from = (int) $term_countdown['sale_countdown_fom'];
								$sale_to   = (int) $term_countdown['sale_countdown_to'];
								delete_transient( 'sales_countdown_timer_params_product_' . $product_id );
							}
						} else {
							$is_event = $this->settings->get_current_countdown( 'sale_countdown_loop_enable', $index );
							if ( $term_countdown = get_transient( 'sales_countdown_timer_params_' . $id ) ) {
								$sale_from = (int) $term_countdown['sale_countdown_fom'];
								$sale_to   = (int) $term_countdown['sale_countdown_to'];
								delete_transient( 'sales_countdown_timer_params_' . $id );
							}
						}
						if ( ! $sale_from && ! $sale_to ) {
							if ( ! $sale_from_date ) {
								$sale_from_date = $this->settings->get_current_countdown( 'sale_countdown_fom_date', $index );
							}
							if ( ! $sale_to_date ) {
								$sale_to_date = $this->settings->get_current_countdown( 'sale_countdown_to_date', $index );

							}
							if ( ! $sale_from_time ) {
								$sale_from_time = $this->settings->get_current_countdown( 'sale_countdown_fom_time', $index );
							}
							if ( ! $sale_to_time ) {
								$sale_to_time = $this->settings->get_current_countdown( 'sale_countdown_to_time', $index );
							}
							$sale_from_date = strtotime( $sale_from_date );
							$sale_to_date   = strtotime( $sale_to_date );
							$sale_from_time = woo_ctr_time( $sale_from_time );
							$sale_to_time   = woo_ctr_time( $sale_to_time );
							$sale_from      = $sale_from_date + $sale_from_time;
							$sale_to        = $sale_to_date + $sale_to_time;
						}
						$active                = $this->settings->get_current_countdown( 'sale_countdown_active', $index );
						$message               = $this->settings->get_current_countdown( 'sale_countdown_message', $index );
						$sale_upcoming         = $type === 'product' ? $this->settings->get_current_countdown( 'sale_countdown_upcoming_enable', $index ) : '';
						$sale_upcoming_message = $type === 'product' ? $this->settings->get_current_countdown( 'sale_countdown_upcoming_message', $index ) : '';
					}
				}
				if ( ! $active ) {
					$show_countdown_check = false;
				}


				if ( $sale_to < $sale_from ) {
					$show_countdown_check = false;
				}
				if ( $sale_from > $now ) {
					if ( $sale_upcoming ) {
						$end_time = $sale_from - $now;
						$text     = $sale_upcoming_message;
					} else {
						$show_countdown_check = false;
					}
				} else {
					if ( $sale_to < $now ) {
						$show_countdown_check = false;
					} else {
						$end_time = $sale_to - $now;
						$text     = $message;
					}
				}
		}
		if ( ! $show_countdown_check ) {
			return false;
		}
		if ( isset( $index ) && $index >= 0 ) {
			$message_position              = $this->settings->get_current_countdown( 'sale_countdown_message_position', $index );
			$time_units                    = $this->settings->get_current_countdown( 'sale_countdown_time_units', $index );
			$datetime_format               = $this->settings->get_current_countdown( 'sale_countdown_datetime_format', $index );
			$datetime_format_custom_date   = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_date', $index );
			$datetime_format_custom_hour   = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_hour', $index );
			$datetime_format_custom_minute = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_minute', $index );
			$datetime_format_custom_second = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_second', $index );

			$time_separator  = $this->settings->get_current_countdown( 'sale_countdown_time_separator', $index );
			$animation_style = $this->settings->get_current_countdown( 'sale_countdown_animation_style', $index );

			$layout_style = $this->settings->get_current_countdown( 'sale_countdown_layout', $index );
			$display_type = $this->settings->get_current_countdown( 'sale_countdown_display_type', $index );

			$template_1_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_1_time_unit_position', $index );

			$template_2_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_2_time_unit_position', $index );

			$template_4_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_4_time_unit_position', $index );

			$template_6_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_6_time_unit_position', $index );
			$template_6_value_cut_behind   = $this->settings->get_current_countdown( 'sale_countdown_template_6_value_cut_behind', $index );

			$template_7_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_7_time_unit_position', $index );
			$template_7_value_cut_behind   = $this->settings->get_current_countdown( 'sale_countdown_template_7_value_cut_behind', $index );
		} else {
			return false;
		}
		$end_time  = (int) $end_time - 1;
		$day_left  = (int) floor( $end_time / 86400 );
		$hour_left = (int) floor( ( $end_time - 86400 * $day_left ) / 3600 );
		$min_left  = (int) floor( ( $end_time - 86400 * $day_left - 3600 * $hour_left ) / 60 );
		$sec_left  = $end_time - 86400 * $day_left - 3600 * $hour_left - 60 * $min_left;
		$day_deg   = $day_left;
		$hour_deg  = $hour_left * 15;
		$min_deg   = $min_left * 6;
		$sec_deg   = ( $sec_left + 1 ) * 6;

		$time_units_arg = ( ! $time_units || $display_type === '7' || in_array( $type,
				array(
					'checkout',
					'product',
				) ) ) ? array() : explode( ',', $time_units );
		$unit_day_class = $unit_hour_class = $unit_minute_class = $unit_second_class = '';
		if ( $day_left === 0 ) {
			if ( empty( $time_units_arg ) ) {
				$time_units_arg = array( 'hour', 'minute', 'second' );
			} elseif ( array_search( 'day', $time_units_arg ) >= 0 ) {
				$t_day = array_search( 'day', $time_units_arg );
				unset( $time_units_arg[ $t_day ] );
			}
			if ( $hour_left === 0 ) {
				$t_hour = array_search( 'hour', $time_units_arg );
				if ( $t_hour >= 0 ) {
					unset( $time_units_arg[ $t_hour ] );
				}
			}
		}
		if ( count( $time_units_arg ) ) {
			if ( ! in_array( 'day', $time_units_arg ) ) {
				$hour_left      = $hour_left + 24 * $day_left;
				$unit_day_class .= ' woo-sctr-countdown-hidden';
			}
			if ( ! in_array( 'hour', $time_units_arg ) ) {
				$min_left        = $hour_left * 60 + $min_left;
				$unit_hour_class .= ' woo-sctr-countdown-hidden';
			}
			if ( ! in_array( 'minute', $time_units_arg ) ) {
				$sec_left          = $min_left * 60 + $sec_left;
				$unit_minute_class .= ' woo-sctr-countdown-hidden';
			}
			if ( ! in_array( 'second', $time_units_arg ) ) {
				$unit_second_class .= ' woo-sctr-countdown-hidden';
			}
		}


		$day_left_t  = zeroise( $day_left, 2 );
		$hour_left_t = zeroise( $hour_left, 2 );
		$min_left_t  = zeroise( $min_left, 2 );
		$sec_left_t  = zeroise( $sec_left, 2 );

		if ( $animation_style === 'default' ) {
			$sec_left_t = zeroise( $sec_left === 59 ? 0 : $sec_left + 1, 2 );
			if ( $sec_left === 59 ) {
				$min_left_t = zeroise( $min_left === 59 ? 0 : $min_left + 1, 2 );
			}
		}
		/*datetime format*/
		switch ( $datetime_format ) {
			case 1:
				$day    = esc_html__( 'days', 'sales-countdown-timer' );
				$hour   = esc_html__( 'hrs', 'sales-countdown-timer' );
				$minute = esc_html__( 'mins', 'sales-countdown-timer' );
				$second = esc_html__( 'secs', 'sales-countdown-timer' );
				break;
			case 2:
				$day    = esc_html__( 'days', 'sales-countdown-timer' );
				$hour   = esc_html__( 'hours', 'sales-countdown-timer' );
				$minute = esc_html__( 'minutes', 'sales-countdown-timer' );
				$second = esc_html__( 'seconds', 'sales-countdown-timer' );
				break;
			case 3:
				$day    = '';
				$hour   = '';
				$minute = '';
				$second = '';
				break;
			case 4:
				$day    = esc_html__( 'd', 'sales-countdown-timer' );
				$hour   = esc_html__( 'h', 'sales-countdown-timer' );
				$minute = esc_html__( 'm', 'sales-countdown-timer' );
				$second = esc_html__( 's', 'sales-countdown-timer' );
				break;
			case '#other':
				$day    = $datetime_format_custom_date;
				$hour   = $datetime_format_custom_hour;
				$minute = $datetime_format_custom_minute;
				$second = $datetime_format_custom_second;
				break;
			default:
		}

		if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-script', 'enqueued' ) ) {
			wp_enqueue_script( 'woo-sctr-shortcode-countdown-script' );
		}
		if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-style', 'enqueued' ) ) {
			wp_enqueue_style( 'woo-sctr-shortcode-countdown-style' );

		}

		switch ( $time_separator ) {
			case 'colon':
				$time_separator = ':';
				break;
			case 'comma':
				$time_separator = ',';
				break;
			case 'dot':
				$time_separator = '.';
				break;
			default:
				$time_separator = '';
		}

		if ( count( $time_units_arg ) !== 1 && $time_separator ) {
			$unit_day_class .= ' woo-sctr-countdown-unit-wrap-two';
			if ( empty( $time_units_arg ) || in_array( 'minute', $time_units_arg ) || in_array( 'second',
					$time_units_arg ) ) {
				$unit_hour_class .= ' woo-sctr-countdown-unit-wrap-two';
			}
			if ( empty( $time_units_arg ) || in_array( 'second', $time_units_arg ) ) {
				$unit_minute_class .= ' woo-sctr-countdown-unit-wrap-two';
			}
		}
		$countdown_layout_class = $countdown_template_class = '';
		if ( $checkout_inline ) {
			$countdown_layout_class .= ' woo-sctr-countdown-timer-layout-same-line';
		}
		if ( $message_position === 'inline_countdown' ) {
			$countdown_layout_class .= ' woo-sctr-countdown-timer-layout-inline';
		}
		$countdown_layout_class .= ' woo-sctr-layout-' . $layout_style;

		switch ( count( $time_units_arg ) ) {
			case 1:
				$countdown_template_class .= ' woo-sctr-shortcode-countdown-count-unit-grid-one';
				break;
			case 2:
				$countdown_template_class .= ' woo-sctr-shortcode-countdown-count-unit-grid-two';
				break;
			case 3:
				$countdown_template_class .= ' woo-sctr-shortcode-countdown-count-unit-grid-three';
				break;
			default:
				$countdown_template_class .= ' woo-sctr-shortcode-countdown-count-unit-grid-four';

		}
		$countdown_template_class .= ' woo-sctr-countdown-timer-' . $display_type . ' woo-sctr-shortcode-countdown-unit-animation-' . $animation_style;


		$css = '';

		switch ( $display_type ) {
			case '4':
				/*set circle fill*/
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-date-value-wrap .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $day_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-hour-value-wrap .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $hour_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-minute-value-wrap .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $min_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-second-value-wrap .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $sec_deg . 'deg);}';
				break;
			case '5':
				/*set circle fill*/
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $day_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $hour_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $min_deg . 'deg);}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap-' . $sale_countdown_timer_id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second .woo-sctr-value-bar {' . esc_attr__( 'transform: rotate(' ) . $sec_deg . 'deg);}';
				break;
		}
		wp_add_inline_style( 'woo-sctr-shortcode-countdown-style', $css );

		/*message*/
		$text = explode( '{countdown_timer}', $text );
		if ( count( $text ) >= 2 ) {
			$text_before = $text[0];
			$text_after  = $text[1];
		} else {
			return '';
		}
		$div_container_class = 'woo-sctr-shortcode-countdown-timer-wrap woo-sctr-shortcode-countdown-timer-wrap-' . trim( $sale_countdown_timer_id ) . ' woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id;
		$div_container_class .= $resize_archive_page_enable ? ' woo-sctr-shortcode-countdown-timer-wrap-loop' : '';
		$div_container_class .= $is_event ? ' woo-sctr-shortcode-countdown-timer-wrap-event' : '';
		ob_start();
		?>
        <div class="<?php esc_attr_e( $div_container_class ) ?>">
            <div class="woo-sctr-countdown-timer-layout <?php esc_attr_e( trim( $countdown_layout_class ) ) ?>">
				<?php
				switch ( $layout_style ) {
				case '1':
				if ( $text_before ) {
					?>
                    <div class="woo-sctr-countdown-timer-text-wrap woo-sctr-countdown-timer-text-before-wrap">
                        <span class="woo-sctr-countdown-timer-text-before"><?php echo $text_before; ?></span>
                    </div>
					<?php
				}
				?>
                <div class="woo-sctr-countdown-timer-wrap">
                    <input type="hidden" class="woo-sctr-countdown-end-time" value="<?php echo $end_time; ?>"
                           data-now="<?php echo $now; ?>"
                           data-sale_from="<?php echo isset( $sale_from ) ? $sale_from : $now ?>"
                           data-sale_to="<?php echo isset( $sale_to ) ? $sale_to : $checkout_to_time ?>">
					<?php
					break;
					}

					switch ( $display_type ) {
						case '1':
							?>
                            <div class="woo-sctr-countdown-timer <?php esc_attr_e( trim( $countdown_template_class ) ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                                <span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_1_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $day_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
                                        <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1"><?php echo $day_left > 0 ? zeroise( $day_left - 1,
		                                           2 ) : '00'; ?></span>
                                           <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo $day_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_1_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                                <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_1_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $hour_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
                                        <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1"><?php echo $hour_left > 0 ? zeroise( $hour_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo $hour_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_1_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                                <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_1_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $min_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
                                        <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1"><?php echo $min_left > 0 ? zeroise( $min_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo $min_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_1_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                                <span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_1_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $sec_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
                                        <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1"><?php echo $sec_left > 0 ? zeroise( $sec_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo $sec_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_1_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
                                </div>
                            </div>
							<?php
							break;
						case '2':
							?>
                            <div class="woo-sctr-countdown-timer <?php esc_attr_e( trim( $countdown_template_class ) ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                                <span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_2_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $day_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $day_left_t; ?></span>
                                        <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1"><?php echo $day_left > 0 ? zeroise( $day_left - 1,
		                                           2 ) : '00'; ?></span>
                                           <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo $day_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_2_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                                <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_2_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $hour_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $hour_left_t; ?></span>
                                        <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1"><?php echo $hour_left > 0 ? zeroise( $hour_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo $hour_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_2_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                                <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_2_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $min_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $min_left_t; ?></span>
                                        <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1"><?php echo $min_left > 0 ? zeroise( $min_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo $min_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_2_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                                <span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_2_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $sec_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $sec_left_t; ?></span>
                                        <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1"><?php echo $sec_left > 0 ? zeroise( $sec_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo $sec_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_2_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
                                </div>
                            </div>
							<?php
							break;
						case '3':
							?>
                            <div class="woo-sctr-countdown-timer <?php esc_attr_e( trim( $countdown_template_class ) ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                                <span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $day_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $day_left_t; ?></span>
                                        <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1"><?php echo $day_left > 0 ? zeroise( $day_left - 1,
		                                           2 ) : '00'; ?></span>
                                           <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo $day_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }

                                    ?>

                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text">
                                            <?php echo $day; ?>
                                        </span>
                                </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                                <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $hour_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $hour_left_t; ?></span>
                                        <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1"><?php echo $hour_left > 0 ? zeroise( $hour_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo $hour_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text">
                                            <?php echo $hour; ?>
                                        </span>
                                </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                                <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $min_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $min_left_t; ?></span>
                                        <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1"><?php echo $min_left > 0 ? zeroise( $min_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo $min_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text">
                                            <?php echo $minute; ?>
                                        </span>
                                </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                                <span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
                                           <?php echo $sec_left_t; ?>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
                                            <span class="woo-sctr-countdown-value-disabled"><?php echo $sec_left_t; ?></span>
                                        <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                           <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1"><?php echo $sec_left > 0 ? zeroise( $sec_left - 1,
		                                           2 ) : '59'; ?></span>
                                           <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo $sec_left_t; ?></span>
                                        </span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo $second; ?></span>
                                </span>
                                </div>
                            </div>
							<?php
							break;
						case '4':
							?>
                            <div class="woo-sctr-countdown-timer woo-sctr-countdown-timer-circle <?php esc_attr_e( trim( $countdown_template_class ) ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                                <span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_4_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $day_deg <= 180 ? '' : ' woo-sctr-over50' ?>">
                                            <span class="woo-sctr-countdown-value-circle woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $day_left_t; ?></span>
                                            <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $day_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $day_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $day_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
                                                <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1"><?php echo $day_left > 0 ? zeroise( $day_left - 1,
		                                                    2 ) : '00'; ?></span>
                                                    <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo $day_left_t; ?></span>
                                                </span>
                                            </span>
                                             <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $day_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $day_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_4_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $day; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                                <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_4_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $hour_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-value-circle woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $hour_left_t; ?></span>
                                            <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $hour_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $hour_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $hour_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
                                                <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1"><?php echo $hour_left > 0 ? zeroise( $hour_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo $hour_left_t; ?></span>
                                                </span>
                                            </span>
                                             <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $hour_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $hour_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_4_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $hour; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                                <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_4_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $min_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-value-circle woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $min_left_t; ?></span>
                                            <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $min_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $min_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $min_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
                                                <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1"><?php echo $min_left > 0 ? zeroise( $min_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo $min_left_t; ?></span>
                                                </span>
                                            </span>
                                             <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $min_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $min_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_4_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $minute; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                                <span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
                                    <?php
                                    if ( $template_4_time_unit_position === 'top' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $sec_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-value-circle woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $sec_left_t; ?></span>
                                            <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $sec_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $sec_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container <?php echo $sec_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
                                                <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1"><?php echo $sec_left > 0 ? zeroise( $sec_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo $sec_left_t; ?></span>
                                                </span>
                                            </span>
                                             <div class="woo-sctr-left-half-clipper">
                                                <div class="woo-sctr-first50-bar<?php echo $sec_deg <= 180 ? ' woo-sctr-countdown-hidden' : '' ?>"></div>
                                                <div class="woo-sctr-value-bar"
                                                     data-deg="<?php esc_attr_e( $sec_deg ); ?>"></div>
                                            </div>
                                        </span>
	                                    <?php
                                    }
                                    if ( $template_4_time_unit_position === 'bottom' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $second; ?>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                </span>
                                </div>
                            </div>
							<?php
							break;
						case '5':
							?>
                            <div class="woo-sctr-countdown-timer woo-sctr-countdown-timer-circle <?php esc_attr_e( trim( $countdown_template_class ) ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                                <span class="woo-sctr-countdown-date woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $day_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $day_left_t; ?></span>
                                            <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text"><?php echo $day; ?></span>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
                                                <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1"><?php echo $day_left > 0 ? zeroise( $day_left - 1,
		                                                    2 ) : '00'; ?></span>
                                                    <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo $day_left_t; ?></span>
                                                </span>
                                            </span>
                                            <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text"><?php echo $day; ?></span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                    <div class="woo-sctr-left-half-clipper">
                                        <div class="woo-sctr-first50-bar <?php echo $day_deg <= 180 ? 'woo-sctr-countdown-hidden' : ''; ?>"></div>
                                        <div class="woo-sctr-value-bar"
                                             data-deg="<?php esc_attr_e( $day_deg ); ?>"></div>
                                    </div>
                                </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                                <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $hour_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $hour_left_t; ?></span>
                                            <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo $hour; ?></span>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
                                                <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1"><?php echo $hour_left > 0 ? zeroise( $hour_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo $hour_left_t; ?></span>
                                                </span>
                                            </span>
                                            <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo $hour; ?></span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                    <div class="woo-sctr-left-half-clipper">
                                        <div class="woo-sctr-first50-bar <?php echo $hour_deg <= 180 ? 'woo-sctr-countdown-hidden' : '' ?>"></div>
                                        <div class="woo-sctr-value-bar"
                                             data-deg="<?php esc_attr_e( $hour_deg ); ?>"></div>
                                    </div>
                                </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                                <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $min_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $min_left_t; ?></span>
                                            <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text"><?php echo $minute; ?></span>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
                                                <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1"><?php echo $min_left > 0 ? zeroise( $min_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo $min_left_t; ?></span>
                                                </span>
                                            </span>
                                            <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text"><?php echo $minute; ?></span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                    <div class="woo-sctr-left-half-clipper">
                                        <div class="woo-sctr-first50-bar <?php echo $min_deg <= 180 ? 'woo-sctr-countdown-hidden' : '' ?>"></div>
                                        <div class="woo-sctr-value-bar"
                                             data-deg="<?php esc_attr_e( $min_deg ); ?>"></div>
                                    </div>
                                </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>
                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                                <span class="woo-sctr-countdown-second woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $sec_deg <= 180 ? '' : ' woo-sctr-over50'; ?>">
                                    <?php
                                    if ( $animation_style === 'default' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default"><?php echo $sec_left_t; ?></span>
                                            <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo $second; ?></span>
                                        </span>
	                                    <?php
                                    } elseif ( $animation_style === 'slide' ) {
	                                    ?>
                                        <span class="woo-sctr-countdown-circle">
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
                                                <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                                    <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1"><?php echo $sec_left > 0 ? zeroise( $sec_left - 1,
		                                                    2 ) : '59'; ?></span>
                                                    <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo $sec_left_t; ?></span>
                                                </span>
                                            </span>
                                            <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo $second; ?></span>
                                        </span>
	                                    <?php
                                    }
                                    ?>
                                    <div class="woo-sctr-left-half-clipper">
                                        <div class="woo-sctr-first50-bar <?php echo $sec_deg <= 180 ? 'woo-sctr-countdown-hidden' : ''; ?>"></div>
                                        <div class="woo-sctr-value-bar"
                                             data-deg="<?php esc_attr_e( $sec_deg ); ?>"></div>
                                    </div>
                                </span>
                                </div>
                            </div>
							<?php
							break;
						case '6':
							?>
                            <div class="woo-sctr-countdown-timer woo-sctr-countdown-timer-circle <?php esc_attr_e( $countdown_template_class ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( $unit_day_class ) ?>">
                        <span class="woo-sctr-countdown-date woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_6_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $day; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
                                    <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                          data-value="<?php esc_attr_e( $day_left_t ); ?>">
<!--                                        --><?php //echo $day_left_t; ?>
                                    </span>
                                    <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t; ?></span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
                                    <span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
                                        <span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $day_left > 0 ? zeroise( $day_left - 1, 2 ) : '00' ); ?>">
<!--                                                --><?php //echo $day_left > 0 ? zeroise( $day_left - 1, 2 ) : '00'; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1">
                                                <?php echo $day_left > 0 ? zeroise( $day_left - 1, 2 ) : '00'; ?>
                                            </span>
                                        </span>
                                        <span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $day_left_t ); ?>">
<!--                                                --><?php //echo $day_left_t; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2">
                                                <?php echo $day_left_t; ?>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-flip-card">
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top"
                                              data-value="<?php esc_attr_e( $day_left_t ); ?>">
<!--                                                --><?php //echo $day_left_t; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                              data-value="<?php echo zeroise( $day_left_t + 1, 2 ); ?>"></span>
                                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back"
                                              data-value="<?php echo zeroise( $day_left_t + 1, 2 ); ?>">
                                            <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $day_left_t; ?>"></span>
                                        </span>
                                    </span>

                                </span>
	                            <?php
                            }

                            if ( $template_6_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $day; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                        <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_6_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $hour; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
                                    <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                          data-value="<?php esc_attr_e( $hour_left_t ); ?>">
<!--                                        --><?php //echo $hour_left_t; ?>
                                    </span>
                                    <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t; ?></span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
                                    <span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
                                        <span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $hour_left > 0 ? zeroise( $hour_left - 1, 2 ) : '59' ); ?>">
<!--                                                --><?php //echo $hour_left > 0 ? zeroise( $hour_left - 1, 2 ) : '59'; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1">
                                                <?php echo $hour_left > 0 ? zeroise( $hour_left - 1, 2 ) : '59'; ?>
                                            </span>
                                        </span>
                                        <span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $hour_left_t ); ?>">
<!--                                                --><?php //echo $hour_left_t; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2">
                                                <?php echo $hour_left_t; ?>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-flip-card">
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top"
                                              data-value="<?php esc_attr_e( $hour_left_t ); ?>">
<!--                                                --><?php //echo $hour_left_t; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                              data-value="<?php echo zeroise( $hour_left_t + 1, 2 ); ?>"></span>
                                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back"
                                              data-value="<?php echo zeroise( $hour_left_t + 1, 2 ); ?>">
                                            <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $hour_left_t; ?>"></span>
                                        </span>
                                    </span>

                                </span>
	                            <?php
                            }

                            if ( $template_6_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $hour; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                        <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_6_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $minute; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
                                    <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                          data-value="<?php esc_attr_e( $min_left_t ); ?>">
<!--                                        --><?php //echo $min_left_t; ?>
                                    </span>
                                    <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t; ?></span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
                                    <span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
                                        <span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $min_left > 0 ? zeroise( $min_left - 1, 2 ) : '59' ); ?>">
<!--                                                --><?php //echo $min_left > 0 ? zeroise( $min_left - 1, 2 ) : '59'; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1">
                                                <?php echo $min_left > 0 ? zeroise( $min_left - 1, 2 ) : '59'; ?>
                                            </span>
                                        </span>
                                        <span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $min_left_t ); ?>">
<!--                                                --><?php //echo $min_left_t; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2">
                                                <?php echo $min_left_t; ?>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-flip-card">
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top"
                                              data-value="<?php esc_attr_e( $min_left_t ); ?>">
<!--                                                --><?php //echo $min_left_t; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                              data-value="<?php echo zeroise( $min_left_t + 1, 2 ); ?>"></span>
                                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back"
                                              data-value="<?php echo zeroise( $min_left_t + 1, 2 ); ?>">
                                            <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $min_left_t; ?>"></span>
                                        </span>
                                    </span>

                                </span>
	                            <?php
                            }

                            if ( $template_6_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $minute; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                        <span class="woo-sctr-countdown-second woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_6_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $second; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
                                    <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                          data-value="<?php esc_attr_e( $sec_left_t ); ?>">
<!--                                        --><?php //echo $sec_left_t; ?>
                                    </span>
                                    <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t; ?></span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
                                    <span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
                                        <span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $sec_left > 0 ? zeroise( $sec_left - 1, 2 ) : '59' ); ?>">
<!--                                                --><?php //echo $sec_left > 0 ? zeroise( $sec_left - 1, 2 ) : '59'; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1">
                                                <?php echo $sec_left > 0 ? zeroise( $sec_left - 1, 2 ) : '59'; ?>
                                            </span>
                                        </span>
                                        <span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
                                            <span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 <?php echo $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                  data-value="<?php esc_attr_e( $sec_left_t ); ?>">
<!--                                                --><?php //echo $sec_left_t; ?>
                                            </span>
                                            <span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2">
                                                <?php echo $sec_left_t; ?>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-flip-card">
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top"
                                              data-value="<?php esc_attr_e( $sec_left_t ); ?>">
<!--                                                --><?php //echo $sec_left_t; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                              data-value="<?php echo zeroise( $sec_left_t + 1, 2 ); ?>"></span>
                                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back"
                                              data-value="<?php echo zeroise( $sec_left_t + 1, 2 ); ?>">
                                            <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $sec_left_t ?>"></span>
                                        </span>
                                    </span>

                                </span>
	                            <?php
                            }

                            if ( $template_6_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $second; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>

                                </div>
                            </div>
							<?php
							break;
						case '7':
							$day_left_t_arg = str_split( $day_left_t );
							$hour_left_t_arg = str_split( $hour_left_t );
							$min_left_t_arg = str_split( $min_left_t );
							$sec_left_t_arg = str_split( $sec_left_t );
							list( $day_left_t1, $day_left_t2 ) = array_map( 'intval', $day_left_t_arg );
							list( $hour_left_t1, $hour_left_t2 ) = array_map( 'intval', $hour_left_t_arg );
							list( $min_left_t1, $min_left_t2 ) = array_map( 'intval', $min_left_t_arg );
							list( $sec_left_t1, $sec_left_t2 ) = array_map( 'intval', $sec_left_t_arg );
							?>
                            <div class="woo-sctr-countdown-timer woo-sctr-countdown-timer-circle <?php esc_attr_e( $countdown_template_class ) ?>">
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap <?php esc_attr_e( trim( $unit_day_class ) ) ?>">
                        <span class="woo-sctr-countdown-date woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_7_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $day; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $day_left_t1 ); ?>">
<!--                                            --><?php //echo $day_left_t1; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t1; ?></span>
                                    </span>
                                    <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $day_left_t2 ); ?>">
<!--                                            --><?php //echo $day_left_t2; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t2; ?></span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-date-1-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-date-1-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $day_left_t1 > 0 ? $day_left_t1 - 1 : 0 ); ?>">
<!--                                                     --><?php //echo $day_left_t1 > 0 ? $day_left_t1 - 1 : 0; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t1 > 0 ? $day_left_t1 - 1 : 0; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $day_left_t1 ); ?>">
<!--                                                     --><?php //echo $day_left_t1; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t1; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-date-2-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-date-2-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $day_left_t2 > 0 ? $day_left_t2 - 1 : 9 ); ?>">
<!--                                                     --><?php //echo $day_left_t2 > 0 ? $day_left_t2 - 1 : 9; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t2 > 0 ? $day_left_t2 - 1 : 9; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $day_left_t2 ); ?>">
<!--                                                     --><?php //echo $day_left_t2; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $day_left_t2; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-wrap-wrap woo-sctr-countdown-value-wrap-wrap <?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-date-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $day_left_t1 ); ?>"><?php //echo $day_left_t1; ?></span>
                                            <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $day_left_t1 < 9 ? $day_left_t1 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $day_left_t1 < 9 ? $day_left_t1 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $day_left_t1; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-date-2-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $day_left_t2 ); ?>"><?php //echo $day_left_t2; ?></span>
                                            <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $day_left_t2 < 9 ? $day_left_t2 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $day_left_t2 < 9 ? $day_left_t2 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $day_left_t2; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            }

                            if ( $template_7_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $day; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap <?php esc_attr_e( trim( $unit_hour_class ) ) ?>">
                        <span class="woo-sctr-countdown-hour woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_7_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $hour; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $hour_left_t1 ); ?>">
<!--                                            --><?php //echo $hour_left_t1; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t1; ?></span>
                                    </span>
                                    <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $hour_left_t2 ); ?>">
<!--                                            --><?php //echo $hour_left_t2; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t2; ?></span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-hour-1-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-hour-1-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $hour_left_t1 > 0 ? $hour_left_t1 - 1 : 5 ); ?>">
<!--                                                     --><?php //echo $hour_left_t1 > 0 ? $hour_left_t1 - 1 : 5; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t1 > 0 ? $hour_left_t1 - 1 : 5; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $hour_left_t1 ); ?>">
<!--                                                     --><?php //echo $hour_left_t1; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t1; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-hour-2-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-hour-2-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $hour_left_t2 > 0 ? $hour_left_t2 - 1 : 9 ); ?>">
<!--                                                     --><?php //echo $hour_left_t2 > 0 ? $hour_left_t2 - 1 : 9; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t2 > 0 ? $hour_left_t2 - 1 : 9; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $hour_left_t2 ); ?>">
<!--                                                     --><?php //echo $hour_left_t2; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $hour_left_t2; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-wrap-wrap woo-sctr-countdown-value-wrap-wrap <?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-hour-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $hour_left_t1 ); ?>"><?php //echo $hour_left_t1; ?></span>
                                            <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $hour_left_t1 < 5 ? $hour_left_t1 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $hour_left_t1 < 5 ? $hour_left_t1 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $hour_left_t1; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-hour-2-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $hour_left_t2 ); ?>"><?php //echo $hour_left_t2; ?></span>
                                            <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $hour_left_t2 < 9 ? $hour_left_t2 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $hour_left_t2 < 9 ? $hour_left_t2 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $hour_left_t2; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            }

                            if ( $template_7_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $hour; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap <?php esc_attr_e( trim( $unit_minute_class ) ) ?>">
                        <span class="woo-sctr-countdown-minute woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_7_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top">
                                            <?php echo $minute; ?>
                                        </span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $min_left_t1 ); ?>">
<!--                                            --><?php //echo $min_left_t1; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t1; ?></span>
                                    </span>
                                    <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $min_left_t2 ); ?>">
<!--                                            --><?php //echo $min_left_t2; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t2; ?></span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-minute-1-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-minute-1-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $min_left_t1 > 0 ? $min_left_t1 - 1 : 5 ); ?>">
<!--                                                     --><?php //echo $min_left_t1 > 0 ? $min_left_t1 - 1 : 5; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t1 > 0 ? $min_left_t1 - 1 : 5; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $min_left_t1 ); ?>">
<!--                                                     --><?php //echo $min_left_t1; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t1; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-minute-2-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-minute-2-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $min_left_t2 > 0 ? $min_left_t2 - 1 : 9 ); ?>">
<!--                                                     --><?php //echo $min_left_t2 > 0 ? $min_left_t2 - 1 : 9; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t2 > 0 ? $min_left_t2 - 1 : 9; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $min_left_t2 ); ?>">
<!--                                                     --><?php //echo $min_left_t2; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $min_left_t2; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-wrap-wrap woo-sctr-countdown-value-wrap-wrap <?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-minute-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $min_left_t1 ); ?>"><?php// echo $min_left_t1; ?></span>
                                            <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $min_left_t1 < 5 ? $min_left_t1 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $min_left_t1 < 5 ? $min_left_t1 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $min_left_t1; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-minute-2-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $min_left_t2 ); ?>"><?php// echo $min_left_t2; ?></span>
                                            <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $min_left_t2 < 9 ? $min_left_t2 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $min_left_t2 < 9 ? $min_left_t2 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $min_left_t2; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            }

                            if ( $template_7_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom">
                                            <?php echo $minute; ?>
                                        </span>
	                            <?php
                            }
                            ?>
                        </span>
									<?php
									if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
										?>
                                        <span class="woo-sctr-countdown-time-separator"><?php echo $time_separator; ?></span>
										<?php
									}
									?>

                                </div>
                                <div class="woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap <?php esc_attr_e( trim( $unit_second_class ) ) ?>">
                        <span class="woo-sctr-countdown-second woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
                            <?php
                            if ( $template_7_time_unit_position === 'top' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top"><?php echo $second; ?></span>
	                            <?php
                            }
                            if ( $animation_style === 'default' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
                                    <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $sec_left_t1 ); ?>">
<!--                                            --><?php //echo $sec_left_t1; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t1; ?></span>
                                    </span>
                                    <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                        <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                              data-value="<?php esc_attr_e( $sec_left_t2 ); ?>">
<!--                                            --><?php //echo $sec_left_t2; ?>
                                        </span>
                                        <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t2; ?></span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'slide' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap <?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-second-1-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-second-1-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $sec_left_t1 > 0 ? $sec_left_t1 - 1 : 5 ); ?>">
<!--                                                     --><?php //echo $sec_left_t1 > 0 ? $sec_left_t1 - 1 : 5; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t1 > 0 ? $sec_left_t1 - 1 : 5; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $sec_left_t1 ); ?>">
<!--                                                     --><?php //echo $sec_left_t1; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t1; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-second-2-wrap woo-sctr-countdown-value-wrap">
                                        <span class="woo-sctr-countdown-second-2-container woo-sctr-countdown-value-container">
                                            <span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $sec_left_t2 > 0 ? $sec_left_t2 - 1 : 9 ); ?>">
<!--                                                     --><?php //echo $sec_left_t2 > 0 ? $sec_left_t2 - 1 : 9; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t2 > 0 ? $sec_left_t2 - 1 : 9; ?></span>
                                            </span>
                                            <span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
                                                <span class="woo-sctr-countdown-two-vertical-top <?php echo $template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default'; ?>"
                                                      data-value="<?php esc_attr_e( $sec_left_t2 ); ?>">
<!--                                                     --><?php //echo $sec_left_t2; ?>
                                                </span>
                                                <span class="woo-sctr-countdown-two-vertical-bottom"><?php echo $sec_left_t2; ?></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            } elseif ( $animation_style === 'flip' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap <?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                                    <span class="woo-sctr-countdown-second-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $sec_left_t1 ); ?>"><?php //echo $sec_left_t1; ?></span>
                                            <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $sec_left_t1 < 5 ? $sec_left_t1 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $sec_left_t1 < 5 ? $sec_left_t1 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $sec_left_t1; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="woo-sctr-countdown-second-2-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
                                        <span class="woo-sctr-countdown-flip-card">
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top"
                                                  data-value="<?php esc_attr_e( $sec_left_t2 ); ?>"><?php //echo $sec_left_t2; ?></span>
                                            <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                                  data-value="<?php echo $sec_left_t2 < 9 ? $sec_left_t2 + 1 : 0; ?>"></span>
                                            <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back"
                                                  data-value="<?php echo $sec_left_t2 < 9 ? $sec_left_t2 + 1 : 0; ?>">
                                                <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom"
                                                      data-value="<?php echo $sec_left_t2; ?>"></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
	                            <?php
                            }

                            if ( $template_7_time_unit_position === 'bottom' ) {
	                            ?>
                                <span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom"><?php echo $second; ?></span>
	                            <?php
                            }
                            ?>
                        </span>

                                </div>
                            </div>
							<?php
							break;
					}
					switch ( $layout_style ) {
					case '1':
					?>
                </div>
			<?php
			if ( $text_after ) {
				?>
                <div class="woo-sctr-countdown-timer-text-wrap woo-sctr-countdown-timer-text-after-wrap">
                    <span class="woo-sctr-countdown-timer-text-after"><?php echo $text_after; ?></span>
                </div>
				<?php
			}
			break;
			}
			?>
            </div>
        </div>
		<?php

		$html = ob_get_clean();
		$html = str_replace( "\n", '', $html );
		$html = str_replace( "\r", '', $html );
		$html = str_replace( "\t", '', $html );
		$html = str_replace( "\l", '', $html );
		$html = str_replace( "\0", '', $html );

		return ent2ncr( $html );
	}

}