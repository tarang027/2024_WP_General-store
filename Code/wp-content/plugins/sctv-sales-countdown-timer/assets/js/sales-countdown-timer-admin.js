'use strict';
jQuery(document).ready(function () {
    handleNameChange();
    copyShortcode();
    handleDropdown();
    handleColorPicker();
    handleFontChange();
    handleCheckbox();
    duplicateItem();
    removeItem();
    saveDataAjax();

    // change name
    function handleNameChange() {
        jQuery('.woo-sctr-name').unbind().on('keyup', function () {
            jQuery(this).parent().parent().parent().parent().find('.woo-sctr-accordion-name').html(jQuery(this).val());
        })
    }

    // copy shortcode
    function copyShortcode() {
        jQuery('.woo-sctr-short-description-copy-shortcode').unbind().on('click', function (event) {
            let container = jQuery(this).closest('.woo-sctr-accordion-wrap');
            var value = '[sales_countdown_timer id="' + container.find('.woo-sctr-id').val() + '"]';
            var $temp = jQuery("<input>");
            jQuery("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            container.find('.woo-sctr-shortcode-copied').removeClass('woo-sctr-countdown-hidden');
            console.log(value);
            console.log(container.find('.woo-sctr-shortcode-copied').html());
            setTimeout(function (container) {
                container.find('.woo-sctr-shortcode-copied').addClass('woo-sctr-countdown-hidden');
            }, 5000, container);
            event.stopPropagation();
        });
    }

    //dropdown
    function handleDropdown() {
        jQuery('.vi-ui.accordion').accordion();
        jQuery('.vi-ui.dropdown').unbind().dropdown();
        jQuery('.ui-sortable').sortable({
            placeholder: 'sctv-place-holder',
        });
        jQuery('.woo-sctr-sal-countdown-display-type').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                container.find('.woo-sctr-design-countdown-timer, .woo-sctr-countdown-timer ').addClass('woo-sctr-countdown-hidden');
                container.find('.woo-sctr-design-countdown-timer.woo-sctr-design-countdown-timer-' + val + ', .woo-sctr-countdown-timer.woo-sctr-countdown-timer-' + val).removeClass('woo-sctr-countdown-hidden');
                if (jQuery.inArray(val, ['6', '7']) !== -1) {
                    container.find('.woo-sctr-animation-style [data-value="flip"]').removeClass("disabled");
                    container.find('.woo-sctr-animation-style option[value="flip"]').prop("disabled", false);
                    // if (val === '7') {
                    //     container.find('.woo-sctr-time-units-display-select ').addClass("disabled");
                    // } else {
                    //     container.find('.woo-sctr-time-units-display-select ').removeClass("disabled");
                    // }
                } else {
                    if (container.find('.woo-sctr-animation-style option[value="flip"]').prop('selected')) {
                        container.find('.woo-sctr-animation-style select').val('default').change();
                    }
                    container.find('.woo-sctr-animation-style [data-value="flip"]').removeClass('active selected').addClass("disabled");
                }
                if (jQuery.inArray(val, ['4', '5']) !== -1) {
                    container.find('.woo-sctr-countdown-circle-smooth-animation-wrap').removeClass('woo-sctr-hidden');
                } else {
                    container.find('.woo-sctr-countdown-circle-smooth-animation-wrap').addClass('woo-sctr-hidden');
                }
            }
        });
        jQuery('.woo-sctr-message-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'inline_countdown') {
                    container.find('.woo-sctr-countdown-timer-layout').addClass('woo-sctr-countdown-timer-layout-same-line');
                } else {
                    container.find('.woo-sctr-countdown-timer-layout').removeClass('woo-sctr-countdown-timer-layout-same-line');
                }
            }
        });
        jQuery('.woo-sctr-time-separator').dropdown({
            onChange: function (val) {
                switch (val) {
                    case 'dot':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-countdown-time-separator').html('.');
                        break;
                    case 'comma':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-countdown-time-separator').html(',');
                        break;
                    case 'colon':
                        jQuery(this).parent().parent().parent().find('.woo-sctr-countdown-time-separator').html(':');
                        break;
                    default:
                        jQuery(this).parent().parent().parent().find('.woo-sctr-countdown-time-separator').html('');
                }
            }
        });
        jQuery('.woo-sctr-count-style').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                switch (val) {
                    case '1':
                        container.find('.woo-sctr-countdown-date-text').html('days');
                        container.find('.woo-sctr-countdown-hour-text').html('hrs');
                        container.find('.woo-sctr-countdown-minute-text').html('mins');
                        container.find('.woo-sctr-countdown-second-text').html('secs');
                        container.find('.woo-sctr-datetime-format-style-custom').addClass('woo-sctr-hidden');
                        break;
                    case '2':
                        container.find('.woo-sctr-countdown-date-text').html('days');
                        container.find('.woo-sctr-countdown-hour-text').html('hours');
                        container.find('.woo-sctr-countdown-minute-text').html('minutes');
                        container.find('.woo-sctr-countdown-second-text').html('seconds');
                        container.find('.woo-sctr-datetime-format-style-custom').addClass('woo-sctr-hidden');
                        break;
                    case '3':
                        container.find('.woo-sctr-countdown-date-text').html('');
                        container.find('.woo-sctr-countdown-hour-text').html('');
                        container.find('.woo-sctr-countdown-minute-text').html('');
                        container.find('.woo-sctr-countdown-second-text').html('');
                        container.find('.woo-sctr-datetime-format-style-custom').addClass('woo-sctr-hidden');
                        break;
                    case '#other':
                        container.find('.woo-sctr-datetime-format-style-custom').removeClass('woo-sctr-hidden');
                        container.find('.woo-sctr-countdown-date-text').html(container.find('#woo-sctr-datetime-format-custom-date').val());
                        container.find('.woo-sctr-countdown-hour-text').html(container.find('#woo-sctr-datetime-format-custom-hour').val());
                        container.find('.woo-sctr-countdown-minute-text').html(container.find('#woo-sctr-datetime-format-custom-minute').val());
                        container.find('.woo-sctr-countdown-second-text').html(container.find('#woo-sctr-datetime-format-custom-second').val());
                        jQuery(document).on('keyup', '.woo-sctr-datetime-format-custom-date', function () {
                            jQuery(this).closest('.woo-sctr-panel').find('.woo-sctr-countdown-date-text').html(jQuery(this).val());
                        });
                        jQuery(document).on('keyup', '.woo-sctr-datetime-format-custom-hour', function () {
                            jQuery(this).closest('.woo-sctr-panel').find('.woo-sctr-countdown-hour-text').html(jQuery(this).val());
                        });
                        jQuery(document).on('keyup', '.woo-sctr-datetime-format-custom-minute', function () {
                            jQuery(this).closest('.woo-sctr-panel').find('.woo-sctr-countdown-minute-text').html(jQuery(this).val());
                        });
                        jQuery(document).on('keyup', '.woo-sctr-datetime-format-custom-second', function () {
                            jQuery(this).closest('.woo-sctr-panel').find('.woo-sctr-countdown-second-text').html(jQuery(this).val());
                        });
                        break;
                    default:
                        container.find('.woo-sctr-countdown-date-text').html('d');
                        container.find('.woo-sctr-countdown-hour-text').html('h');
                        container.find('.woo-sctr-countdown-minute-text').html('m');
                        container.find('.woo-sctr-countdown-second-text').html('s');
                        container.find('.woo-sctr-datetime-format-style-custom').addClass('woo-sctr-hidden');
                }
            }
        });
        jQuery('.woo-sctr-time-units-display-select ').dropdown({
            onChange: function (val) {
                let check_has_all = false;
                jQuery(this).parent().parent().find('.woo-sctr-time-units-display').val(val);
                let container = jQuery(this).closest('.woo-sctr-panel').find('.woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer');
                let count_unit_select = val.length;
                if (container.length) {
                    for (let i = 0; i < container.length; i++) {
                        let container_change = container.eq(i),
                            countdown_id = container_change.attr('data-countdown_id');
                        if (countdown_id === '7') {
                            continue;
                        }
                        container_change.find('.woo-sctr-countdown-unit-wrap').removeClass('woo-sctr-countdown-unit-wrap-two');
                        container_change.find('.woo-sctr-countdown-unit-wrap .woo-sctr-countdown-time-separator').addClass('woo-sctr-countdown-hidden');
                        if (count_unit_select !== 1) {
                            container_change.find('.woo-sctr-countdown-date-wrap').addClass('woo-sctr-countdown-unit-wrap-two');
                            container_change.find('.woo-sctr-countdown-date-wrap .woo-sctr-countdown-time-separator').removeClass('woo-sctr-countdown-hidden');
                            if (count_unit_select === 0 || jQuery.inArray('minute', val) !== -1 || jQuery.inArray('second', val) !== -1) {
                                container_change.find('.woo-sctr-countdown-hour-wrap').addClass('woo-sctr-countdown-unit-wrap-two');
                                container_change.find('.woo-sctr-countdown-hour-wrap .woo-sctr-countdown-time-separator').removeClass('woo-sctr-countdown-hidden');
                            }
                            if (count_unit_select === 0 || jQuery.inArray('second', val) !== -1) {
                                container_change.find('.woo-sctr-countdown-minute-wrap').addClass('woo-sctr-countdown-unit-wrap-two');
                                container_change.find('.woo-sctr-countdown-minute-wrap .woo-sctr-countdown-time-separator').removeClass('woo-sctr-countdown-hidden');
                            }
                        }
                        container_change.removeClass('woo-sctr-shortcode-countdown-count-unit-grid-one woo-sctr-shortcode-countdown-count-unit-grid-two woo-sctr-shortcode-countdown-count-unit-grid-three woo-sctr-shortcode-countdown-count-unit-grid-four');
                        switch (count_unit_select) {
                            case 1:
                                container_change.addClass('woo-sctr-shortcode-countdown-count-unit-grid-one');
                                break;
                            case 2:
                                container_change.addClass('woo-sctr-shortcode-countdown-count-unit-grid-two');
                                break;
                            case 3:
                                container_change.addClass('woo-sctr-shortcode-countdown-count-unit-grid-three');
                                break;
                            default:
                                container_change.addClass(' woo-sctr-shortcode-countdown-count-unit-grid-four');
                                check_has_all = true;
                        }
                        let date = 1, hour = 2, minute = 3, second = 4,
                            date_t, hour_t, minute_t, second_t;
                        if (countdown_id === '5' || countdown_id === '4') {
                            minute = 30;
                            second = 40;
                        }
                        if (check_has_all) {
                            container_change.find('.woo-sctr-countdown-second-wrap').removeClass('woo-sctr-countdown-hidden');
                            container_change.find('.woo-sctr-countdown-minute-wrap').removeClass('woo-sctr-countdown-hidden');
                            container_change.find('.woo-sctr-countdown-hour-wrap').removeClass('woo-sctr-countdown-hidden');
                            container_change.find('.woo-sctr-countdown-date-wrap').removeClass('woo-sctr-countdown-hidden');
                        } else {
                            if (val.indexOf('day') === -1) {
                                container_change.find('.woo-sctr-countdown-date-wrap').addClass('woo-sctr-countdown-hidden');
                                if (val.indexOf('hour') !== -1) {
                                    hour = date * 24 + 2;
                                    container_change.find('.woo-sctr-countdown-hour-wrap').removeClass('woo-sctr-countdown-hidden');
                                }
                            } else {
                                container_change.find('.woo-sctr-countdown-date-wrap').removeClass('woo-sctr-countdown-hidden');
                            }

                            if (val.indexOf('hour') === -1) {
                                container_change.find('.woo-sctr-countdown-hour-wrap').addClass('woo-sctr-countdown-hidden');
                                if (val.indexOf('minute') !== -1) {
                                    minute = hour * 60 + 3;
                                    container_change.find('.woo-sctr-countdown-minute-wrap').removeClass('woo-sctr-countdown-hidden');
                                }
                            } else {
                                container_change.find('.woo-sctr-countdown-hour-wrap').removeClass('woo-sctr-countdown-hidden');
                            }
                            if (val.indexOf('minute') === -1) {
                                container_change.find('.woo-sctr-countdown-minute-wrap').addClass('woo-sctr-countdown-hidden');
                                if (val.indexOf('second') !== -1) {
                                    second = minute * 60 + 4;
                                    container_change.find('.woo-sctr-countdown-second-wrap').removeClass('woo-sctr-countdown-hidden');
                                }
                            } else {
                                container_change.find('.woo-sctr-countdown-minute-wrap').removeClass('woo-sctr-countdown-hidden');
                            }

                            if (val.indexOf('second') === -1) {
                                container_change.find('.woo-sctr-countdown-second-wrap').addClass('woo-sctr-countdown-hidden');
                            } else {
                                container_change.find('.woo-sctr-countdown-second-wrap').removeClass('woo-sctr-countdown-hidden');
                            }
                        }
                        date_t = date > 9 ? date : '0' + date;
                        hour_t = hour > 9 ? hour : '0' + hour;
                        minute_t = minute > 9 ? minute : '0' + minute;
                        second_t = second > 9 ? second : '0' + second;
                        switch (countdown_id) {
                            case '6':
                                container_change.find('.woo-sctr-countdown-date-value.woo-sctr-countdown-two-vertical-top').attr('data-value', date_t);
                                container_change.find('.woo-sctr-countdown-date-value.woo-sctr-countdown-two-vertical-bottom').html(date_t);
                                container_change.find('.woo-sctr-countdown-hour-value.woo-sctr-countdown-two-vertical-top').attr('data-value', hour_t);
                                container_change.find('.woo-sctr-countdown-hour-value.woo-sctr-countdown-two-vertical-bottom').html(hour_t);
                                container_change.find('.woo-sctr-countdown-minute-value.woo-sctr-countdown-two-vertical-top').attr('data-value', minute_t);
                                container_change.find('.woo-sctr-countdown-minute-value.woo-sctr-countdown-two-vertical-bottom').html(minute_t);
                                container_change.find('.woo-sctr-countdown-second-value.woo-sctr-countdown-two-vertical-top').attr('data-value', second_t);
                                container_change.find('.woo-sctr-countdown-second-value.woo-sctr-countdown-two-vertical-bottom').html(second_t);
                                break;
                            default:
                                container_change.find('.woo-sctr-countdown-date-value').html(date_t);
                                container_change.find('.woo-sctr-countdown-hour-value').html(hour_t);
                                container_change.find('.woo-sctr-countdown-minute-value').html(minute_t);
                                container_change.find('.woo-sctr-countdown-second-value').html(second_t);
                        }
                    }
                }
            }
        });

        jQuery('.woo-sctr-countdown-template-1-time-unit-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'top') {
                    container.find('.woo-sctr-countdown-timer-1 .woo-sctr-datetime-format-position-top').removeClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-1 .woo-sctr-datetime-format-position-bottom').addClass('woo-sctr-countdown-hidden');
                } else {
                    container.find('.woo-sctr-countdown-timer-1 .woo-sctr-datetime-format-position-top').addClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-1 .woo-sctr-datetime-format-position-bottom').removeClass('woo-sctr-countdown-hidden');
                }
            }
        });
        jQuery('.woo-sctr-countdown-template-2-time-unit-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                console.log(val)
                if (val === 'top') {
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-countdown-unit').css({'grid-template-rows': '35% 65%'});
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-datetime-format-position-top').removeClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-datetime-format-position-bottom').addClass('woo-sctr-countdown-hidden');
                } else {
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-countdown-unit').css({'grid-template-rows': '65% 35%'});
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-datetime-format-position-top').addClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-2  .woo-sctr-datetime-format-position-bottom').removeClass('woo-sctr-countdown-hidden');
                }
            }
        });
        jQuery('.woo-sctr-countdown-template-4-time-unit-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'top') {
                    container.find('.woo-sctr-countdown-timer-4  .woo-sctr-datetime-format-position-top').removeClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-4  .woo-sctr-datetime-format-position-bottom').addClass('woo-sctr-countdown-hidden');
                } else {
                    container.find('.woo-sctr-countdown-timer-4  .woo-sctr-datetime-format-position-top').addClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-4  .woo-sctr-datetime-format-position-bottom').removeClass('woo-sctr-countdown-hidden');
                }
            }
        });
        jQuery('.woo-sctr-countdown-template-6-time-unit-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'top') {
                    container.find('.woo-sctr-countdown-timer-6  .woo-sctr-datetime-format-position-top').removeClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-6  .woo-sctr-datetime-format-position-bottom').addClass('woo-sctr-countdown-hidden');
                } else {
                    container.find('.woo-sctr-countdown-timer-6  .woo-sctr-datetime-format-position-top').addClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-6  .woo-sctr-datetime-format-position-bottom').removeClass('woo-sctr-countdown-hidden');
                }
            }
        });
        jQuery('.woo-sctr-countdown-template-7-time-unit-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'top') {
                    container.find('.woo-sctr-countdown-timer-7  .woo-sctr-datetime-format-position-top').removeClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-7  .woo-sctr-datetime-format-position-bottom').addClass('woo-sctr-countdown-hidden');
                } else {
                    container.find('.woo-sctr-countdown-timer-7  .woo-sctr-datetime-format-position-top').addClass('woo-sctr-countdown-hidden');
                    container.find('.woo-sctr-countdown-timer-7  .woo-sctr-datetime-format-position-bottom').removeClass('woo-sctr-countdown-hidden');
                }
            }
        });

        jQuery('.woo-sctr-archive-page-show-select').dropdown({
            onChange: function (val) {
                jQuery(this).parent().parent().find('.woo-sctr-archive-page-show').val(val);
            }
        });
        jQuery('.woo-sctr-progress-bar-order-status-select').dropdown({
            onChange: function (val) {
                jQuery(this).parent().parent().find('.woo-sctr-progress-bar-order-status').val(val);
            }
        });
        jQuery('.woo-sctr-progress-bar-message-position').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                container.find('.woo-sctr-progress-bar-message').addClass('woo-sctr-progress-bar-hidden');
                container.find('.woo-sctr-progress-bar-wrap-container').removeClass('woo-sctr-progress-bar-wrap-inline');
                switch (val) {
                    case 'above_progressbar':
                        container.find('.woo-sctr-progress-bar-message-above').removeClass('woo-sctr-progress-bar-hidden');
                        break;
                    case 'below_progressbar':
                        container.find('.woo-sctr-progress-bar-message-below').removeClass('woo-sctr-progress-bar-hidden');
                        break;
                    case 'in_progressbar':
                        container.find('.woo-sctr-progress-bar-message-in').removeClass('woo-sctr-progress-bar-hidden');
                        break;
                    case 'left_progressbar':
                        container.find('.woo-sctr-progress-bar-wrap-container').addClass('woo-sctr-progress-bar-wrap-inline');
                        container.find('.woo-sctr-progress-bar-message-above').removeClass('woo-sctr-progress-bar-hidden');
                        break;
                    default:
                        container.find('.woo-sctr-progress-bar-wrap-container').addClass('woo-sctr-progress-bar-wrap-inline');
                        container.find('.woo-sctr-progress-bar-message-below').removeClass('woo-sctr-progress-bar-hidden');
                }
            }
        });

        jQuery('.woo-sctr-progress-bar-type').dropdown({
            onChange: function (val) {
                let container = jQuery(this).closest('.woo-sctr-panel');
                if (val === 'increase') {
                    container.find('.woo-sctr-progress-bar-fill').css('width', '20%');
                } else {
                    container.find('.woo-sctr-progress-bar-fill').css('width', '80%');
                }
            }
        });
        jQuery('.woo-sctr-progress-bar-template-1-width-type').dropdown({
            onChange: function (val) {
                let width = jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-template-1-width').val();
                if (parseInt(width) > 0) {
                    width = width + val;
                } else {
                    width = '100%';
                }
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({
                    'width': width
                });
            }
        });
    }

    function handleColorPicker() {
        jQuery('.color-picker').unbind().iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            },
            hide: true,
            border: true
        }).on('click', function (e) {
            jQuery('.iris-picker').hide();
            jQuery(this).parent().find('.iris-picker').show();
            e.stopPropagation();
        })

        /* countdown timer  layout 1*/
        jQuery('.woo-sctr-countdown-layout-1-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1,.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 .woo-sctr-countdown-timer-text-wrap').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1,.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 .woo-sctr-countdown-timer-text-wrap').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-layout-1-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-layout-1-border-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({'border': '1px solid ' + ui.color.toString()});
            }
        }).on('keyup', function () {
            if (jQuery(this).val() !== '') {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1').css({'border': '1px solid ' + jQuery(this).val()});
            } else {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({'border': 'none'});
                jQuery(this).parent().find('.color-picker').css({'background': 'none'});
            }
        });

        /* template 1 */

        jQuery('.woo-sctr-countdown-template-1-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-1-value-background').iris({
            change: function (event, ui) {
                let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                let div_value_change = jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ');
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                div_value_change.css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-1-value-border-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'border': '1px solid ' + ui.color.toString()});

            }
        }).on('keyup', function () {
            if (jQuery(this).val() !== '') {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'border': '1px solid ' + jQuery(this).val()});

            } else {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'border': 'none'});
                jQuery(this).parent().find('.color-picker').css({'background': 'none'});
            }
        });

        jQuery('.woo-sctr-countdown-template-1-text-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-1-text-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        /*template 2*/
        jQuery('.woo-sctr-countdown-template-2-item-border-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'border': '1px solid ' + ui.color.toString()});
            }
        }).on('keyup', function () {
            if (jQuery(this).val() !== '') {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'border': '1px solid ' + jQuery(this).val()});
                jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
            } else {
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'border': 'none'});
                jQuery(this).parent().find('.color-picker').css({'background': 'none'});
            }
        });
        jQuery('.woo-sctr-countdown-template-2-item-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-2-item-value-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-2-item-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-2-item-time-unit-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });


        /*template 3*/

        jQuery('.woo-sctr-countdown-template-3-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-3-value-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-3-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-3-time-unit-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        /* template 4 */
        jQuery('.woo-sctr-countdown-template-4-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-4-value-border-color-1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-4-value-border-color-2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar').css({'background-color': jQuery(this).val()});
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-4-value-background').iris({
            change: function (event, ui) {
                let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:[\\s\\S]*?;}';
                let reg = new RegExp(reg_str);
                let match = reg.exec(str);
                if (match) {
                    str = str.replace(match[0], '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:' + ui.color.toString() + ';}');
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                } else {
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:' + ui.color.toString() + ';}');
                }
            }
        }).on('keyup', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:[\\s\\S]*?;}';
            let reg = new RegExp(reg_str);
            let match = reg.exec(str);
            if (match) {
                str = str.replace(match[0], '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:' + jQuery(this).val() + ';}');
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            } else {
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:' + jQuery(this).val() + ';}');
            }
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-4-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-4-time-unit-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        /* template 5 */

        jQuery('.woo-sctr-countdown-template-5-value-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('.woo-sctr-countdown-template-5-date-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-countdown-circle ').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-countdown-circle ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-date-border-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-countdown-circle ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-countdown-circle ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-date-border-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-value-bar ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-date .woo-sctr-value-bar ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('.woo-sctr-countdown-template-5-hour-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-countdown-circle ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-countdown-circle ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-hour-border-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-countdown-circle ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-countdown-circle ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-hour-border-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-value-bar ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-hour .woo-sctr-value-bar ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-minute-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-countdown-circle ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-countdown-circle ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-minute-border-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-countdown-circle ').css({borderColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-countdown-circle ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-minute-border-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-value-bar,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute.woo-sctr-over50 .woo-sctr-first50-bar  ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-minute .woo-sctr-value-bar ,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute.woo-sctr-over50 .woo-sctr-first50-bar ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-second-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-countdown-circle ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-countdown-circle ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-second-border-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-countdown-circle ').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-countdown-circle ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-5-second-border-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-value-bar,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-second.woo-sctr-over50 .woo-sctr-first50-bar').css({borderColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-countdown-second .woo-sctr-value-bar,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-second.woo-sctr-over50 .woo-sctr-first50-bar ').css({'border-color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        /* template 6 */
        jQuery('.woo-sctr-countdown-template-6-value-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-6-value-background1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-6-value-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-6-value-background2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-6-value-cut-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                let reg = new RegExp(reg_str);
                let match = reg.exec(str), str1;
                let color = jQuery(this).val();
                if (parent_accordion.find('.woo-sctr-countdown-template-6-value-cut-behind-check').prop('checked')) {
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                    str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';
                } else {
                    str1 = '';
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default ').css({'border-bottom': '1px solid ' + ui.color.toString()});
                }
                if (match) {
                    str = str.replace(match[0], str1);
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                } else if (str1) {
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
                }
            }
        }).on('keyup', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
            let reg = new RegExp(reg_str);
            let match = reg.exec(str), str1;
            let color = jQuery(this).val();
            if (parent_accordion.find('.woo-sctr-countdown-template-6-value-cut-behind-check').prop('checked')) {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';
            } else {
                str1 = '';
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default ').css({'border-bottom': '1px solid ' + ui.color.toString()});
            }
            if (match) {
                str = str.replace(match[0], str1);
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            } else if (str1) {
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
            }
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('.woo-sctr-countdown-template-6-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        /* template 7 */

        jQuery('.woo-sctr-countdown-template-7-value-color1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-7-value-background1').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-7-value-color2').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-7-value-background2').iris({
            change: function (event, ui) {
                console.log(ui)
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-bottom ').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-countdown-template-7-value-cut-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                let reg = new RegExp(reg_str);
                let match = reg.exec(str), str1;
                let color = jQuery(this).val();
                if (parent_accordion.find('.woo-sctr-countdown-template-7-value-cut-behind-check').prop('checked')) {
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                    str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';
                } else {
                    str1 = '';
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default').css({'border-bottom': '1px solid ' + ui.color.toString()});
                }
                if (match) {
                    str = str.replace(match[0], str1);
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                } else if (str1) {
                    jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
                }
            }
        }).on('keyup', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
            let reg = new RegExp(reg_str);
            let match = reg.exec(str), str1;
            let color = jQuery(this).val();
            if (parent_accordion.find('.woo-sctr-countdown-template-7-value-cut-behind-check').prop('checked')) {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';
            } else {
                str1 = '';
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default').css({'border-bottom': '1px solid ' + ui.color.toString()});
            }
            if (match) {
                str = str.replace(match[0], str1);
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            } else if (str1) {
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
            }
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('.woo-sctr-countdown-template-7-time-unit-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-text ').css({color: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-text ').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        /* progress bar */
        jQuery('.woo-sctr-progress-bar-template-1-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('.woo-sctr-progress-bar-template-1-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-fill').css({backgroundColor: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-fill').css({'background': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-sctr-progress-bar-template-1-message-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-message').css({color: ui.color.toString()})
            }
        }).on('keyup', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-message').css({'color': jQuery(this).val()});
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        /* sale countdown on single page */
        jQuery('.woo-sctr-sale_countdown_wrap_border_color_in_single').on('keyup', function () {
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('body').on('click', function () {
            jQuery('.iris-picker').hide();
        });
    }

    function handleFontChange() {
        /* layout 1*/
        jQuery('.woo-sctr-countdown-layout-1-border-radius').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({'border-radius': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-layout-1-padding').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout.woo-sctr-layout-1 ').css({'padding': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-layout-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout').css({'font-size': jQuery(this).val() + 'px'});
        });

        /*countdown template 1 */
        jQuery('.woo-sctr-countdown-template-1-value-font-size').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'font-size': jQuery(this).val() + 'px'});

        });
        jQuery('.woo-sctr-countdown-template-1-value-border-radius').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'border-radius': jQuery(this).val() + 'px'})
        });
        jQuery('.woo-sctr-countdown-template-1-value-height').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({
                'height': jQuery(this).val() + 'px'
            });
        });
        jQuery('.woo-sctr-countdown-template-1-value-width').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({
                'width': jQuery(this).val() + 'px'
            });
        });
        jQuery('.woo-sctr-countdown-template-1-text-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-1 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'font-size': jQuery(this).val() + 'px'})
        });

        /* countdown template 2 */
        jQuery('.woo-sctr-countdown-template-2-item-border-radius').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'border-radius': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-2-item-height').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'height': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-2-item-width').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-unit ').css({'width': jQuery(this).val() + 'px'});
        });

        jQuery('.woo-sctr-countdown-template-2-item-value-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'font-size': jQuery(this).val() + 'px'});
        });

        jQuery('.woo-sctr-countdown-template-2-item-time-unit-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'font-size': jQuery(this).val() + 'px'});
        });

        /*countdown template 3  */
        jQuery('.woo-sctr-countdown-template-3-value-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-3-time-unit-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-3 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'font-size': jQuery(this).val() + 'px'});
        });

        /*countdown template 4  */
        jQuery('.woo-sctr-countdown-template-4-value-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-value ').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-4-time-unit-fontsize').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-unit-wrap .woo-sctr-countdown-text ').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-4-value-border-width').unbind().on('change', function () {
            if (!jQuery(this).val() || !jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-template-4-value-diameter').val()) {
                return false;
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            let circle_border_width = parseInt(jQuery(this).val());
            let circle_diameter = parseInt(parent_accordion.find('.woo-sctr-countdown-template-4-value-diameter').val());
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar  ').css({'border-width': circle_border_width + 'px'});
            if (circle_border_width === 0) {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'background-color': 'transparent'});
            } else {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'background-color': parent_accordion.find('.woo-sctr-countdown-template-4-value-border-color-2').val()});
            }
            let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {width:[\\s\\S]*?;}';
            let reg = new RegExp(reg_str);
            let match = reg.exec(str);
            let str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {width:' + (circle_diameter - 2 * circle_border_width) + 'px ;height:' + (circle_diameter - 2 * circle_border_width) + 'px ; top:' + circle_border_width + 'px ; left:' + circle_border_width + 'px ;}';
            if (match) {
                str = str.replace(match[0], str1);
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            } else {
                str += str1;
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            }
        });
        jQuery('.woo-sctr-countdown-template-4-value-diameter').unbind().on('change', function () {
            if (!jQuery(this).val() || !jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-template-4-value-border-width').val()) {
                return false;
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            let circle_border_width = parseInt(parent_accordion.find('.woo-sctr-countdown-template-4-value-border-width').val());
            let circle_diameter = parseInt(jQuery(this).val());
            if (circle_border_width === 0) {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'background-color': 'transparent'});
            } else {
                parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'background-color': parent_accordion.find('.woo-sctr-countdown-template-4-value-border-color-2').val()});
            }
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container ').css({
                'width': circle_diameter + 'px',
                'height': circle_diameter + 'px',
            });
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-left-half-clipper ,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'clip': 'rect(0,' + circle_diameter + 'px,' + circle_diameter + 'px,' + circle_diameter / 2 + 'px)'});
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar  ').css({
                'clip': 'rect(0,' + circle_diameter / 2 + 'px,' + circle_diameter + 'px,0 )',
                'border-width': circle_border_width + 'px'
            });

            let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {width:[\\s\\S]*?;}';
            let reg = new RegExp(reg_str);
            let match = reg.exec(str);
            let str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {width:' + (circle_diameter - 2 * circle_border_width) + 'px ;height:' + (circle_diameter - 2 * circle_border_width) + 'px ; top:' + circle_border_width + 'px ; left:' + circle_border_width + 'px ;}';
            if (match) {
                str = str.replace(match[0], str1);
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            } else {
                str += str1;
                jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
            }
        });

        /*countdown template 5  */

        jQuery('.woo-sctr-countdown-template-5-item-border-width').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(0);
            }
            let circle_border_width = parseInt(jQuery(this).val());
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-countdown-circle').css({'border-width': circle_border_width + 'px'});
            parent_accordion.find(' .woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-value-bar ').css({'border-width': circle_border_width + 'px'});
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-over50 .woo-sctr-first50-bar').css({'border-width': circle_border_width + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-5-item-diameter').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(90);
            }
            let circle_diameter = parseInt(jQuery(this).val());
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container').css({
                'width': circle_diameter + 'px',
                'height': circle_diameter + 'px'
            });
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-left-half-clipper ,.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container.woo-sctr-over50 .woo-sctr-first50-bar  ').css({'clip': 'rect(0,' + circle_diameter + 'px,' + circle_diameter + 'px,' + circle_diameter / 2 + 'px)'});
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-value-bar').css({'clip': 'rect(0,' + circle_diameter / 2 + 'px,' + circle_diameter + 'px,0 )'});
        });
        jQuery('.woo-sctr-countdown-template-5-value-fontsize').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(30);
            }
            let value_fontsize = parseInt(jQuery(this).val());
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-countdown-value').css({
                'font-size': value_fontsize + 'px',
            });
        });
        jQuery('.woo-sctr-countdown-template-5-time-unit-fontsize').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(12);
            }
            let time_unit_fontsize = parseInt(jQuery(this).val());
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container .woo-sctr-countdown-text').css({'font-size': time_unit_fontsize + 'px'});
        });

        /*countdown template 6  */
        jQuery('.woo-sctr-countdown-template-6-value-width').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(48);
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap').css({'width': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-6-value-height').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(40);
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap').css({'height': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-6-value-border-radius').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap').css({'border-radius': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-6-value-fontsize').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap span').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-6-time-unit-fontsize').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-text').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-6-time-unit-grid-gap').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap').css({'grid-gap': jQuery(this).val() + 'px'});
        });
        /*countdown template 7  */
        jQuery('.woo-sctr-countdown-template-7-value-width').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(50);
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap').css({'width': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-7-value-height').unbind().on('change', function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(75);
            }
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap').css({'height': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-7-value-border-radius').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap').css({'border-radius': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-7-value-fontsize').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-7-time-unit-fontsize').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-text').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-countdown-template-7-time-unit-grid-gap').unbind().on('change', function () {
            let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
            parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap').css({'grid-gap': jQuery(this).val() + 'px'});
        });

        /*progress bar template 1*/
        jQuery('.woo-sctr-progress-bar-template-1-font-size').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-message').css({'font-size': jQuery(this).val() + 'px'});
        });
        jQuery('.woo-sctr-progress-bar-template-1-border-radius').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({'border-radius': jQuery(this).val() + 'px'});
        });

        jQuery('.woo-sctr-progress-bar-template-1-height').unbind().on('change', function () {
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({
                'height': jQuery(this).val() + 'px'
            });
        });
        jQuery('.woo-sctr-progress-bar-template-1-width').unbind().on('change', function () {
            let width = jQuery(this).val();
            if (parseInt(width) > 0) {
                width = width + jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-template-1-width-type select').val();
            } else {
                width = '100%';
            }
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-progress-bar-1 .woo-sctr-progress-bar-wrap').css({
                'width': width
            });
        });

        jQuery('.woo-sctr-message').unbind().on('keyup', function () {
            var textBefore, textAfter, message = jQuery(this).val();
            var temp = message.split('{countdown_timer}');
            if (temp.length < 2) {
                jQuery('.woo-sctr-warning-message-countdown-timer').removeClass('woo-sctr-hidden');
            } else {
                jQuery('.woo-sctr-warning-message-countdown-timer').addClass('woo-sctr-hidden');
                textBefore = temp[0];
                textAfter = temp[1];
            }
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-text-before').html(textBefore);
            jQuery(this).closest('.woo-sctr-accordion-wrap').find('.woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-text-after').html(textAfter);
        });
        jQuery('.woo-sctr-sale-from-date').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-from-date').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-from-time').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-from-time').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-to-date').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-to-date').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-sale-to-time').unbind().on('change', function () {
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-short-description-to-time').html(jQuery(this).val());
        });
        jQuery('.woo-sctr-progress-bar-message-input').unbind().on('keyup', function () {
            let pg_message = jQuery(this).val();
            pg_message = pg_message.replace(/{quantity_left}/g, '80');
            pg_message = pg_message.replace(/{quantity_sold}/g, '20');
            pg_message = pg_message.replace(/{percentage_sold}/g, '20');
            pg_message = pg_message.replace(/{percentage_left}/g, '20');
            pg_message = pg_message.replace(/{goal}/g, '100');
            jQuery(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.woo-sctr-progress-bar-message').html(pg_message);
        });
    }

    // handle checkbox to save
    function handleCheckbox() {
        jQuery('.vi-ui.checkbox').unbind().checkbox();
        jQuery('input[type="checkbox"]').unbind().not('.woo-sctr-display-type-checkbox').on('change', function () {
            let container = jQuery(this).closest('.woo-sctr-panel');
            if (jQuery(this).prop('checked')) {
                jQuery(this).parent().find('input[type="hidden"]').val('1');
                if (jQuery(this).hasClass('woo-sctr-countdown-template-6-value-box-shadow-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap').css({'box-shadow': '0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08)'});
                } else if (jQuery(this).hasClass('woo-sctr-countdown-template-6-value-cut-behind-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top').removeClass('woo-sctr-countdown-two-vertical-top-cut-default').addClass('woo-sctr-countdown-two-vertical-top-cut-behind');
                    let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                    let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                    let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                    let reg = new RegExp(reg_str);
                    let match = reg.exec(str), str1;
                    let color = container.find('.woo-sctr-countdown-template-6-value-cut-color').val();
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                    str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';

                    if (match) {
                        str = str.replace(match[0], str1);
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                    } else if (str1) {
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
                    }
                } else if (jQuery(this).hasClass('woo-sctr-countdown-template-7-value-box-shadow-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap').css({'box-shadow': '0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08)'});
                } else if (jQuery(this).hasClass('woo-sctr-countdown-template-7-value-cut-behind-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top').removeClass('woo-sctr-countdown-two-vertical-top-cut-default').addClass('woo-sctr-countdown-two-vertical-top-cut-behind');
                    let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                    let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                    let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                    let reg = new RegExp(reg_str);
                    let match = reg.exec(str), str1;
                    let color = container.find('.woo-sctr-countdown-template-7-value-cut-color').val();
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind').css({'border-bottom': 'unset'});
                    str1 = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom: 1px solid ' + color + ';}';
                    if (match) {
                        str = str.replace(match[0], str1);
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                    } else if (str1) {
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str + str1);
                    }
                }
            } else {
                jQuery(this).parent().find('input[type="hidden"]').val('');
                if (jQuery(this).hasClass('woo-sctr-countdown-template-6-value-box-shadow-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap').css({'box-shadow': 'unset'});
                } else if (jQuery(this).hasClass('woo-sctr-countdown-template-6-value-cut-behind-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top').addClass('woo-sctr-countdown-two-vertical-top-cut-default').removeClass('woo-sctr-countdown-two-vertical-top-cut-behind');
                    let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                    let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                    let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                    let reg = new RegExp(reg_str);
                    let match = reg.exec(str), str1;
                    let color = container.find('.woo-sctr-countdown-template-6-value-cut-color').val();
                    str1 = '';
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default').css({'border-bottom': '1px solid ' + color});

                    if (match) {
                        str = str.replace(match[0], str1);
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                    }
                }
                if (jQuery(this).hasClass('woo-sctr-countdown-template-7-value-box-shadow-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap').css({'box-shadow': 'unset'});
                } else if (jQuery(this).hasClass('woo-sctr-countdown-template-7-value-cut-behind-check')) {
                    container.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top').addClass('woo-sctr-countdown-two-vertical-top-cut-default').removeClass('woo-sctr-countdown-two-vertical-top-cut-behind');
                    let parent_accordion = jQuery(this).closest('.woo-sctr-accordion-wrap');
                    let str = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
                    let reg_str = '.woo-sctr-accordion-wrap-' + parent_accordion.attr('data-accordion_id') + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind:after{border-bottom:[\\s\\S]*?;}';
                    let reg = new RegExp(reg_str);
                    let match = reg.exec(str), str1;
                    let color = container.find('.woo-sctr-countdown-template-7-value-cut-color').val();
                    str1 = '';
                    parent_accordion.find('.woo-sctr-countdown-timer.woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default').css({'border-bottom': '1px solid ' + color});

                    if (match) {
                        str = str.replace(match[0], str1);
                        jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(str);
                    }
                }
            }
        });
    }

    // duplicate item
    function duplicateItem() {
        jQuery('.woo-sctr-button-edit-duplicate').unbind().on('click', function (e) {
            e.stopPropagation();
            let new_id = jQuery('.woo-sctr-accordion-wrap').length;
            let inline_style = jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html();
            var current = jQuery(this).parent().parent().parent();
            var newRow = current.clone();
            var $now = Date.now();
            newRow.find('.vi-ui.checkbox').unbind().checkbox();
            for (let i = 0; i < newRow.find('.vi-ui.dropdown').length; i++) {
                let selected = current.find('.vi-ui.dropdown').eq(i).dropdown('get value');
                newRow.find('.vi-ui.dropdown').eq(i).dropdown('set selected', selected);
            }
            inline_style += '.woo-sctr-accordion-wrap-' + $now + '  .woo-sctr-countdown-timer-layout .woo-sctr-countdown-timer-wrap .woo-sctr-countdown-timer.woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {background:' + newRow.find('.woo-sctr-countdown-template-4-value-background').val() + ';}';
            jQuery('#vi-sales-countdown-timer-admin-css-inline-css').html(inline_style);
            newRow.attr('data-accordion_id', $now);
            newRow.find('.woo-sctr-id').val($now);
            newRow.find('.woo-sctr-shortcode-text span.woo-sctr-shortcode-show').html('[sales_countdown_timer id="' + $now + '"]');

            newRow.find('.iris-picker').remove();
            newRow.find('.color-picker').iris({
                change: function (event, ui) {
                    jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
                },
                hide: true,
                border: true
            }).on('click', function (ev) {
                jQuery('.iris-picker').hide();
                jQuery(this).parent().find('.iris-picker').show();
                ev.stopPropagation();
            });
            newRow.insertAfter(current);
            duplicateItem();
            removeItem();
            handleCheckbox();
            handleNameChange();
            copyShortcode();
            handleDropdown();
            handleColorPicker();
            handleFontChange();
            jQuery('.vi-ui.accordion')
                .accordion('refresh')
            ;
            e.stopPropagation();
        });

    }

    // remove item
    function removeItem() {
        jQuery('.woo-sctr-button-edit-remove').unbind().on('click', function (e) {
            if (jQuery('.woo-sctr-button-edit-remove').length === 1) {
                alert('You can not remove the last item.');
                return false;
            }
            if (confirm("Would you want to remove this?")) {
                jQuery(this).parent().parent().parent().remove();
            }
            e.stopPropagation();
        });
    }

    var myDebugVar = 0, save_all_change = true, save_update_key;

    function myDebug() {
        // console.log(myDebugVar);
        myDebugVar++;
    }

    function saveDataAjax() {
        jQuery('.woo-sctr-check-key').unbind().on('click', function () {
            if (!confirm('Save all your changes.')) {
                save_all_change = false;
            } else {
                save_all_change = true;
            }
            save_update_key = true;
            jQuery('.woo-sctr-save').trigger('click');
        });
        jQuery('body').on('click', '.woo-sctr-save', function () {
            var myArr;
            var myData = {};
            var temp;
            if (save_all_change) {
                jQuery(this).addClass('woo-sctr-adding');
                var nameArr = jQuery('input[name="sale_countdown_name[]"]');
                var z, v;
                for (z = 0; z < nameArr.length; z++) {
                    if (!jQuery('input[name="sale_countdown_name[]"]').eq(z).val()) {
                        alert('Name cannot be empty!');
                        // jQuery('input[name="sale_countdown_name[]"]').eq(z).focus();
                        if (!jQuery('.woo-sctr-accordion').eq(z).hasClass('woo-sctr-active-accordion')) {
                            jQuery('.woo-sctr-accordion').eq(z).addClass('woo-sctr-active-accordion');
                            jQuery('.woo-sctr-panel').eq(z).css({'max-height': jQuery('.woo-sctr-panel').eq(z).prop('scrollHeight') + 'px'})
                        }
                        jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                        return false;
                    }
                }
                for (z = 0; z < nameArr.length - 1; z++) {
                    for (v = z + 1; v < nameArr.length; v++) {
                        if (jQuery('input[name="sale_countdown_name[]"]').eq(z).val() === jQuery('input[name="sale_countdown_name[]"]').eq(v).val()) {
                            alert("Names are unique!");
                            // jQuery('input[name="sale_countdown_name[]"]').eq(v).focus();
                            if (!jQuery('.woo-sctr-accordion').eq(v).hasClass('woo-sctr-active-accordion')) {
                                jQuery('.woo-sctr-accordion').eq(v).addClass('woo-sctr-active-accordion');
                                jQuery('.woo-sctr-panel').eq(v).css({'max-height': jQuery('.woo-sctr-panel').eq(v).prop('scrollHeight') + 'px'})
                            }
                            jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                            return false;
                        }
                    }
                }
                myArr = [
                    'sale_countdown_active',
                    'sale_countdown_name',
                    'sale_countdown_id',
                    'sale_countdown_loop_enable',
                    'sale_countdown_loop_time_val',
                    'sale_countdown_loop_time_type',
                    'sale_countdown_fom_date',
                    'sale_countdown_fom_time',
                    'sale_countdown_to_date',
                    'sale_countdown_to_time',
                    'sale_countdown_message',
                    'sale_countdown_layout',
                    'sale_countdown_display_type',
                    'sale_countdown_message_position',
                    'sale_countdown_time_units',
                    'sale_countdown_time_separator',
                    'sale_countdown_datetime_format',
                    'sale_countdown_datetime_format_custom_date',
                    'sale_countdown_datetime_format_custom_hour',
                    'sale_countdown_datetime_format_custom_minute',
                    'sale_countdown_datetime_format_custom_second',
                    'sale_countdown_animation_style',

                    'sale_countdown_layout_fontsize',
                    'sale_countdown_layout_1_background',
                    'sale_countdown_layout_1_color',
                    'sale_countdown_layout_1_border_color',
                    'sale_countdown_layout_1_border_radius',
                    'sale_countdown_layout_1_padding',
                    'sale_countdown_layout_1_sticky_background',
                    'sale_countdown_layout_1_sticky_color',
                    'sale_countdown_layout_1_sticky_border_color',

                    'sale_countdown_template_1_time_unit_position',
                    'sale_countdown_template_1_time_unit_color',
                    'sale_countdown_template_1_time_unit_background',
                    'sale_countdown_template_1_time_unit_fontsize',
                    'sale_countdown_template_1_value_color',
                    'sale_countdown_template_1_value_background',
                    'sale_countdown_template_1_value_border_color',
                    'sale_countdown_template_1_value_border_radius',
                    'sale_countdown_template_1_value_width',
                    'sale_countdown_template_1_value_height',
                    'sale_countdown_template_1_value_font_size',

                    'sale_countdown_template_2_item_border_color',
                    'sale_countdown_template_2_item_border_radius',
                    'sale_countdown_template_2_item_height',
                    'sale_countdown_template_2_item_width',
                    'sale_countdown_template_2_value_color',
                    'sale_countdown_template_2_value_background',
                    'sale_countdown_template_2_value_fontsize',
                    'sale_countdown_template_2_time_unit_color',
                    'sale_countdown_template_2_time_unit_background',
                    'sale_countdown_template_2_time_unit_fontsize',
                    'sale_countdown_template_2_time_unit_position',

                    'sale_countdown_template_3_value_color',
                    'sale_countdown_template_3_value_background',
                    'sale_countdown_template_3_value_fontsize',
                    'sale_countdown_template_3_time_unit_color',
                    'sale_countdown_template_3_time_unit_background',
                    'sale_countdown_template_3_time_unit_fontsize',

                    'sale_countdown_template_4_value_border_color1',
                    'sale_countdown_template_4_value_border_color2',
                    'sale_countdown_template_4_value_color',
                    'sale_countdown_template_4_value_background',
                    'sale_countdown_template_4_value_fontsize',
                    'sale_countdown_template_4_value_border_width',
                    'sale_countdown_template_4_value_diameter',
                    'sale_countdown_template_4_time_unit_color',
                    'sale_countdown_template_4_time_unit_background',
                    'sale_countdown_template_4_time_unit_fontsize',
                    'sale_countdown_template_4_time_unit_position',
                    'sale_countdown_circle_smooth_animation',

                    'sale_countdown_template_5_item_border_width',
                    'sale_countdown_template_5_item_diameter',
                    'sale_countdown_template_5_value_color',
                    'sale_countdown_template_5_value_fontsize',
                    'sale_countdown_template_5_time_unit_color',
                    'sale_countdown_template_5_time_unit_fontsize',
                    'sale_countdown_template_5_date_border_color1',
                    'sale_countdown_template_5_date_border_color2',
                    'sale_countdown_template_5_date_background',
                    'sale_countdown_template_5_hour_border_color1',
                    'sale_countdown_template_5_hour_border_color2',
                    'sale_countdown_template_5_hour_background',
                    'sale_countdown_template_5_minute_border_color1',
                    'sale_countdown_template_5_minute_border_color2',
                    'sale_countdown_template_5_minute_background',
                    'sale_countdown_template_5_second_border_color1',
                    'sale_countdown_template_5_second_border_color2',
                    'sale_countdown_template_5_second_background',

                    'sale_countdown_template_6_time_unit_position',
                    'sale_countdown_template_6_value_width',
                    'sale_countdown_template_6_value_height',
                    'sale_countdown_template_6_value_border_radius',
                    'sale_countdown_template_6_value_fontsize',
                    'sale_countdown_template_6_value_color1',
                    'sale_countdown_template_6_value_color2',
                    'sale_countdown_template_6_value_background1',
                    'sale_countdown_template_6_value_background2',
                    'sale_countdown_template_6_value_box_shadow',
                    'sale_countdown_template_6_value_cut_color',
                    'sale_countdown_template_6_value_cut_behind',
                    'sale_countdown_template_6_time_unit_color',
                    'sale_countdown_template_6_time_unit_fontsize',
                    'sale_countdown_template_6_time_unit_grid_gap',

                    'sale_countdown_template_7_time_unit_position',
                    'sale_countdown_template_7_value_width',
                    'sale_countdown_template_7_value_height',
                    'sale_countdown_template_7_value_border_radius',
                    'sale_countdown_template_7_value_fontsize',
                    'sale_countdown_template_7_value_color1',
                    'sale_countdown_template_7_value_color2',
                    'sale_countdown_template_7_value_background1',
                    'sale_countdown_template_7_value_background2',
                    'sale_countdown_template_7_value_box_shadow',
                    'sale_countdown_template_7_value_cut_color',
                    'sale_countdown_template_7_value_cut_behind',
                    'sale_countdown_template_7_time_unit_color',
                    'sale_countdown_template_7_time_unit_fontsize',
                    'sale_countdown_template_7_time_unit_grid_gap',

                    'sale_countdown_single_product_sticky',
                    'sale_countdown_single_product_position',
                    'sale_countdown_mobile_resize',
                    'sale_countdown_loop_resize',
                    'sale_countdown_sticky_resize',

                    'sale_countdown_archive_page_enable',
                    // 'sale_countdown_archive_page_show',
                    'sale_countdown_archive_page_assign',
                    'sale_countdown_archive_page_position',

                    'sale_countdown_progress_bar_message',
                    'sale_countdown_progress_bar_type',
                    'sale_countdown_progress_bar_order_status',
                    'sale_countdown_progress_bar_position',
                    'sale_countdown_progress_bar_template',
                    'sale_countdown_progress_bar_message_position',
                    'sale_countdown_progress_bar_template_1_background',
                    'sale_countdown_progress_bar_template_1_color',
                    'sale_countdown_progress_bar_template_1_message_color',
                    'sale_countdown_progress_bar_template_1_border_radius',
                    'sale_countdown_progress_bar_template_1_height',
                    'sale_countdown_progress_bar_template_1_width',
                    'sale_countdown_progress_bar_template_1_width_type',
                    'sale_countdown_progress_bar_template_1_font_size',

                    'sale_countdown_wrap_border_radius_in_single',
                    'sale_countdown_wrap_border_color_in_single',
                    'sale_countdown_progress_bar_position_in_single',
                    'sale_countdown_progress_bar_message_position_in_single',

                    'sale_countdown_upcoming_enable',
                    'sale_countdown_upcoming_progress_bar_enable',
                    'sale_countdown_upcoming_message',

                    'sale_countdown_sticky_width',
                    'sale_countdown_sticky_time_unit_disable',
                    'sale_countdown_add_to_cart_button',
                ];
                for (var eleName in myArr) {
                    temp = [];
                    jQuery('[name="' + myArr[eleName] + '[]"]').map(function () {
                        temp.push(jQuery(this).val());
                    });
                    myData[myArr[eleName]] = temp;
                }
            }
            if (save_update_key) {
                myData['update_key'] = jQuery('#auto-update-key').val();
            }
            myData['save_all_changes'] = save_all_change ? 'yes' : '';
            myData['save_update_key'] = save_update_key ? 'yes' : '';
            myData['woo_ctr_nonce_field'] = jQuery('#_woo_ctr_settings_page_nonce_field').val();
            jQuery.ajax({
                type: 'post',
                dataType: 'json',
                url: 'admin-ajax.php?action=woo_sctr_save_settings',
                data: myData,
                beforeSend: function () {
                    // console.log(myData);
                },
                success: function (response) {
                    // console.log(response);
                    jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                    if (response.status === 'successful') {
                        if (save_update_key) {
                            location.reload();
                        } else if (save_all_change) {
                            jQuery('.woo-sctr-save-sucessful-popup').animate({'bottom': '45px'}, 500);
                            setTimeout(function () {
                                jQuery('.woo-sctr-save-sucessful-popup').animate({'bottom': '-300px'}, 200);
                            }, 5000);
                        }
                    } else {
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function (err) {
                    console.log(err);
                    jQuery('.woo-sctr-save').removeClass('woo-sctr-adding');
                    alert(err.responseText);
                }
            });
        })
    }

    //Auto update
    jQuery('.villatheme-get-key-button').one('click', function (e) {
        let v_button = jQuery(this);
        v_button.addClass('loading');
        let data = v_button.data();
        let item_id = data.id;
        let app_url = data.href;
        let main_domain = window.location.hostname;
        main_domain = main_domain.toLowerCase();
        let popup_frame;
        e.preventDefault();
        let download_url = v_button.attr('data-download');
        popup_frame = window.open(app_url, "myWindow", "width=380,height=600");
        window.addEventListener('message', function (event) {
            /*Callback when data send from child popup*/
            let obj = jQuery.parseJSON(event.data);
            let update_key = '';
            let message = obj.message;
            let support_until = '';
            let check_key = '';
            if (obj['data'].length > 0) {
                for (let i = 0; i < obj['data'].length; i++) {
                    if (obj['data'][i].id == item_id && (obj['data'][i].domain == main_domain || obj['data'][i].domain == '' || obj['data'][i].domain == null)) {
                        if (update_key == '') {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        } else if (support_until < obj['data'][i].support_until) {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        }
                        if (obj['data'][i].domain == main_domain) {
                            update_key = obj['data'][i].download_key;
                            break;
                        }
                    }
                }
                if (update_key) {
                    check_key = 1;
                    jQuery('.villatheme-autoupdate-key-field').val(update_key);
                }
            }
            v_button.removeClass('loading');
            if (check_key) {
                jQuery('<p><strong>' + message + '</strong></p>').insertAfter(".villatheme-autoupdate-key-field");
                jQuery(v_button).closest('form').submit();
            } else {
                jQuery('<p><strong> Your key is not found. Please contact support@villatheme.com </strong></p>').insertAfter(".villatheme-autoupdate-key-field");
            }
        });
    });
});