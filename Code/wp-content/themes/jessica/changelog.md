# Jessica Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.8.3 - 28 February 2020
### Changed
- Auto set frontpage layout
- Add group block support
- Fixed Sidebar not showing on homepage

### Files Added
- index.php

### Files Modified
- /functions.php
- /front-page.php

## 1.8.2 - 30 July 2019
### Changed
- Remove error notice when on category and tag pages

### Files Modified
- /functions.php

## 1.8.1 - 3 July 2019
### Changed
- Synchronize StudioPress and 9seeds theme versions.

## 1.8.0 - 13 June 2019
### Changed
- Updated depreciated function for genesis_get_additional_image_sizes()
### Files Modified
- /lib/widgets/wsm-featured-page.php
- /lib/widgets/wsm-featured-post.php

## [1.7.1] - 3 April 2019
### Changed
- Fixed template fallback for gutenberg support on front-page.php
- Fixed error on template landing
### Files Modified
- /front-page.php
- /lib/helper.php

### Changed
## [1.7.0] - 1 April 2019
### Added
- Added Woocommerce block support
- Added ability to use page as homepage for block based homepages
- Added margin on cover align left and align right
- Added hover effect on button
- Added ability to choose link color and acsent from customizer
- Added gutenberg editor styles
- Moved Accessibility, Custom Logo and Menus into Genesis Config

### Changed
- Fixed align wide and align full issue
- Fixed wrong submit button position
- Updated Gravity Form Style for input text
- Updated product grid view under 780px
- Removed page title on category
- Removed horizontal scroll on fullwidth page
- Removed php create_function
- Removed ancient IE fallback

### Files Modified
- /style.css
- /style-editor.css
- /front-page.php
- /function.php
- /config/accessiibility.php
- /config/custom-logo.php
- /config/menus.php
- /editor.js
- /lib/helper.php
- /lib/admin/customize.php

## [1.6.0] - 31 January 2019
### Changed
- Add Landing template
- Add Blocks template

### Files Added
- /template/blocks.php
- /template/landing.php
- /style.css (Version bump only)

## [1.5.0] - 20 December 2018
### Changed
- Add Gutenberg support
- Update style for Gutenberg

### Files Modified
- /function.php
- /style.css

## [1.4.0] - 12 July 2018
### Changed
- Update comment form for GDPR cookie consent checkbox
- Update comment form for screen reader accessibility (form labels instead of place holder text).

### Files Modified
- /lib/structure/comment-form.php

### Files With trivial string replacments
- /init.php
- /shop/exchange.php
- /shop/woocommerce.php
- /shop/wpec.php
- /structure/before-header.php
- /structure/footer.php
- /structure/sidebar.php
- style.css
- style-rtl.css

## [1.3.5] - 2018-03-20
### Changed
- Fixes for WooCommerce 3.3 changes to default markup

### Files Modified
- /style.css

## [1.3.4] - 2017-09-19
- Changed update URL domain to https://9seeds.com
- Changed cache name to make updates from previous versions run more smoothly
- Disabled SSL for cURL, for servers which can't cURL from SSL sites
- Changed URL domain in "Update Downloads and Instructions" to https://9seeds.com

### Files Modified
- /style.css
- /lib/functions/update.php

## 1.3.3 - 4 May 2017
* WooCommerce 3.0 compatibility

## [1.3.2] - 11 Apr 2017
* Change CSS for WooCommerce compatibility
* Fix WooCommerce gallery display issue
* Update WooCommerce template files for Woo 3.0+

### Files Modified
* /style.css
* /lib/shop/woocommerce.php

## [1.3.1] - 02 Aug 2016
* Change CSS for Genesis E-News Widget compatibility
* Fix WooCommerce coupon code CSS bug in cart
* Update Soliloquy plugin install script

### Files Modified
* /style.css
* /style-rtl.css
* /lib/languages/*
* /lib/plugins/plugins.php

## [1.3.0] - 07 Mar 2016
* Change Genesis Simple Sidebar related logic to be compatible with Genesis 2.2.7
* Update Soliloquy plugin install script

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /lib/languages/*
* /lib/plugins/class-tgm-plugin-activation.php
* /lib/plugins/plugins.php
* /lib/structure/sidebar.php

## [1.2.10] - 05 Feb 2016
* Fix for Genesis Connect for WooCommerce with Genesis Simple Sidebars compatibility
* Fix typo in Social Widget code

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /lib/structure/sidebar.php
* /lib/widgets/widget-social.php

## [1.2.9] - 14 Jan 2016
* Modify update notification script to prevent warnings in some hosting environments
* Improve widget contructor method
* Update included Genericons to version 3.4.1

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /genericons/*
* /lib/widgets/call-to-action.php
* /lib/widgets/widget-social.php
* /lib/widgets/wsm-banner.php

## [1.2.8] - 20 Oct 2015
* Fix bug in iThemes Exchange and WP eCommerce product layout
* Add rem measurements to CSS font-size rules for improved accessibility
* Change Google font import from style sheet to enqueue function
* Fix bug in update notification script
* Various minor bug fixes

### Files Modified
* /style.css
* /style-rtl.css
* /languages/*
* lib/init.php
* /lib/functions/update.php
* /shop/woocommerce.php

## [1.2.7] - 03 Sep 2015
* Simplify WooCommerce integration
* Add full Genesis 2.2+ accessibility support
* Switch to built-in Genesis mobile responsive meta tag

### Files Modified
* /style.css
* /style-rtl.css
* /functions.php
* /languages/*
* lib/init.php
* /shop/woocommerce.php
* /woocommerce/content-product.php (file removed)
* /woocommerce/loop/add-to-cart.php (file removed)
* /woocommerce/single-product/meta.php
* /woocommerce/single-product/add-to-cart/* (directory removed)

## [1.2.6] - 22 Aug 2015
* Fix WooCommerce variable product bug

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /languages/*
* /woocommerce/single-product/add-to-cart/variable.php

## [1.2.5] - 11 Aug 2015
* Fix widget compatibility for WordPress version 4.3
* Update theme template files for WooCommerce version 2.4.x compatibility

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /lib/widgets/call-to-action.php
* /lib/widgets/widet-social.php
* /lib/widgets/wsm-banner.php
* /woocommerce/content-product.php
* /woocommerce/single-product/add-to-cart/variable.php

## [1.2.4] - 03 Aug 2015
* Fix bug in header search display toggle

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /lib/structure/before-header.php

## [1.2.3] - 01 Aug 2015
* CSS fix for color scheme list bullets on WooCommerce pages
* Fix Google Font compatibility with SSL/HTTPS
* Renamed home.php file to front-page.php file for simplicity
* Add full WPML plugin compatibility

### Files Modified
* /style.css
* /style-rtl.css
* /home.php (file removed)
* /fron-page.php (new file added)
* /wpml-config.xml (new file added)
* /languages/*
* /lib/widgets/call-to-action.php
* /lib/widgets/widget-social.php
* /lib/widgets/wsm-banner.php
* /lib/widgets/wsm-featured-page.php
* /lib/widgets/wsm-featured-post.php

## [1.2.2] - 07 May 2015
* Security Fix for Genericons package (same fix as Jetpack, etc.)

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /lib/genericons/example.html (file removed)

## [1.2.1] - 30 Apr 2015
* Fix CSS bug induced by Soliloquy style changes
* CSS fix for WooCommerce update
* Remove rem units from style sheet for simplicity
* Fix comment form to use placeholders without script requirement

### Files Modified
* /style.css
* /style-rtl.css
* /languages/*
* /lib/structure/comment-form.php

## [1.2.0] - 11 Mar 2015
* Fix bug in style sheet enqueueing
* Convert to new Gravity Forms built-in placeholder functionality
* Add template file for iTHemes Exchange to use thumbnail image size on store archive pages instead of large image size
* Enable shortcode use in footer settings fields
* Various minor bug fixes

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /functions.php
* /taxonomy-it_exchange_category.php
* /taxonomy-it_exchange_tag.php
* /taxonomy-product_tag.php
* /wpsc-single_product.php
* /exchange/content-store/elements/featured-image.php (new file added)
* /lib/init.php
* lib/admin/jessica-theme-settings.php
* /lib/functions/gforms-placeholder.php (file removed)
* /lib/functions/metaboxes.php
* /lib/js/modernizr.min.js (file removed)
* /lib/structure/before-header.php
* /lib/structure/comment-form.php
* /lib/structure/footer.php
* /lib/widgets/widget-social.php
* /lib/widgets/wsm-banner.php

## [1.1.5] - 21 Feb 2015
* Fix CSS bug in main navigation hover state (See this Forum topic for more info: https://9seeds.com/forum/jessica-instructions/prevent-main-navigation-movement-on-hover/ )

### Files Modified
* /style.css
* /style-rtl.css

## [1.1.4] - 19 Feb 2015
* Resolve WooCommerce template version warning
* Explicitly declare WooCommerce support if WooCommerce is activated

### Files Modified
* /style.css (version number only)
* /style-rtl.css (version number only)
* /taxonomy-product_tag.php
* /languages/en_US.mo
* /languages/en_US.po
* /languages/jessica.pot
* /lib/shop/woocommerce.php
* /woocommerce/single-product/add-to-cart/variable.php

## [1.1.3] - 13 Jan 2015
* Change style sheet character encoding to UTF-8
* Bug fix in WooCommerce single product view

### Files Modified
* /style.css (character encoding only)
* /style-rtl.css (version number only)
* /woocommerce/content-product.php

## [1.1.2] - 02 Dec 2014
* Fix bug with Android display of mobile menu icon
* Update Genericons font to latest version
* Add missing strings to text domain

### Files Modified
* /style.css
* /style-rtl.css
* /languages/en_US.po
* /languages/en_US.mo
* /languages/jessica.pot
* /lib/genericons/* (update all files in directory)
* /lib/widgets/widget-social.php
* /lib/widgets/wsm-banner.php
* /woocommerce/loop/add-to-cart.php
* /woocommerce/single-product/add-to-cart/grouped.php
* /woocommerce/single-product/add-to-cart/simple.php
* /woocommerce/single-product/add-to-cart/variable.php

## [1.1.1] - 13 Nov 2014
* Bug fix for imported fonts

### Files Modified
* /style.css
* /style-rtl.css

## [1.1.0] - 12 Nov 2014
* Added internationalization (i18n) support by wrapping all front-end facing text strings in a translation function
* Added text domain in style.css and loaded text domain in functions.php
* Added .pot file as basis for translations
* Add .rtl stylesheet to be conditionally enqueued (instead of style.css) when WordPress language is set to an RTL script

### Files Modified
* /style.css
* /style-rtl.css (new file added)
* /functions.php
* /languages/*.pot (new directory and file added)

## [1.0.2] - 10 Nov 2014
* Fix Gravity Forms Placeholder code to work with multiple forms on a single page
* Load gforms-placeholder script after Modernizr script

### Files Modified
* /style.css (version number only)
* /lib/functions/gforms-placeholder.php

## [1.0.1] - 06 Oct 2014
* Fix Social Widget not to display icons when URL field empty

### Files Modified
* /style.css
* /style-rtl.css
* /functions.php
* /languages/*.pot

## [1.0.1] - 06 Oct 2014
* Fix Social Widget not to display icons when URL field empty

### Files Modified
* /style.css (version number only)
* /lib/widgets/widget-social.php

## [1.0.0] - 29 Sept 2014
* Initial theme release
