/* 
======================================
        Table of Content
======================================
1)  gallery
2)  header fixed
3)  Search toggle
4)  preloader
5)  header options custom
6)  responsive menu
7)  mobile menu
8)  banner slider
9)  testimonial slider
10) blog slider
11) Brand logo slider
12) scrol to top button
13) Countdown
14) Newsletter popup window
15) Theme Features
16) light-dark mode
17) color option
======================================
*/
(function($){
  "use strict";
 
  jQuery(document).ready(function(e) {
      background();
  });
  function background()
  {
    var img=$('.has_bg_image');
    img.css('background-image', function () {
      var bg = ('url(' + $(this).data('background') + ')');
      return bg;
    });
  }

  //==================== header fixed ====================//
  var fixed_top = $(".header-section");
  $(window).on("scroll", function(){
      if( $(window).scrollTop() > 50){  
          fixed_top.addClass("animated fadeInDown menu-fixed");
      }
      else{
          fixed_top.removeClass("animated fadeInDown menu-fixed");
      }
  });
  

  //================ Search ============================//

  jQuery(window).resize();


  $(window).on('load', function(){

      //==================== preloader====================//
      $("#preloader").delay(300).animate({
        "opacity" : "0"
        }, 500, function() {
        $("#preloader").css("display","none");
    });

     responsiveSize();
     $(window).resize(responsiveSize);

    //==================== header options custom ====================//
    var fixed_top = $(".site-container");
    $(window).on("scroll", function(){
      
      if( $(this).scrollTop() > 50 ){  
        fixed_top.addClass("header-close");
      }
      else{
        fixed_top.removeClass("header-close");
      }
    });

  });

  //==================== responsive menu ====================//
  function responsiveSize(){
    if (window.matchMedia('(max-width: 1199px)').matches) {
      $(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function() {
        var element = $(this).parent("li");
        if (element.hasClass("open")) {
          element.removeClass("open");
          element.find("li").removeClass("open");
          element.find("ul").slideUp(500,"linear");
        }
        else {
          element.addClass("open");
          element.children("ul").slideDown();
          element.siblings("li").children("ul").slideUp();
          element.siblings("li").removeClass("open");
          element.siblings("li").find("li").removeClass("open");
          element.siblings("li").find("ul").slideUp();
        }
      });
    }
  }

  //==================== mobile menu ====================//
  $(".menu-toggle").on("click", function() {
      $(this).toggleClass("is-active");
      $('.site-container').toggleClass("responsive-menu-fixed");
  });



  //============== testimonial slider =========================//
  $('.testimonial-slider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    appendArrows: '.testiarrow',
    arrows: true,
    dots: false
  });


  //==================== Product slider ====================//
  $('.home .product-slider .woocommerce  ul.products,.home2 .product-slider .woocommerce  ul.products').slick({
    dots: false,
    vertical: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
  });

  $('.home3 .product-slider .woocommerce  ul.products').slick({
    dots: false,
    vertical: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
  });



  //==================== bestseller Product slider ====================//
  $('.specialproduct-section .woocommerce  ul.products').slick({
    dots: false,
    vertical: true,
    verticalSwiping: true,
    slidesToScroll: 1,
    arrows: true,
  });   


  //============== blog slider =========================//
  $('.home .blog-slider,.home2 .blog-slider,.home3 .blog-slider').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  arrows: true,
  appendArrows: '.blogarrow',
  dots: false,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
      }
    },  
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
      }
    }
  ]
  });

  $('.home4 .blog-slider').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  arrows: true,
  appendArrows: '.home4blogarrow',
  dots: false,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
      }
    },  
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
      }
    }
  ]
  });

  //==================== Brand logo slider ====================//
  $('.brand-slider').slick({
      infinite: true,
      slidesToShow: tx_brandnumber,
      slidesToScroll: tx_brandscrollnumber,
      speed: tx_brandanimate,
      easing: 'linear',
      arrows: false,
      autoplay: tx_brandscroll,
      autoplaySpeed: tx_brandpause,
      swipeToSlide: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2
            }
          }
        ]
}); 


    // add/remove tab class
    jQuery(".product-section .nav-tabs li:first-child a").addClass("active");
    jQuery(".product-section .nav-tabs li:not(:first-child) a").click(function(){
      jQuery(".product-section .nav-tabs li:first-child a").removeClass("active");
    });
    // add/remove tab class
    jQuery(".product-section .tab-content .tab-pane:first-child").addClass("active show");
    jQuery(".product-section .tab-content .tab-pane:not(:first-child)").click(function(){
      jQuery(".product-section .tab-content .tab-pane:first-child").removeClass("active show");
    });
    
  //==================== Product Zoom & Slider====================//
   $('.main-product-image').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    dots: false,
    asNavFor: '.product-thumbnails'
  });

  $('.product-thumbnails').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: '.main-product-image',
    dots: false,
    arrows: false,
    focusOnSelect: true
  });

  $('.thumbnails').zoom();


  // debounce so filtering doesn't happen every millisecond
  function debounce( fn, threshold ) {
    var timeout;
    return function debounced() {
      if ( timeout ) {
        clearTimeout( timeout );
      }
      function delayed() {
        fn();
        timeout = null;
      }
      timeout = setTimeout( delayed, threshold || 100 );
    }
  }

  $(window).bind("load", function() {
    $('#all').click();
  });


  //==================== scrol to top button ====================//
  $(window).on("scroll", function() {
    if ($(this).scrollTop() > 200) {
        $(".scroll-to-top").fadeIn(200);
    } else {
        $(".scroll-to-top").fadeOut(200);
    }
  });

  $(".scroll-to-top").on("click", function(event) {
    event.preventDefault();
    $("html, body").animate({scrollTop: 0}, 800);
  });



  //==================== Countdown ====================//
$(document).ready(function($) {
 
  function animateElements() {
    $('.progressbar').each(function() {
      var elementPos = $(this).offset().top;
      var topOfWindow = $(window).scrollTop();
      var percent = $(this).find('.circle').attr('data-percent');
      var percentage = parseInt(percent, 10) / parseInt(100, 10);
      var animate = $(this).data('animate');
      if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
        $(this).data('animate', true);
        $(this).find('.circle').circleProgress({
          startAngle: -Math.PI / 2,
          value: percent / 100,
          thickness: 3,
          fill: {
            color: '#1B58B8'
          }
        }).on('circle-animation-progress', function(event, progress, stepValue) {
          $(this).find('div').text((stepValue * 100).toFixed() + "%");
        }).stop();
      }
    });
  }

  // Show animated elements
  animateElements();
  $(window).scroll(animateElements);
});

   //==================== Category view ====================//  
    //list layout view

    $('.list-layout-view').on('click', function(e) {
        $(this).addClass('active').siblings().removeClass('active');
        $('.content-sidebar-wrap').addClass("list-view");
        $(".content-sidebar-wrap .right-column ul.products li.product").addClass("full d-flex");
        setTimeout(function(){
            $(".content-sidebar-wrap").css("opacity","1");
        }, 500);
    });
   //grid layout view
    $('.grid-layout-view').on('click', function(e) {
        $(this).addClass('active').siblings().removeClass('active');
        $('.content-sidebar-wrap').removeClass("list-view");
        $(".content-sidebar-wrap .right-column ul.products li.product").removeClass("full d-flex");
    });    
  //==================== Newsletter popup window ====================//


  //=============== Video section ===================//
  $('.video-section .video').parent().click(function () {
    if($(this).children(".video-section .video").get(0).paused){        
      $(this).children(".video-section .video").get(0).play();   
      $(this).children(".video-section .playpause").fadeOut();
    } else {       
        $(this).children(".video-section .video").get(0).pause();
        $(this).children(".video-section .playpause").fadeIn();
    }
  });

    $(document).ready(function() {
         //Mobile Menu
    var mobileMenuWrapper = jQuery('.mobile-menu-container');
    mobileMenuWrapper.find('.menu-item-has-children').each(function(){
      var linkItem = jQuery(this).find('a').first();
      linkItem.after('<span class="icons"><i class="fa fa-plus icons"></i></span>');
    });
    //calculate the init height of menu
    var totalMenuLevelFirst = jQuery('.mobile-menu-container .nav-menu > li').length;
    var mobileMenuH = totalMenuLevelFirst*40 + 10; //40 is height of one item, 10 is padding-top + padding-bottom;
    
    jQuery('.mbmenu-toggler').on('click', function(){
      if(mobileMenuWrapper.hasClass('open')) {
        mobileMenuWrapper.removeClass('open');
        mobileMenuWrapper.animate({'height': 0}, 'fast');
      } else {
        mobileMenuWrapper.addClass('open');
        mobileMenuWrapper.animate({'height': mobileMenuH}, 'fast');
      }
    });
      //set the height of all li.menu-item-has-children items
    jQuery('.mobile-menu-container li.menu-item-has-children').each(function(){
      jQuery(this).css({'height': 40, 'overflow': 'hidden'});
    });
      //process the parent items
    jQuery('.mobile-menu-container li.menu-item-has-children').each(function(){
      var parentLi = jQuery(this);
      var dropdownUl = parentLi.find('ul.sub-menu').first();
      
      parentLi.find('.icons').first().on('click', function(){
        //set height is auto for all parents dropdown
        parentLi.parents('li.menu-item-has-children').css('height', 'auto');
        //set height is auto for menu wrapper
        mobileMenuWrapper.css({'height': 'auto'});
        
        var dropdownUlheight = dropdownUl.outerHeight() + 40;
        
        if(parentLi.hasClass('opensubmenu')) {
          parentLi.removeClass('opensubmenu');
          parentLi.animate({'height': 40}, 'fast', function(){
            //calculate new height of menu wrapper
            mobileMenuH = mobileMenuWrapper.outerHeight();
          });
          parentLi.find('svg').first().removeClass('fa-minus');
          parentLi.find('svg').first().addClass('fa-plus');
        } else {
          parentLi.addClass('opensubmenu');
          parentLi.animate({'height': dropdownUlheight}, 'fast', function(){
            //calculate new height of menu wrapper
            mobileMenuH = mobileMenuWrapper.outerHeight();
          });
          parentLi.find('svg').first().addClass('fa-minus');
          parentLi.find('svg').first().removeClass('fa-plus');
        }
        
      });
    });

       $('.site-footer .wrap,.footer-widgets .wrap').addClass('container').removeClass('wrap');
       $('.woocommerce-result-count').wrapAll('<div class="shop-pagination-before"></div>');
       $('.woocommerce-pagination').wrapAll('<div class="shop-pagination-before pagi-right"></div>');
       $('.shop-pagination-before').wrapAll('<div class="blog-pagination"></div>');
       $('.woocommerce-ordering').addClass('filter-selection shop-top');
       $('.collection-view').addClass('shop-top');
       $('.shop-top').wrapAll('<div class="product-page-filter"></div>');
       $('.woocommerce-checkout #order_review').insertAfter($('.woocommerce-checkout #customer_details .col-2'));
    });
    


})(jQuery);