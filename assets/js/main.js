;(function($){

$(document).ready(function(){

//========== HEADER ACTIVE STRATS ============= //
  var windowOn = $(window);
  windowOn.on('scroll', function () {
    var scroll = windowOn.scrollTop();
    if (scroll < 100) {
      $("#vl-header-sticky").removeClass("header-sticky");
    } else {
      $("#vl-header-sticky").addClass("header-sticky");
    }
  });
  
//========== HEADER ACTIVE ENDS ============= //

//========== MOBILE MENU STARTS ============= //
function initMobileMenu() {
  var vlMenuWrap = $('.vl-mobile-menu-active > ul').clone();
  var vlSideMenu = $('.vl-offcanvas-menu nav');
  vlSideMenu.append(vlMenuWrap);
  if ($(vlSideMenu).find('.sub-menu, .vl-mega-menu').length !== 0) {
    $(vlSideMenu).find('.sub-menu, .vl-mega-menu').parent().append('<button class="vl-menu-close"><i class="fas fa-chevron-right"></i></button>');
  }
  var sideMenuList = $('.vl-offcanvas-menu nav > ul > li button.vl-menu-close, .vl-offcanvas-menu nav > ul li.has-dropdown > a');
  $(sideMenuList).on('click', function (e) {
    e.preventDefault();
    if (!$(this).parent().hasClass('active')) {
      $(this).parent().addClass('active');
      $(this).siblings('.sub-menu, .vl-mega-menu').slideDown();
    } else {
      $(this).siblings('.sub-menu, .vl-mega-menu').slideUp();
      $(this).parent().removeClass('active');
    }
  });

  $(".vl-offcanvas-toggle").on('click', function () {
    $(".vl-offcanvas").addClass("vl-offcanvas-open");
    $(".vl-offcanvas-overlay").addClass("vl-offcanvas-overlay-open");
  });
  $(".vl-offcanvas-close-toggle, .vl-offcanvas-overlay").on('click', function () {
    $(".vl-offcanvas").removeClass("vl-offcanvas-open");
    $(".vl-offcanvas-overlay").removeClass("vl-offcanvas-overlay-open");
  });
}

initMobileMenu();

//========== MOBILE MENU ENDS ============= //

//========== PRICING AREA ============= //
$("#ce-toggle1").on('click', function(event) {
  $(".plan-toggle-wrap1").toggleClass("active");
});

$("#ce-toggle1").on('change', function() {
  if ($(this).is(":checked")) {
    $(".tab-content #yearly1").hide();
    $(".tab-content #monthly1").show();
  } else {
    $(".tab-content #yearly1").show();
    $(".tab-content #monthly1").hide();
  }
});
//========== PRICING AREA ============= //

//========== PAGE PROGRESS STARTS ============= // 
function initScrollProgress() {
  var progressPath = document.querySelector(".progress-wrap path");
  var pathLength = progressPath.getTotalLength();
  progressPath.style.transition = progressPath.style.WebkitTransition = "none";
  progressPath.style.strokeDasharray = pathLength + " " + pathLength;
  progressPath.style.strokeDashoffset = pathLength;
  progressPath.getBoundingClientRect();
  progressPath.style.transition = progressPath.style.WebkitTransition = "stroke-dashoffset 10ms linear";
  var updateProgress = function () {
    var scroll = $(window).scrollTop();
    var height = $(document).height() - $(window).height();
    var progress = pathLength - (scroll * pathLength) / height;
    progressPath.style.strokeDashoffset = progress;
  };
  updateProgress();
  $(window).on('scroll', updateProgress);
  var offset = 50;
  var duration = 550;
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > offset) {
      $(".progress-wrap").addClass("active-progress");
    } else {
      $(".progress-wrap").removeClass("active-progress");
    }
  });

  $(".progress-wrap").on('click', function (event) {
    event.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, duration);
    return false;
  });
}

initScrollProgress();

//========== PAGE PROGRESS STARTS ============= // 

//========== VIDEO POPUP STARTS ============= //
function initYouTubePopup() {
  if ($(".popup-youtube").length > 0) {
    $(".popup-youtube").magnificPopup({
      type: "iframe",
    });
  }
}

initYouTubePopup();
//========== VIDEO POPUP ENDS ============= //
AOS.init;
AOS.init({disable: 'mobile'});

//========== NICE SELECT ============= //
$('select').niceSelect();

//========== CASE IMAGE ============= //
});
//========== COUNTER UP============= //
function initCounterUp() {
  const ucounter = $('.counter');
  if (ucounter.length > 0) {
    ucounter.countUp();
  }
}
initCounterUp();

//========== TESTIMONIAL AREA ============= //

// SLIDER //
$('.testi-widget-slider').owlCarousel({
  loop:true,
  margin:30,
  nav:false,
  dots:true,
  items:10,
  autoplay:true,
  smartSpeed:2000,
  autoplayTimeout:3000,
  responsiveClass:true,
  responsive:{
      0:{
          items:1,
      },
      600:{
          items:2,
      },
      1000:{
          items:2,
      }
  }
});

// SLIDER //
$('.project-widget-slider').owlCarousel({
  loop:true,
  margin:30,
  nav:true,
  dots:false,
  mouseDrag:true,
  items:10,
  autoplay:true,
  navText:["<i class='fa-solid fa-angle-left'></i>" , "<i class='fa-solid fa-angle-right'></i>"],
  smartSpeed:3000,
  autoplayTimeout:4000,
  responsiveClass:true,
  responsive:{
      0:{
          items:1,
          nav:true,
      },
      600:{
          items:2,
      },
      1000:{
          items:3,
      }
  }
});

// SLIDER //
$(".hero2-slider-main").slick({
  autoplay:true,
  autoplaySpeed:1500,
  speed:1500,
  slidesToShow:1,
  slidesToScroll:1,
  pauseOnHover:false,
  dots:false,
  arrows:true,
  pauseOnDotsHover:true,
  cssEase:'linear',
  fade:true,
  draggable:true,
  prevArrow: $(".testimonial-prev-arrow-h2"),
  nextArrow: $(".testimonial-next-arrow-h2"), 
}); 

// SLIDER //
$(".testimonial2-slider-area").slick({
  slidesToShow: 2,
  slidesToScroll: 2,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".team-slider-area").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow"),
  nextArrow: $(".testimonial-next-arrow"), 
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

$('.slick-slider-content').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow"),
  nextArrow: $(".testimonial-next-arrow"), 
  responsive: [
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1
      }
    }
  ]
});

// SLIDER //
$(".hero3-slick-slider").slick({
  autoplay:true,
  autoplaySpeed:1500,
  speed:1000,
  slidesToShow:1,
  slidesToScroll:1,
  dots:false,
  arrows:true,
  cssEase:'linear',
  fade:true,
  draggable:true,
  prevArrow: $(".testimonial-prev-arrow-h3"),
  nextArrow: $(".testimonial-next-arrow-h3"), 
}); 

// SLIDER //
$(".testimonial-single-slider3").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true,
  prevArrow: $(".prev-arrow"),
  nextArrow: $(".next-arrow"),
  loop: true,
  autoplay:true,
  autoplayTimeout:500,
});

// SLIDER //
$(".project3-main-slider").slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow"),
  nextArrow: $(".testimonial-next-arrow"), 
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".team-slider-area4").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow-t4"),
  nextArrow: $(".testimonial-next-arrow-t4"), 
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".team5-slick-sliderarea").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow-t5"),
  nextArrow: $(".testimonial-next-arrow-t5"), 
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".testimonial-header-box").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
});

// SLIDER //
$(".project-single-boxarea").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
});

// SLIDER //
$(".service-slider-images").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true, 
  prevArrow: $(".testimonial-prev-arrow-sinner"),
  nextArrow: $(".testimonial-next-arrow-sinner"),
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
});

// SLIDER //
$(".hero7-slider-area").slick({
  autoplay:true,
  autoplaySpeed:1500,
  speed:1500,
  slidesToShow:1,
  slidesToScroll:1,
  pauseOnHover:false,
  dots:false,
  arrows:true,
  pauseOnDotsHover:true,
  cssEase:'linear',
  fade:true,
  draggable:true,
  prevArrow: $(".testimonial-prev-arrow-h2"),
  nextArrow: $(".testimonial-next-arrow-h2"), 
}); 

// SLIDER //
$(".testimonial7-main-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 2,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".project7-main-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 2,
  dots: false,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// testimonial//
 $('.slider-galeria').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true,
  infinite: false,
  infinite:true,
  asNavFor: '.slider-galeria-thumbs',
  prevArrow: $('.testimonial-next-arrow'),
  nextArrow: $('.testimonial-prev-arrow'),
});
$('.slider-galeria-thumbs').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  items:15,
  arrows: true,
  asNavFor: '.slider-galeria',
  vertical: true,
  infinite:true,
  verticalSwiping: true,
  focusOnSelect: true,
  infinite: false,
  prevArrow: $('.testimonial-next-arrow'),
  nextArrow: $('.testimonial-prev-arrow'),
});

// SLIDER //
$(".testimonial9-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  prevArrow: $(".testimonial9-prev-arrow"),
  nextArrow: $(".testimonial9-next-arrow"), 
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".service9-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".testimonial10-slider").slick({
  slidesToShow: 2,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".project10-main-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 2,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

// SLIDER //
$(".service10-slider-area").slick({
  slidesToShow: 3,
  slidesToScroll: 2,
  dots: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  loop: true,
  autoplay:true,
  autoplaySpeed:2000,
  infinite: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});
//========== PRELOADER ============= //
$(window).on('load', function(event) {
  setTimeout(function() {
    $(".preloader").fadeToggle();
  }, 200);
});

})(jQuery);


