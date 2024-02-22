'use strict';
jQuery(document).ready(function () {
    jQuery('.vi-ui.vi-ui-main.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });
    /*Setup tab*/
    let tabs,
        tabEvent = false,
        initialTab = 'general',
        navSelector = '.vi-ui.vi-ui-main.menu',
        panelSelector = '.vi-ui.vi-ui-main.tab',
        panelFilter = function () {
            jQuery(panelSelector + ' a').filter(function () {
                return jQuery(navSelector + ' a[title=' + jQuery(this).attr('title') + ']').size() != 0;
            });
        };
    // Initializes plugin features
    jQuery.address.strict(false).wrap(true);

    if (jQuery.address.value() == '') {
        jQuery.address.history(false).value(initialTab).history(true);
    }
    // Address handler
    jQuery.address.init(function (event) {

        // Adds the ID in a lazy manner to prevent scrolling
        jQuery(panelSelector).attr('id', initialTab);

        panelFilter();

        // Tabs setup
        tabs = jQuery('.vi-ui.vi-ui-main.menu')
            .vi_tab({
                history: true,
                historyType: 'hash'
            });

        // Enables the plugin for all the tabs
        jQuery(navSelector + ' a').click(function (event) {
            tabEvent = true;

            tabEvent = false;
            return true;
        });

    });
    jQuery('.ui-sortable').sortable({
        placeholder: 'sctv-place-holder',
    });
    jQuery('.woo-stcr-checkout-countdown-discount-free-products').select2({
        placeholder: 'Please fill your product name',
        closeOnSelect: false,
        ajax: {
            url: "admin-ajax.php?action=sctv_search_product_free",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 2
    });

    function handleDropdown() {
        jQuery('.vi-ui.accordion')
            .accordion('refresh')
        ;
        jQuery('.vi-ui.dropdown').unbind().dropdown();
        jQuery('.woo-stcr-checkout-countdown-discount-type').dropdown({
            onChange: function (val) {
                if (val === 'none') {
                    jQuery('#woo-stcr-checkout-countdown-discount-amount').removeAttr('required').addClass('woo-sctr-hidden');
                    if (jQuery('#woo-stcr-checkout-countdown-change').val() === 'auto_change') {
                        jQuery('#woo-stcr-checkout-countdown-change').val('none').change();
                    }
                } else {
                    jQuery('#woo-stcr-checkout-countdown-discount-amount').attr('required', 'required').removeClass('woo-sctr-hidden');
                    if (jQuery('#woo-stcr-checkout-countdown-change').val() === 'auto_change') {
                        jQuery('.woo-stcr-checkout-countdown-change-auto-change-warning').addClass('woo-sctr-hidden');
                        jQuery('.woo-stcr-checkout-countdown-change-auto-change').removeClass('woo-sctr-hidden');
                        jQuery('#woo-stcr-checkout-countdown-change-auto-change-detail-value , #woo-stcr-checkout-countdown-change-auto-change-time').attr('required', 'required');
                    }
                }
            }
        });
        jQuery('#woo-stcr-checkout-countdown-change').unbind().on('change', function () {
            let val = jQuery(this).val();
            jQuery('.woo-stcr-checkout-countdown-change-wrap').addClass('woo-sctr-hidden');
            jQuery('#woo-stcr-checkout-countdown-change-auto-change-detail-value , #woo-stcr-checkout-countdown-change-auto-change-time').removeAttr('required');
            switch (val) {
                case 'auto_change':
                    if (jQuery('#woo-stcr-checkout-countdown-discount-type').val() === 'none') {
                        jQuery('.woo-stcr-checkout-countdown-change-auto-change-warning').removeClass('woo-sctr-hidden');
                        return false;
                    }
                    jQuery('.woo-stcr-checkout-countdown-change-auto-change-warning').addClass('woo-sctr-hidden');
                    jQuery('.woo-stcr-checkout-countdown-change-auto-change').removeClass('woo-sctr-hidden');
                    jQuery('#woo-stcr-checkout-countdown-change-auto-change-detail-value , #woo-stcr-checkout-countdown-change-auto-change-time').attr('required', 'required');
                    break;
                case 'custom':
                    jQuery('.woo-stcr-checkout-countdown-change-wrap.woo-stcr-checkout-countdown-change-custom').removeClass('woo-sctr-hidden');
                    break;
                default:
            }
        });

        jQuery('.woo-stcr-checkout-countdown-change-auto-change-time-type').dropdown({
            onChange: function (val) {
                jQuery('#woo-stcr-checkout-countdown-change-auto-change-time').val(null);
                if (val === 'minute') {
                    jQuery('#woo-stcr-checkout-countdown-change-auto-change-time').attr('min', 1);
                } else {
                    jQuery('#woo-stcr-checkout-countdown-change-auto-change-time').attr('min', 15);
                }
            }
        });
        jQuery('#woo-stcr-checkout-countdown-change-auto-change-time').unbind().change(function () {
            let div_parent = jQuery(this).parent().parent();
            div_parent.find('.wotv-error-auto-change-time').addClass('woo-sctr-hidden');
            if (!jQuery(this).val()) {
                div_parent.find('.wotv-error-auto-change-time-no-value').removeClass('woo-sctr-hidden');
                return false;
            }
            let min_time, max_time, current_time_t,
                current_time = parseInt(jQuery(this).val()),
                minute_default = jQuery('#woo-stcr-checkout-countdown-time-minute').val() ? parseInt(jQuery('#woo-stcr-checkout-countdown-time-minute').val()) : 0,
                second_default = jQuery('#woo-stcr-checkout-countdown-time-second').val() ? parseInt(jQuery('#woo-stcr-checkout-countdown-time-second').val()) : 0,
                auto_change_type = jQuery('#woo-stcr-checkout-countdown-change-auto-change-time-type').val();
            max_time = minute_default * 60 + second_default;
            if (max_time === 0) {
                jQuery('.woo-stcr-checkout-countdown-time-warning').removeClass('woo-sctr-hidden');
                return false;
            } else {
                jQuery('.woo-stcr-checkout-countdown-time-warning').addClass('woo-sctr-hidden');
            }
            if (auto_change_type === 'minute') {
                min_time = 1;
                current_time_t = current_time * 60;
            } else {
                min_time = 15;
                current_time_t = current_time;
            }
            if (current_time < min_time) {
                jQuery(this).val(min_time);
                div_parent.find('.wotv-error-auto-change-time-minimum-second').removeClass('woo-sctr-hidden');
                return false;
            }
            if (current_time_t >= max_time) {
                jQuery(this).val(null);
                div_parent.find('.wotv-error-auto-change-time-over-time').removeClass('woo-sctr-hidden');
                return false;
            }
        });

        jQuery(document).unbind().on('keyup', '.woo-sctr-message-checkout-countdown-timer', function () {
            let message = jQuery(this).val();
            let check_countdown = message.split('{countdown_timer}');
            if (check_countdown.length < 2) {
                jQuery(this).parent().find('.woo-sctr-warning-message-checkout-countdown-timer').removeClass('woo-sctr-hidden');
            } else {
                jQuery(this).parent().find('.woo-sctr-warning-message-checkout-countdown-timer').addClass('woo-sctr-hidden');
            }
        });
        jQuery(document).unbind().on('change', '#woo-stcr-checkout-countdown-reset', function () {
            let val = jQuery(this).val();
            if (!val) {
                jQuery(this).val(30);
            }
        });
        jQuery('.woo-stcr-checkout-countdown-display-on-page').dropdown({
            onChange: function (val) {
                if (jQuery.inArray('assign', val) !== -1) {
                    jQuery('.woo-stcr-checkout-countdown-assign-page').removeClass('woo-sctr-hidden');
                } else {
                    jQuery('.woo-stcr-checkout-countdown-assign-page').addClass('woo-sctr-hidden');
                }
            }
        });
        jQuery('.woo-stcr-checkout-test-mode-reset').unbind().on('click', function (e) {
            if (!jQuery('#woo-stcr-checkout-test-mode-enable').prop('checked')) {
                return false;
            }
            jQuery.ajax({
                type: 'POST',
                url: 'admin-ajax.php?action=sctv_test_mode_reset',
                beforeSend: function () {
                    jQuery('.woo-stcr-checkout-test-mode-reset').addClass('loading');
                    jQuery('.woo-stcr-checkout-test-mode-message').remove();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        jQuery('.woo-stcr-checkout-test-mode-reset').after('<span class="description woo-stcr-checkout-test-mode-message" style="color: green;">' + response.message + '</span>');
                    } else {
                        jQuery('.woo-stcr-checkout-test-mode-reset').after('<span class="description woo-stcr-checkout-test-mode-message" style="color: red;">' + response.message + '</span>');
                    }
                    setTimeout(function () {
                        jQuery('.woo-stcr-checkout-test-mode-message').remove();
                    }, 3000);
                },
                error: function (err) {
                    console.log(JSON.stringify(err));
                },
                complete: function () {
                    jQuery('.woo-stcr-checkout-test-mode-reset').removeClass('loading');
                }
            });
            e.stopPropagation();
        });
        jQuery('.woo-ctr-settings-checkout-page-btnsave').unbind().on('click', function () {
            jQuery('.woo-stcr-checkout-countdown-warning').addClass('woo-sctr-hidden');
            let minute_default = jQuery('#woo-stcr-checkout-countdown-time-minute').val() ? parseInt(jQuery('#woo-stcr-checkout-countdown-time-minute').val()) : 0,
                second_default = jQuery('#woo-stcr-checkout-countdown-time-second').val() ? parseInt(jQuery('#woo-stcr-checkout-countdown-time-second').val()) : 0;
            if (minute_default === 0 && second_default === 0) {
                jQuery('.woo-stcr-checkout-countdown-time-warning').removeClass('woo-sctr-hidden');
                return false;
            }
            if (!jQuery('#woo-stcr-checkout-countdown-free-ship').prop('checked') && jQuery('#woo-stcr-checkout-countdown-discount-type').val() === 'none') {
                jQuery('.woo-stcr-checkout-offer-warning').removeClass('woo-sctr-hidden');
                return false;
            }
            jQuery(this).attr('type', 'submit');
        });
    }

    handleDropdown();

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
        });
        jQuery('.woo-stcr-checkout-button-color').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });
        jQuery('.woo-stcr-checkout-button-background').iris({
            change: function (event, ui) {
                jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            }
        }).on('keyup', function () {
            jQuery(this).parent().find('.color-picker').css({'background': jQuery(this).val()});
        });

        jQuery('body').on('click', function () {
            jQuery('.iris-picker').hide();
        });
    }

    handleColorPicker();

    // handle checkbox to save
    function handleCheckbox() {
        jQuery('.vi-ui.checkbox').unbind().checkbox();
        jQuery('input[type="checkbox"]').unbind().on('change', function () {
            if (jQuery(this).prop('checked')) {
                jQuery(this).val('1');
                jQuery(this).parent().find('input[type="hidden"]').val('1');
                if (jQuery(this).hasClass('woo-stcr-checkout-test-mode-enable')) {
                    jQuery('.woo-stcr-checkout-test-mode-reset').removeClass('disabled');
                }
            } else {
                jQuery(this).val('');
                jQuery(this).parent().find('input[type="hidden"]').val('');
                if (jQuery(this).hasClass('woo-stcr-checkout-test-mode-enable')) {
                    jQuery('.woo-stcr-checkout-test-mode-reset').addClass('disabled');
                }
            }
        });

    }

    handleCheckbox();

    // duplicate item to save
    function duplicateItem() {
        jQuery('.woo-stcr-checkout-countdown-change-custom input[type ="number"]').unbind().change(function () {
            if (!jQuery(this).val()) {
                jQuery(this).val(0);
            }
        });
        jQuery('.woo-stcr-checkout-countdown-time-second-class').unbind().on('change', function () {
            let check_second = parseInt(jQuery(this).val());
            if (!check_second) {
                jQuery(this).val(0);
            } else if (check_second < 0) {
                jQuery(this).val(0);
            } else if (check_second > 59) {
                jQuery(this).val(59);
            }
        });
        jQuery('.woo-stcr-checkout-countdown-decrease-custom-message').unbind().keyup(function () {
            let message_custom = jQuery(this).val();
            if (message_custom.indexOf('{countdown_timer}') === -1) {
                jQuery(this).parent().find('.woo-sctr-warning-message-checkout-countdown-timer').removeClass('woo-sctr-hidden');
            } else {
                jQuery(this).parent().find('.woo-sctr-warning-message-checkout-countdown-timer').addClass('woo-sctr-hidden');
            }
        });
        jQuery('.woo-stcr-checkout-countdown-decrease-custom-minute').unbind().change(function () {
            let minute, minute_check;
            minute = parseInt(jQuery(this).val());
            minute_check = parseInt(jQuery('#woo-stcr-checkout-countdown-time-minute').val());
            if (!minute) {
                minute = 0;
            } else if (minute < 0) {
                minute = 0;
            } else if (minute >= minute_check) {
                minute = minute_check - 1;
            }
            jQuery(this).val(minute);
        });
        jQuery('.woo-sctr-button-edit-duplicate').unbind().on('click', function (e) {
            e.stopPropagation();
            let new_id = jQuery('.woo-stcr-checkout-countdown-change-custom-wrap').length;
            let current = jQuery(this).parent().parent().parent();
            let current_minute = parseInt(jQuery('.woo-stcr-checkout-countdown-decrease-custom-minute').val());
            let newRow = current.clone();
            var $now = Date.now();
            newRow.find('.woo-stcr-checkout-countdown-decrease-custom-ids').val($now);
            newRow.attr('data-custom_id', new_id);
            newRow.find('.vi-ui.checkbox').unbind().checkbox();
            newRow.find('.woo-stcr-checkout-countdown-decrease-custom-id').html(new_id + 1);
            newRow.find('.woo-stcr-checkout-countdown-decrease-custom-minute').val(current_minute - 1 > 0 ? current_minute - 1 : 0);
            jQuery('.woo-stcr-checkout-countdown-change-custom-wrap-wrap').append(newRow);
            duplicateItem();
            removeItem();
            handleDropdown();
            handleCheckbox();

            e.stopPropagation();
        });

    }

    duplicateItem();


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

    removeItem();
});