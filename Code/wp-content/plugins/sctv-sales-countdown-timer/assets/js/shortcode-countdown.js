'use strict';
let count = 0, has_variation, variation_form;
let vi_sctr_x;
vi_sctr_x = setInterval(function () {
    count++;
}, 1000);
jQuery(document).ready(function () {
//sales countdown timer
    sctv_run_countdown();
    if (jQuery(document.body).find('.woo-sctr-value-bar').length) {
        jQuery(document.body).find('.woo-sctr-value-bar').each(function (k, div) {
            jQuery(div).css({transform: ('rotate(' + jQuery(div).attr('data-deg') + 'deg)')});
        });
    }
    if (jQuery(document.body).find('.woo-sctr-progress-bar-fill').length) {
        jQuery(document.body).find('.woo-sctr-progress-bar-fill').each(function (k, div) {
            jQuery(div).css({width: (jQuery(div).attr('data-width') + '%')});
        });
    }

    if (jQuery('body').find('.single_variation_wrap').length) {
        variation_form = jQuery('body').find('.single_variation_wrap').closest('form');
        has_variation = true;
    } else if (jQuery('body').find('.variations_form').length) {
        variation_form = jQuery('body').find('.variations_form').closest('form');
        has_variation = true;
    } else {
        has_variation = false;
    }
    if (has_variation && variation_form) {
        if (variation_form.data('product_variations')) {
            variation_form.on("show_variation", function (event, variation) {
                if (jQuery('.woo-sctr-single-product-container').length) {
                    jQuery('.woo-sctr-single-product-container').addClass('woo-sctr-countdown-hidden');
                }
                sctv_run_countdown('variation', count);
            }).on('hide_variation', function () {
                if (jQuery('.woo-sctr-single-product-container.woo-sctr-countdown-hidden').length) {
                    jQuery('.woo-sctr-single-product-container').removeClass('woo-sctr-countdown-hidden');
                }
            });
        } else {
            variation_form.on('hide_variation', function () {
                if (jQuery('.woo-sctr-single-product-container.woo-sctr-countdown-hidden').length) {
                    jQuery('.woo-sctr-single-product-container').removeClass('woo-sctr-countdown-hidden');
                }
            });
            jQuery(document).ajaxComplete(function (event, jqxhr, settings) {
                var ajax_link = settings.url;
                var data_post = settings.data;
                if (data_post == '' || data_post == null || jQuery.isEmptyObject(data_post) || _.isEmpty(data_post)) {
                    return;
                }
                if (ajax_link.search(/wc-ajax=get_variation/i) >= 0) {
                    if (jQuery('.woo-sctr-single-product-container').length) {
                        jQuery('.woo-sctr-single-product-container').addClass('woo-sctr-countdown-hidden');
                    }
                    sctv_run_countdown('variation', 0);
                }
            });
        }
    }
});
// jQuery(window).load(function () {
//     sctv_run_countdown();
// });

function sctv_run_countdown(type = '', time = 0) {
    // console.log('count: '+count+' type:'+type +' time: '+ time);
    clearInterval(vi_sctr_x);
    let distance, date, hours, minutes, seconds, i;
    let dates_deg, hours_deg, minutes_deg, seconds_deg;
    // Update the count down every 1 second
    let wooCountdown = jQuery('.woo-sctr-shortcode-countdown-timer-wrap');
    let time_end_parent = wooCountdown.find('.woo-sctr-countdown-end-time');

    if (jQuery(document.body).find('.woo-sctr-progress-bar-fill').length) {
        jQuery(document.body).find('.woo-sctr-progress-bar-fill').each(function (k, div) {
            jQuery(div).css({width: (jQuery(div).attr('data-width') + '%')});
        });
    }
    distance = time_end_parent.map(function () {
        // console.log(jQuery(this).closest('.woo-sctr-shortcode-countdown-timer-wrap').attr('class') );
        // console.log('type:'+type +' count: '+ count +' time: '+ time +' a:' + parseInt(jQuery(this).val())  );
        if ((jQuery(this).closest('.single_variation_wrap').length || jQuery(this).closest('.variations_form').length) && type === 'variation') {
            // console.log('type:'+type +' count: '+ count +' time: '+ time +' a:' +parseInt(jQuery(this).val())  );
            let variation_date, variation_hours, variation_minutes, variation_seconds,
                variation_date_t, variation_hours_t, variation_minutes_t, variation_seconds_t,
                variation_date_deg, variation_hours_deg, variation_minutes_deg, variation_seconds_deg,
                time_expire,
                variation_container = jQuery(this).parent().find('.woo-sctr-countdown-timer'),
                variation_date_container = variation_container.find('.woo-sctr-countdown-date-wrap'),
                variation_hour_container = variation_container.find('.woo-sctr-countdown-hour-wrap'),
                variation_minute_container = variation_container.find('.woo-sctr-countdown-minute-wrap'),
                variation_second_container = variation_container.find('.woo-sctr-countdown-second-wrap');
            if (time > 0) {
                time_expire = parseInt(jQuery(this).val()) - time;
            } else {
                let time_sale_to = parseInt(jQuery(this).data('sale_to')),
                    time_sale_from = parseInt(jQuery(this).data('sale_from')),
                    time_now = parseInt(jQuery(this).data('now'));
                if (time_sale_from > time_now) {
                    time_expire = time_sale_from - time_now;
                } else if (time_sale_to > time_now) {
                    time_expire = time_sale_to - time_now;
                }
                if (time_expire) {
                    jQuery(this).val(time_expire);
                } else {
                    time_expire = parseInt(jQuery(this).val());
                }
            }
            // console.log('type:'+type +' count: '+ count +' time: '+ time +' time_expire:' +time_expire+' a:' +parseInt(jQuery(this).val())  );
            variation_date = Math.floor(time_expire / 86400);
            variation_hours = Math.floor((time_expire % (86400)) / (3600));
            variation_minutes = Math.floor((time_expire % (3600)) / (60));
            variation_seconds = Math.floor((time_expire % (60)));
            variation_date_t = variation_date < 10 ? ("0" + variation_date).slice(-2) : variation_date.toString();
            variation_hours_t = variation_hours < 10 ? ("0" + variation_hours).slice(-2) : variation_hours.toString();
            variation_minutes_t = variation_minutes < 10 ? ("0" + variation_minutes).slice(-2) : variation_minutes.toString();
            variation_seconds_t = variation_seconds < 10 ? ("0" + variation_seconds).slice(-2) : variation_seconds.toString();
            // console.log('date:' +variation_date +'hour: '+variation_hours + 'minute: '+ variation_minutes + 'seconds: '+ variation_seconds);
            if (!variation_container.hasClass('woo-sctr-countdown-timer-7')) {
                if (variation_container.hasClass('woo-sctr-countdown-timer-circle')) {
                    variation_seconds_deg = (variation_seconds > 0 ? variation_seconds : 59) * 6;
                    if (variation_seconds_deg < 180) {
                        variation_second_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                        variation_second_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                        variation_second_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                    } else {
                        variation_second_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                        variation_second_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                        variation_second_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                    }
                    variation_second_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + variation_seconds_deg + 'deg)'});
                    variation_minutes_deg = (variation_minutes > 0 ? (variation_minutes - 1) : 59) * 6;
                    if (variation_minutes_deg < 180) {
                        variation_minute_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                        variation_minute_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                        variation_minute_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                    } else {
                        variation_minute_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                        variation_minute_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                        variation_minute_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                    }
                    variation_minute_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + variation_minutes_deg + 'deg)'});
                    variation_hours_deg = (variation_hours > 0 ? (variation_hours - 1) : 23) * 15;
                    if (variation_hours_deg < 180) {
                        variation_hour_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                        variation_hour_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                        variation_hour_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                    } else {
                        variation_hour_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                        variation_hour_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                        variation_hour_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                    }
                    variation_hour_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + variation_hours_deg + 'deg)'});
                    variation_date_deg = variation_date > 0 ? (variation_date - 1) : 0;
                    if (variation_date_deg < 180) {
                        variation_date_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                        variation_date_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                        variation_date_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                    } else {
                        variation_date_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                        variation_date_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                        variation_date_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                    }
                    variation_date_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + variation_date_deg + 'deg)'});
                }
                if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-default')) {
                    if (variation_container.hasClass('woo-sctr-countdown-timer-6')) {
                        variation_second_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_seconds_t);
                        variation_second_container.find('.woo-sctr-countdown-two-vertical-bottom').html(variation_seconds_t);

                        variation_minute_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_minutes_t);
                        variation_minute_container.find('.woo-sctr-countdown-two-vertical-bottom').html(variation_minutes_t);

                        variation_hour_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_hours_t);
                        variation_hour_container.find('.woo-sctr-countdown-two-vertical-bottom').html(variation_hours_t);

                        variation_date_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_date_t);
                        variation_date_container.find('.woo-sctr-countdown-two-vertical-bottom').html(variation_date_t);
                    } else {
                        variation_second_container.find('.woo-sctr-countdown-second-value').html(variation_seconds_t);
                        variation_minute_container.find('.woo-sctr-countdown-minute-value').html(variation_minutes_t);
                        variation_hour_container.find('.woo-sctr-countdown-hour-value').html(variation_hours_t);
                        variation_date_container.find('.woo-sctr-countdown-date-value').html(variation_date_t);
                    }
                } else if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-slide')) {
                    let variation_date_2, variation_hours_2, variation_minutes_2, variation_seconds_2,
                        variation_date_2_t, variation_hours_2_t, variation_minutes_2_t, variation_seconds_2_t;
                    variation_date_2 = (variation_date > 0) ? variation_date - 1 : 0;
                    variation_hours_2 = (variation_hours > 0) ? variation_hours - 1 : 59;
                    variation_minutes_2 = (variation_minutes > 0) ? variation_minutes - 1 : 59;
                    variation_seconds_2 = (variation_seconds > 0) ? variation_seconds - 1 : 59;
                    variation_date_2_t = variation_date_2 < 10 ? ("0" + variation_date_2).slice(-2) : variation_date_2.toString();
                    variation_hours_2_t = variation_hours_2 < 10 ? ("0" + variation_hours_2).slice(-2) : variation_hours_2.toString();
                    variation_minutes_2_t = variation_minutes_2 < 10 ? ("0" + variation_minutes_2).slice(-2) : variation_minutes_2.toString();
                    variation_seconds_2_t = variation_seconds_2 < 10 ? ("0" + variation_seconds_2).slice(-2) : variation_seconds_2.toString();
                    if (variation_container.hasClass('woo-sctr-countdown-timer-6')) {
                        variation_second_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_seconds_2_t);
                        variation_second_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(variation_seconds_2_t);
                        variation_second_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_seconds_t);
                        variation_second_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(variation_seconds_t);

                        variation_minute_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_minutes_2_t);
                        variation_minute_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(variation_minutes_2_t);
                        variation_minute_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_minutes_t);
                        variation_minute_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(variation_minutes_t);

                        variation_hour_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_hours_2_t);
                        variation_hour_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(variation_hours_2_t);
                        variation_hour_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_hours_t);
                        variation_hour_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(variation_hours_t);

                        variation_date_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_date_2_t);
                        variation_date_container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(variation_date_2_t);
                        variation_date_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', variation_hours_t);
                        variation_date_container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(variation_hours_t);
                    } else {
                        variation_second_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-1').html(variation_seconds_2_t);
                        variation_minute_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-1').html(variation_minutes_2_t);
                        variation_hour_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-1').html(variation_hours_2_t);
                        variation_date_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-1').html(variation_date_2_t);

                        variation_second_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-2').html(variation_seconds_t);
                        variation_minute_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-2').html(variation_minutes_t);
                        variation_hour_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-2').html(variation_hours_t);
                        variation_date_container.find('.woo-sctr-countdown-value-container .woo-sctr-countdown-value-2').html(variation_date_t);
                    }
                } else if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-flip')) {
                    variation_second_container.find('.woo-sctr-countdown-flip-top').attr('data-value', variation_seconds_t);
                    variation_second_container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', variation_seconds_t);

                    variation_minute_container.find('.woo-sctr-countdown-flip-top').attr('data-value', variation_minutes_t);
                    variation_minute_container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', variation_minutes_t);

                    variation_hour_container.find('.woo-sctr-countdown-flip-top').attr('data-value', variation_hours_t);
                    variation_hour_container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', variation_hours_t);

                    variation_date_container.find('.woo-sctr-countdown-flip-top').attr('data-value', variation_date_t);
                    variation_date_container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', variation_date_t);

                }
            } else {
                let sec_left_arg, min_left_arg, hour_left_arg, date_left_arg;
                sec_left_arg = variation_seconds_t.split('');
                min_left_arg = variation_minutes_t.split('');
                hour_left_arg = variation_hours_t.split('');
                date_left_arg = variation_date_t.split('');
                if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-default')) {
                    variation_second_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[0]);
                    variation_second_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[0]);
                    variation_second_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[1]);
                    variation_second_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[1]);

                    variation_minute_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[1]);
                    variation_minute_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[1]);

                    variation_hour_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[1]);
                    variation_hour_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[1]);

                    variation_date_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[1]);
                    variation_date_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[1]);
                } else if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-slide')) {
                    variation_second_container.find('.woo-sctr-countdown-second-1-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[0] > 0 ? sec_left_arg[0] - 1 : 5);
                    variation_second_container.find('.woo-sctr-countdown-second-1-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[0] > 0 ? sec_left_arg[0] - 1 : 5);
                    variation_second_container.find('.woo-sctr-countdown-second-1-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[0]);
                    variation_second_container.find('.woo-sctr-countdown-second-1-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[0]);

                    variation_second_container.find('.woo-sctr-countdown-second-2-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[1] > 0 ? sec_left_arg[1] - 1 : 9);
                    variation_second_container.find('.woo-sctr-countdown-second-2-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[1] > 0 ? sec_left_arg[1] - 1 : 9);
                    variation_second_container.find('.woo-sctr-countdown-second-2-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[1]);
                    variation_second_container.find('.woo-sctr-countdown-second-2-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[1]);

                    variation_minute_container.find('.woo-sctr-countdown-minute-1-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[0] > 0 ? min_left_arg[0] - 1 : 5);
                    variation_minute_container.find('.woo-sctr-countdown-minute-1-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[0] > 0 ? min_left_arg[0] - 1 : 5);
                    variation_minute_container.find('.woo-sctr-countdown-minute-1-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-1-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[1] > 0 ? min_left_arg[1] - 1 : 9);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[1] > 0 ? min_left_arg[1] - 1 : 9);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[1]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[1]);

                    variation_hour_container.find('.woo-sctr-countdown-hour-1-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[0] > 0 ? hour_left_arg[0] - 1 : 0);
                    variation_hour_container.find('.woo-sctr-countdown-hour-1-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[0] > 0 ? hour_left_arg[0] - 1 : 0);
                    variation_hour_container.find('.woo-sctr-countdown-hour-1-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-1-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[1] > 0 ? hour_left_arg[1] - 1 : 0);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[1] > 0 ? hour_left_arg[1] - 1 : 0);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[1]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[1]);

                    variation_date_container.find('.woo-sctr-countdown-date-1-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[0] > 0 ? date_left_arg[0] - 1 : 0);
                    variation_date_container.find('.woo-sctr-countdown-date-1-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[0] > 0 ? date_left_arg[0] - 1 : 0);
                    variation_date_container.find('.woo-sctr-countdown-date-1-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-date-1-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-date-2-container .woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[1] > 0 ? date_left_arg[1] - 1 : 0);
                    variation_date_container.find('.woo-sctr-countdown-date-2-container .woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[1] > 0 ? date_left_arg[1] - 1 : 0);
                    variation_date_container.find('.woo-sctr-countdown-date-2-container .woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[1]);
                    variation_date_container.find('.woo-sctr-countdown-date-2-container .woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[1]);

                } else if (variation_container.hasClass('woo-sctr-shortcode-countdown-unit-animation-flip')) {

                    variation_second_container.find('.woo-sctr-countdown-second-1-wrap .woo-sctr-countdown-flip-top').attr('data-value', sec_left_arg[0]);
                    variation_second_container.find('.woo-sctr-countdown-second-1-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', sec_left_arg[0]);
                    variation_second_container.find('.woo-sctr-countdown-second-2-wrap .woo-sctr-countdown-flip-top').attr('data-value', sec_left_arg[1]);
                    variation_second_container.find('.woo-sctr-countdown-second-2-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', sec_left_arg[1]);

                    variation_minute_container.find('.woo-sctr-countdown-minute-1-wrap .woo-sctr-countdown-flip-top').attr('data-value', min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-1-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', min_left_arg[0]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-wrap .woo-sctr-countdown-flip-top').attr('data-value', min_left_arg[1]);
                    variation_minute_container.find('.woo-sctr-countdown-minute-2-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', min_left_arg[1]);

                    variation_hour_container.find('.woo-sctr-countdown-hour-1-wrap .woo-sctr-countdown-flip-top').attr('data-value', hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-1-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', hour_left_arg[0]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-wrap .woo-sctr-countdown-flip-top').attr('data-value', hour_left_arg[1]);
                    variation_hour_container.find('.woo-sctr-countdown-hour-2-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', hour_left_arg[1]);

                    variation_date_container.find('.woo-sctr-countdown-date-1-wrap .woo-sctr-countdown-flip-top').attr('data-value', date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-date-1-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', date_left_arg[0]);
                    variation_date_container.find('.woo-sctr-countdown-date-2-wrap .woo-sctr-countdown-flip-top').attr('data-value', date_left_arg[1]);
                    variation_date_container.find('.woo-sctr-countdown-date-2-wrap .woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', date_left_arg[1]);
                }
            }
            return time_expire;

        } else if (jQuery(this).closest('.woo-sctr-checkout-countdown-wrap-wrap').length && type === 'checkout' && time) {
            return parseInt(jQuery(this).val()) - time;

        } else {
            return parseInt(jQuery(this).val()) - count;
        }
    });
    // console.log(distance);
    vi_sctr_x = setInterval(function () {
        count++;
        for (i = 0; i < wooCountdown.length; i++) {
            // console.log(distance[i]);
            let container = wooCountdown.eq(i).find('.woo-sctr-countdown-timer'),
                date_container = container.find('.woo-sctr-countdown-date-wrap'),
                hour_container = container.find('.woo-sctr-countdown-hour-wrap'),
                minute_container = container.find('.woo-sctr-countdown-minute-wrap'),
                second_container = container.find('.woo-sctr-countdown-second-wrap');
            date = Math.floor(distance[i] / 86400);
            hours = Math.floor((distance[i] % (86400)) / (3600));
            minutes = Math.floor((distance[i] % (3600)) / (60));
            seconds = Math.floor((distance[i] % (60)));
            if (date === 0) {
                date_container.addClass('woo-sctr-countdown-hidden');
                if (hours === 0) {
                    hour_container.addClass('woo-sctr-countdown-hidden');
                }
            }
            if (container.hasClass('woo-sctr-countdown-timer-7')) {
                sctv_countdown_two(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds);
            } else {
                if (date_container.hasClass('woo-sctr-countdown-hidden') && date > 0) {
                    hours += date * 24;
                }
                if (hour_container.hasClass('woo-sctr-countdown-hidden') && hours > 0) {
                    minutes += hours * 60;
                }
                if (minute_container.hasClass('woo-sctr-countdown-hidden') && minutes > 0) {
                    seconds += minutes * 60;
                }
                if (container.hasClass('woo-sctr-countdown-timer-6')) {
                    sctv_countdown_three(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds);
                } else {
                    sctv_countdown_one(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds, seconds_deg, minutes_deg, hours_deg, dates_deg);
                }

            }

            distance[i]--;
            if (distance[i] < 0) {
                clearInterval(vi_sctr_x);
                if (wooCountdown.eq(i).hasClass('woo-sctr-shortcode-countdown-timer-wrap-event')) {
                    window.location.href = window.location.href + '?sctv_countdown_job=1';
                } else {
                    window.location.reload();
                }
            }
        }
    }, 1000);
}

function sctv_countdown_three(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds) {
    if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-default')) {
        let seconds_t, minutes_t, hours_t, date_t;
        seconds_t = seconds < 10 ? ("0" + seconds).slice(-2) : seconds;
        minutes_t = minutes < 10 ? ("0" + minutes).slice(-2) : minutes;
        hours_t = hours < 10 ? ("0" + hours).slice(-2) : hours;
        date_t = date < 10 ? ("0" + date).slice(-2) : date;
        second_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', seconds_t);
        second_container.find('.woo-sctr-countdown-two-vertical-bottom').html(seconds_t);
        minute_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', minutes_t);
        minute_container.find('.woo-sctr-countdown-two-vertical-bottom').html(minutes_t);
        hour_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', hours_t);
        hour_container.find('.woo-sctr-countdown-two-vertical-bottom').html(hours_t);
        date_container.find('.woo-sctr-countdown-two-vertical-top').attr('data-value', date_t);
        date_container.find('.woo-sctr-countdown-two-vertical-bottom').html(date_t);
    } else if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-slide')) {
        if (seconds !== parseInt(second_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html())) {
            second_container.find('.woo-sctr-countdown-value-container').addClass('transition');
            setTimeout(function (container, time_value) {
                let time_value2, time_value1, t;
                t = (time_value > 0) ? time_value - 1 : 59;
                time_value1 = t > 9 ? t : ("0" + t).slice(-2);
                time_value2 = time_value > 9 ? time_value : ("0" + time_value).slice(-2);
                container.removeClass('transition');
                container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(time_value2);
            }, 500, second_container.find('.woo-sctr-countdown-value-container'), seconds);
        }
        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            setTimeout(function (container, time_value) {
                container.find('.woo-sctr-countdown-value-container').addClass('transition');
                setTimeout(function (container, time_value) {
                    let time_value2, time_value1;
                    time_value2 = (time_value > 0) ? time_value - 1 : 59;
                    time_value1 = (time_value2 > 0) ? time_value2 - 1 : 59;
                    time_value1 = time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2);
                    time_value2 = time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2);
                    container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                    container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                    container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                    container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                    container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                }, 500, container, time_value);
            }, 1000, minute_container, minutes);
            if (minutes === 0 && (hours > 0 || date > 0)) {
                setTimeout(function (container, time_value) {
                    container.find('.woo-sctr-countdown-value-container').addClass('transition');
                    setTimeout(function (container, time_value) {
                        let time_value2, time_value1;
                        time_value2 = (time_value > 0) ? time_value - 1 : 59;
                        time_value1 = (time_value2 > 0) ? time_value2 - 1 : 59;
                        time_value1 = time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2);
                        time_value2 = time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2);
                        container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                        container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                        container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                        container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                    }, 500, container, time_value);
                }, 1000, hour_container, hours);
            }
            if (hours === 0 && date > 0) {
                setTimeout(function (container, time_value) {
                    container.find('.woo-sctr-countdown-value-container').addClass('transition');
                    setTimeout(function (container, time_value) {
                        let time_value2, time_value1;
                        time_value2 = (time_value > 0) ? time_value - 1 : 0;
                        time_value1 = (time_value2 > 0) ? time_value2 - 1 : 0;
                        time_value1 = time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2);
                        time_value2 = time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2);
                        container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                        container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                        container.find('.woo-sctr-countdown-value-1.woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                        container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-value-2.woo-sctr-countdown-two-vertical-bottom').html(time_value2);

                    }, 500, container, time_value);
                }, 1000, date_container, date);
            }
        }
    } else if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-flip')) {
        second_container.find('.woo-sctr-countdown-flip-wrap').removeClass('woo-sctr-countdown-flip-active');
        setTimeout(function (container, time_value) {
            let time_value2;
            time_value2 = (time_value > 0) ? time_value - 1 : 59;
            container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
            container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value > 9 ? time_value : ("0" + time_value).slice(-2));
            container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
            container.find('.woo-sctr-countdown-flip-wrap').addClass('woo-sctr-countdown-flip-active');
        }, 500, second_container, seconds);
        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            minute_container.find('.woo-sctr-countdown-flip-wrap').removeClass('woo-sctr-countdown-flip-active');
            setTimeout(function (container, time_value) {
                let time_value2;
                time_value2 = (time_value > 0) ? time_value - 1 : 59;

                container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value > 9 ? time_value : ("0" + time_value).slice(-2));
                container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                container.find('.woo-sctr-countdown-flip-wrap').addClass('woo-sctr-countdown-flip-active');
            }, 500, minute_container, minutes);

            if (minutes === 0 && (hours > 0 || date > 0)) {
                hour_container.find('.woo-sctr-countdown-flip-wrap').removeClass('woo-sctr-countdown-flip-active');
                setTimeout(function (container, time_value) {
                    let time_value2;
                    time_value2 = (time_value > 0) ? time_value - 1 : 59;

                    container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                    container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value > 9 ? time_value : ("0" + time_value).slice(-2));
                    container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                    container.find('.woo-sctr-countdown-flip-wrap').addClass('woo-sctr-countdown-flip-active');
                }, 500, hour_container, hours);

                if (hours === 0 && date > 0) {
                    date_container.find('.woo-sctr-countdown-flip-wrap').removeClass('woo-sctr-countdown-flip-active');
                    setTimeout(function (container, time_value) {
                        let time_value2;
                        time_value2 = (time_value > 0) ? time_value - 1 : 0;

                        container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                        container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value > 9 ? time_value : ("0" + time_value).slice(-2));
                        container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                        container.find('.woo-sctr-countdown-flip-wrap').addClass('woo-sctr-countdown-flip-active');
                    }, 500, date_container, date);
                }
            }
        }
    }
}

function sctv_countdown_one(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds, seconds_deg, minutes_deg, hours_deg, dates_deg) {
    if (container.hasClass('woo-sctr-countdown-timer-circle')) {
        setTimeout(function (second_container, seconds) {
            seconds_deg = (seconds > 0 ? seconds : 59) * 6;
            if (seconds_deg < 180) {
                second_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                second_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                second_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
            } else {
                second_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                second_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                second_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
            }
            second_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + seconds_deg + 'deg)'});
        }, 500, second_container, seconds);
        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            setTimeout(function (minute_container, minutes) {
                minutes_deg = (minutes > 0 ? (minutes - 1) : 59) * 6;
                if (minutes_deg < 180) {
                    minute_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                } else {
                    minute_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                    minute_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                }

                setTimeout(function (minute_container, minutes_deg) {
                    minute_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + minutes_deg + 'deg)'});
                }, 500, minute_container, minutes_deg);
            }, 1000, minute_container, minutes);
            if (minutes === 0 && (hours > 0 || date > 0)) {
                setTimeout(function (hours, hour_container) {
                    hours_deg = (hours > 0 ? (hours - 1) : 23) * 15;
                    if (hours_deg < 180) {
                        hour_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                    } else {
                        hour_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                        hour_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                    }

                    setTimeout(function (hours_deg, hour_container) {
                        hour_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + hours_deg + 'deg)'});
                    }, 500, hours_deg, hour_container);
                }, 1000, hours, hour_container);

                if (hours === 0 && date > 0) {

                    setTimeout(function (date, date_container) {
                        dates_deg = date > 0 ? (date - 1) : 0;
                        if (dates_deg < 180) {
                            date_container.find('.woo-sctr-countdown-value-circle-container').removeClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-countdown-circle-container').removeClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-first50-bar').addClass('woo-sctr-countdown-hidden');
                        } else {
                            date_container.find('.woo-sctr-countdown-value-circle-container').addClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-countdown-circle-container').addClass('woo-sctr-over50');
                            date_container.find('.woo-sctr-first50-bar').removeClass('woo-sctr-countdown-hidden');
                        }
                        setTimeout(function (dates_deg, date_container) {
                            date_container.find('.woo-sctr-value-bar').css({'transform': 'rotate(' + dates_deg + 'deg)'});

                        }, 500, dates_deg, date_container);
                    }, 1000, date, date_container);
                }
            }
        }
    }
    if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-default')) {
        second_container.find('.woo-sctr-countdown-second-value.woo-sctr-countdown-value-animation-default').html(seconds < 10 ? ("0" + seconds).slice(-2) : seconds);
        minute_container.find('.woo-sctr-countdown-minute-value.woo-sctr-countdown-value-animation-default').html(minutes < 10 ? ("0" + minutes).slice(-2) : minutes);
        hour_container.find('.woo-sctr-countdown-hour-value.woo-sctr-countdown-value-animation-default').html(hours < 10 ? ("0" + hours).slice(-2) : hours);
        date_container.find('.woo-sctr-countdown-date-value.woo-sctr-countdown-value-animation-default').html(date < 10 ? ("0" + date).slice(-2) : date);
    } else if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-slide')) {
        if (seconds !== parseInt(second_container.find('.woo-sctr-countdown-value-2').html())) {
            second_container.find('.woo-sctr-countdown-value-container').addClass('transition');
            setTimeout(function (container, time_value) {
                let time_value2, time_value1;
                time_value2 = (time_value > 0) ? time_value - 1 : 59;
                time_value1 = (time_value2 > 0) ? time_value2 - 1 : 59;
                container.removeClass('transition');

                container.find('.woo-sctr-countdown-value-1').html(time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));
                container.find('.woo-sctr-countdown-value-2').html(time_value > 9 ? time_value : ("0" + time_value).slice(-2));
            }, 500, second_container.find('.woo-sctr-countdown-value-container'), seconds);
        }
        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            setTimeout(function (container, time_value) {
                container.find('.woo-sctr-countdown-value-container').addClass('transition');
                setTimeout(function (container, time_value) {
                    let time_value2, time_value1;
                    time_value2 = (time_value > 0) ? time_value - 1 : 59;
                    time_value1 = (time_value2 > 0) ? time_value2 - 1 : 59;
                    container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                    container.find('.woo-sctr-countdown-value-1').html(time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2));
                    container.find('.woo-sctr-countdown-value-2').html(time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));

                }, 500, container, time_value);
            }, 1000, minute_container, minutes);
            if (minutes === 0 && (hours > 0 || date > 0)) {
                setTimeout(function (container, time_value) {
                    container.find('.woo-sctr-countdown-value-container').addClass('transition');
                    setTimeout(function (container, time_value) {
                        let time_value2, time_value1;
                        time_value2 = (time_value > 0) ? time_value - 1 : 59;
                        time_value1 = (time_value2 > 0) ? time_value2 - 1 : 59;
                        container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                        container.find('.woo-sctr-countdown-value-1').html(time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2));
                        container.find('.woo-sctr-countdown-value-2').html(time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));

                    }, 500, container, time_value);
                }, 1000, hour_container, hours);

                if (hours === 0 && date > 0) {
                    setTimeout(function (container, time_value) {
                        container.find('.woo-sctr-countdown-value-container').addClass('transition');
                        setTimeout(function (container, time_value) {
                            let time_value2, time_value1;
                            time_value2 = (time_value > 0) ? time_value - 1 : 0;
                            time_value1 = (time_value2 > 0) ? time_value2 - 1 : 0;
                            container.find('.woo-sctr-countdown-value-container').removeClass('transition');
                            container.find('.woo-sctr-countdown-value-1').html(time_value1 > 9 ? time_value1 : ("0" + time_value1).slice(-2));
                            container.find('.woo-sctr-countdown-value-2').html(time_value2 > 9 ? time_value2 : ("0" + time_value2).slice(-2));

                        }, 500, container, time_value);
                    }, 1000, date_container, date);

                }
            }
        }
    }

}

function sctv_countdown_two(container, date_container, hour_container, minute_container, second_container, date, hours, minutes, seconds) {
    let sec_left_arg, min_left_arg, hour_left_arg, date_left_arg;
    let date_left_1, date_left_2, hour_left_1, hour_left_2, min_left_1, min_left_2, sec_left_1, sec_left_2;
    sec_left_arg = ("0" + seconds).slice(-2);
    min_left_arg = ("0" + minutes).slice(-2);
    hour_left_arg = ("0" + hours).slice(-2);
    date_left_arg = ("0" + date).slice(-2);

    sec_left_arg = sec_left_arg.split('');
    min_left_arg = min_left_arg.split('');
    hour_left_arg = hour_left_arg.split('');
    date_left_arg = date_left_arg.split('');

    sec_left_1 = parseInt(sec_left_arg[0]);
    sec_left_2 = parseInt(sec_left_arg[1]);

    if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-default')) {

        second_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[0]);
        second_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[0]);
        second_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', sec_left_arg[1]);
        second_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(sec_left_arg[1]);

        minute_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[0]);
        minute_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[0]);
        minute_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', min_left_arg[1]);
        minute_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(min_left_arg[1]);

        hour_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[0]);
        hour_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[0]);
        hour_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', hour_left_arg[1]);
        hour_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(hour_left_arg[1]);

        date_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[0]);
        date_container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[0]);
        date_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-top').attr('data-value', date_left_arg[1]);
        date_container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(date_left_arg[1]);
    } else if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-slide')) {
        if (sec_left_2 !== parseInt(second_container.find('.woo-sctr-countdown-second-2-container .woo-sctr-countdown-value-2 span').html())) {
            second_container.find('.woo-sctr-countdown-second-2-container').addClass('transition');
            setTimeout(function (container, time_value) {
                let time_value2;
                time_value2 = (time_value > 0) ? time_value - 1 : 9;

                container.removeClass('transition');
                container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value);
                container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value);
            }, 500, second_container.find('.woo-sctr-countdown-second-2-container'), sec_left_2);
        }
        if (sec_left_2 === 0) {
            setTimeout(function (container, time_value) {
                container.addClass('transition');
                setTimeout(function (container, time_value) {
                    let time_value1, time_value2;
                    time_value2 = (time_value > 0) ? time_value - 1 : 5;
                    time_value1 = (time_value2 > 0) ? time_value2 - 1 : 5;

                    container.removeClass('transition');
                    container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                    container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                    container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                    container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                }, 500, container, time_value);
            }, 1000, second_container.find('.woo-sctr-countdown-second-1-container'), sec_left_1);
        }
        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            min_left_1 = parseInt(min_left_arg[0]);
            min_left_2 = parseInt(min_left_arg[1]);
            setTimeout(function (container, time_value) {
                container.addClass('transition');
                setTimeout(function (container, time_value) {
                    let time_value1, time_value2;
                    time_value2 = (time_value > 0) ? time_value - 1 : 9;
                    time_value1 = (time_value2 > 0) ? time_value2 - 1 : 9;

                    container.removeClass('transition');
                    container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                    container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                    container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                    container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                }, 500, container, time_value);
            }, 1000, minute_container.find('.woo-sctr-countdown-minute-2-container'), min_left_2);
            if (min_left_2 === 0) {
                setTimeout(function (container, time_value) {
                    container.addClass('transition');
                    setTimeout(function (container, time_value) {
                        let time_value1, time_value2;
                        time_value2 = (time_value > 0) ? time_value - 1 : 5;
                        time_value1 = (time_value2 > 0) ? time_value2 - 1 : 5;

                        container.removeClass('transition');
                        container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                        container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                        container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                    }, 500, container, time_value);
                }, 1000, minute_container.find('.woo-sctr-countdown-minute-1-container'), min_left_1);
            }
            if (minutes === 0 && (hours > 0 || date > 0)) {
                hour_left_1 = parseInt(hour_left_arg[0]);
                hour_left_2 = parseInt(hour_left_arg[1]);
                setTimeout(function (container, time_value) {
                    container.addClass('transition');
                    setTimeout(function (container, time_value) {
                        let time_value1, time_value2;
                        time_value2 = (time_value > 0) ? time_value - 1 : 9;
                        time_value1 = (time_value2 > 0) ? time_value2 - 1 : 9;

                        container.removeClass('transition');
                        container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                        container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                        container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                    }, 500, container, time_value);
                }, 1000, hour_container.find('.woo-sctr-countdown-hour-2-container'), hour_left_2);
                if (hour_left_2 === 0) {
                    setTimeout(function (container, time_value) {
                        container.addClass('transition');
                        setTimeout(function (container, time_value) {
                            let time_value1, time_value2;
                            time_value2 = (time_value > 0) ? time_value - 1 : 5;
                            time_value1 = (time_value2 > 0) ? time_value2 - 1 : 5;

                            container.removeClass('transition');
                            container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                            container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                            container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                            container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                        }, 500, container, time_value);
                    }, 1000, hour_container.find('.woo-sctr-countdown-hour-1-container'), hour_left_1);
                }
                if (hours === 0 && date > 0) {
                    date_left_1 = parseInt(date_left_arg[0]);
                    date_left_2 = parseInt(date_left_arg[1]);
                    setTimeout(function (container, time_value) {
                        container.addClass('transition');
                        setTimeout(function (container, time_value) {
                            let time_value1, time_value2;
                            time_value2 = (time_value > 0) ? time_value - 1 : 9;
                            time_value1 = (time_value2 > 0) ? time_value2 - 1 : 9;

                            container.removeClass('transition');
                            container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                            container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                            container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                            container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                        }, 500, container, time_value);
                    }, 1000, date_container.find('.woo-sctr-countdown-date-2-container'), date_left_2);
                    if (date_left_2 === 0 && date_left_1 > 0) {
                        setTimeout(function (container, time_value) {
                            container.addClass('transition');
                            setTimeout(function (container, time_value) {
                                let time_value1, time_value2;
                                time_value2 = (time_value > 0) ? time_value - 1 : 0;
                                time_value1 = (time_value2 > 0) ? time_value2 - 1 : 0;

                                container.removeClass('transition');
                                container.find('.woo-sctr-countdown-value-1  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value1);
                                container.find('.woo-sctr-countdown-value-1 .woo-sctr-countdown-two-vertical-bottom').html(time_value1);
                                container.find('.woo-sctr-countdown-value-2  .woo-sctr-countdown-two-vertical-top').attr('data-value', time_value2);
                                container.find('.woo-sctr-countdown-value-2 .woo-sctr-countdown-two-vertical-bottom').html(time_value2);
                            }, 500, container, time_value);
                        }, 1000, date_container.find('.woo-sctr-countdown-date-1-container'), date_left_1);
                    }
                }
            }

        }
    } else if (container.hasClass('woo-sctr-shortcode-countdown-unit-animation-flip')) {
        second_container.find('.woo-sctr-countdown-second-2-wrap').removeClass('woo-sctr-countdown-flip-active');
        setTimeout(function (container, time_value) {
            let time_value2;
            time_value2 = (time_value > 0) ? time_value - 1 : 9;

            container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
            container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
            container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
            container.addClass('woo-sctr-countdown-flip-active');
        }, 500, second_container.find('.woo-sctr-countdown-second-2-wrap'), sec_left_2);
        if (sec_left_2 === 0) {
            second_container.find('.woo-sctr-countdown-second-1-wrap').removeClass('woo-sctr-countdown-flip-active');
            setTimeout(function (container, time_value) {
                let time_value2;
                time_value2 = (time_value > 0) ? time_value - 1 : 5;
                container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                container.addClass('woo-sctr-countdown-flip-active');
            }, 500, second_container.find('.woo-sctr-countdown-second-1-wrap'), sec_left_1);
        }

        if (seconds === 0 && (minutes > 0 || hours > 0 || date > 0)) {
            min_left_1 = parseInt(min_left_arg[0]);
            min_left_2 = parseInt(min_left_arg[1]);
            minute_container.find('.woo-sctr-countdown-minute-2-wrap').removeClass('woo-sctr-countdown-flip-active');
            setTimeout(function (container, time_value) {
                let time_value2;
                time_value2 = (time_value > 0) ? time_value - 1 : 9;

                container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                container.addClass('woo-sctr-countdown-flip-active');
            }, 500, minute_container.find('.woo-sctr-countdown-minute-2-wrap'), min_left_2);
            if (min_left_2 === 0) {
                minute_container.find('.woo-sctr-countdown-minute-1-wrap').removeClass('woo-sctr-countdown-flip-active');
                setTimeout(function (container, time_value) {
                    let time_value2;
                    time_value2 = (time_value > 0) ? time_value - 1 : 5;
                    container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                    container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                    container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                    container.addClass('woo-sctr-countdown-flip-active');
                }, 500, minute_container.find('.woo-sctr-countdown-minute-1-wrap'), min_left_1);
            }
            if (minutes === 0 && (hours > 0 || date > 0)) {
                hour_left_1 = parseInt(hour_left_arg[0]);
                hour_left_2 = parseInt(hour_left_arg[1]);
                hour_container.find('.woo-sctr-countdown-hour-2-wrap').removeClass('woo-sctr-countdown-flip-active');
                setTimeout(function (container, time_value) {
                    let time_value2;
                    time_value2 = (time_value > 0) ? time_value - 1 : 9;

                    container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                    container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                    container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                    container.addClass('woo-sctr-countdown-flip-active');
                }, 500, hour_container.find('.woo-sctr-countdown-hour-2-wrap'), hour_left_2);
                if (hour_left_2 === 0) {

                    hour_container.find('.woo-sctr-countdown-hour-1-wrap').removeClass('woo-sctr-countdown-flip-active');
                    setTimeout(function (container, time_value) {
                        let time_value2;
                        time_value2 = (time_value > 0) ? time_value - 1 : 5;

                        container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                        container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                        container.addClass('woo-sctr-countdown-flip-active');
                    }, 500, hour_container.find('.woo-sctr-countdown-hour-1-wrap'), hour_left_1);

                }
                if (hours === 0 && date > 0) {
                    date_left_1 = parseInt(date_left_arg[0]);
                    date_left_2 = parseInt(date_left_arg[1]);

                    date_container.find('.woo-sctr-countdown-date-2-wrap').removeClass('woo-sctr-countdown-flip-active');
                    setTimeout(function (container, time_value) {
                        let time_value2;
                        time_value2 = (time_value > 0) ? time_value - 1 : 9;

                        container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                        container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                        container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                        container.addClass('woo-sctr-countdown-flip-active');
                    }, 500, date_container.find('.woo-sctr-countdown-date-2-wrap'), date_left_2);
                    if (date_left_2 === 0 && date_left_1 > 0) {

                        date_container.find('.woo-sctr-countdown-date-1-wrap').removeClass('woo-sctr-countdown-flip-active');
                        setTimeout(function (container, time_value) {
                            let time_value2;
                            time_value2 = (time_value > 0) ? time_value - 1 : 0;

                            container.find('.woo-sctr-countdown-flip-top').attr('data-value', time_value2);
                            container.find('.woo-sctr-countdown-flip-back,.woo-sctr-countdown-flip-bottom').attr('data-value', time_value);
                            container.find('.woo-sctr-countdown-flip-back .woo-sctr-countdown-flip-bottom').attr('data-value', time_value2);
                            container.addClass('woo-sctr-countdown-flip-active');
                        }, 500, date_container.find('.woo-sctr-countdown-date-1-wrap'), date_left_1);
                    }
                }
            }

        }
    }

}