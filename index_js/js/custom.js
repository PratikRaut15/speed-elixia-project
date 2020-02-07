/*
  @Author : Pixelmotive studio
  @Project : Thini-Elixia
  @Dev : Dev Team
  @Date : 30-03-2018;
*/


/* Document Ready */
var winH = $(window).height();
var winW = $(window).width();

var bannerSlider;
var testiswiper;
var productSwiper;
var clientSwiper;
var videoSwiper;
var productSetting = {
    speed: 1500,
    slidesPerView: 3,
    spaceBetween: 15,
    loop: true,
    autoplay: 2500,
    nextButton: '.swiper-button-next.productSlider1',
    prevButton: '.swiper-button-prev.productSlider1',
    autoplayDisableOnInteraction: false,
    breakpoints: {
        768: {
            slidesPerView: 1,
            spaceBetween: 10
        }
    }
}
var dynamic = $(".bs-sec.typ-no-pad .sec-head.cm-not-in-page").html();
var menuId = [];

function appendDynamic() {
    $(".bs-tile .tile-list").prepend('<li class="item col-md-4"><div class="tile typ-title"><div class="tile-info">' + dynamic + '</div></div></li>');
}

function imgToBg(obj, objItem, objSrc) {
    $(obj).find(objItem).each(function() {
        var imgSrc = $(this).find(objSrc).attr('src');
        $(this).css("background-image", "url(" + imgSrc + ")");
    });
}

function bannerheight() {
    var smH = winH - 100;
    $('.bs-banner .img-wrap').height(winH);
    $('.bs-banner.typ-sm .img-wrap').height(smH);
}

function mobMenu() {
    $('.mobile .bs-header .js-mob').on('click', function(e) {
        if ($('body.aboutus').length != 0) {
            e.preventDefault();
            var menuHeight = $(this).parent().find('.mod-sub-menu').height() + 20;
            $('.mobile .bs-header .mod-sub-menu').removeClass('active');
            $('.mobile .bs-header .nav-list > .item').css('margin-top', '0px');
            $(this).parent().next('.item').css('margin-top', menuHeight + 'px');
            setTimeout(function() {
                $(this).parent().find('.mod-sub-menu').addClass('active');
            }, 600);
        } else {
            var menuHeight = $(this).parent().find('.mod-sub-menu').height() + 20;
            $('.mobile .bs-header .mod-sub-menu').removeClass('active');
            $('.mobile .bs-header .nav-list > .item').css('margin-top', '0px');
            $(this).parent().next('.item').css('margin-top', menuHeight + 'px');
            setTimeout(function() {
                $(this).parent().find('.mod-sub-menu').addClass('active');
            }, 600);
        }
    })
}

function form() {
    $('.bs-banner .btn-flyout').on('click', function() {
        $(this).addClass('active');
        $('.bs-banner .lyt-login').addClass("active");
    });
    $('.lyt-login .icon-back').on('click', function(e) {
        $('.lyt-login').removeClass('active');
        $('.bs-banner .btn-flyout').removeClass('active');
    });
}

function benefitsScroll() {
    var listWidth = 0;
    var temp = 0;
    $('.bs-benefit .btn-list li').each(function() {
        temp = $(this).width().toFixed(2)
        listWidth = listWidth + Number(temp);
    });
    $('.bs-benefit .btn-list ul').width(listWidth + 0.5);
}

function bannerSlider() {
    bannerSlider = new Swiper('#swiperBanner', {
        speed: 1500,
        slidesPerView: 1,
        autoplay: 2500,
        autoplayDisableOnInteraction: false,
        effect: 'fade',

    });
    $('.bs-banner.typ-slider .swiper-slide').width(winW);
}

function videoSlider() {
    videoSwiper = new Swiper('#videoslider', {
        speed: 1500,
        // loop: true,
        slidesPerView: 1,
        // autoplay: 2500,
        nextButton: '.swiper-button-next.video-btn',
        prevButton: '.swiper-button-prev.video-btn',
        onSlideChangeEnd: function() {
            var currentPlayerId1 = $('.bs-video.active').find('.video-wrap video').attr('id');
            var player = new MediaElementPlayer(document.getElementById(currentPlayerId1));
            player.pause();
            player.remove();
            $('.bs-video.active').removeClass('active');
        }
    })
}

function showVideo() {
    $('.js-trigger-video').on('click', function() {
        var currentPlayerId = $(this).parents('.bs-video').find('.video-wrap video').attr('id');
        $('.bs-video').removeClass('active');
        $(this).closest('.bs-video').addClass('active');
        var player = new MediaElementPlayer(document.getElementById(currentPlayerId), {
            shimScriptAccess: 'always',
            autoRewind: true,
            features: [],
            currentMessage: 'Now playing:',
            alwaysShowControls: true,
            useDefaultControls: true
        })
    });
}

function hamburgerMenu() {
    $('.hamburger').on('click', function() {
        $(this).toggleClass('active');
        $('.menu').toggleClass('active');
        $('.mobile .bs-header .nav-list > .item').css('margin-top', '0px');
    });
}

function testislider() {

    testiswiper = new Swiper('#testimonialSlider .swiper-container', {
        speed: 1500,
        slidesPerView: 2,
        spaceBetween: 30,
        nextButton: '.swiper-button-next.testibutton',
        prevButton: '.swiper-button-prev.testibutton',
        autoplayDisableOnInteraction: false,
        breakpoints: {
            768: {
                slidesPerView: 1,
                spaceBetween: 10
            }
        }
    });
}

function stickyNav() {
    $(window).scroll(function(e) {
        var scrollPos = $(window).scrollTop();
        if (scrollPos > 100) {
            $(".bs-header").addClass('sticky');
        } else {
            $(".bs-header").removeClass('sticky');
        }
    });
}

function menuDashAnimation() {
    if ($('.desktop').length != 0) {
        var initialPos = $(".nav-list > .item.active").position().left;
        var initialWidth = $(".nav-list > .item.active").width();
        // console.log(initialPos);
        $(".bar-line").stop().animate({
            left: initialPos,
            width: initialWidth
        });
        $(".nav-list > .item").hover(function() {
            var ele = $(this);
            var eleWidth = $(this).width();
            var eleLeftPos = $(this).position().left;
            $(".bar-line").stop().animate({
                left: eleLeftPos,
                width: eleWidth
            });
        }, function() {
            $(".bar-line").stop().animate({
                left: initialPos,
                width: initialWidth
            });
        });
    }
}

function menuActive() {
    $('.bs-header .nav-list > .item').each(function(index) {
        menuId[index] = $(this).find('>.nav-link').text();
        if ($('.lyt-content').attr('id') == menuId[index]) {
            $(this).addClass("active");
            menuDashAnimation();
        }
    });
}

function clientSlider() {
    clientSwiper = new Swiper('#clientslider', {
        speed: 1200,
        slidesPerView: 2,
        slidesPerColumn: 2,
        spaceBetween: 10,
        autoplay: 2000
    });
}

function productSlider() {

    productSwiper = new Swiper('#productSlider', {
        speed: 1500,
        slidesPerView: 3,
        spaceBetween: 15,
        autoplayDisableOnInteraction: false,
        breakpoints: {
            768: {
                slidesPerView: 1,
                spaceBetween: 10,
                nextButton: '.swiper-button-next.productSlider',
                prevButton: '.swiper-button-prev.productSlider',
            }
        }
    });
    productSwiper1 = new Swiper('#productSlider1', {
        speed: 1500,
        slidesPerView: 3,
        spaceBetween: 15,
        nextButton: '.swiper-button-next.productSlider1',
        prevButton: '.swiper-button-prev.productSlider1',
        loop: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false,
        breakpoints: {
            768: {
                slidesPerView: 1,
                spaceBetween: 10
            }
        }
    });
}

$("#scrollup").click(function() {
  $("html, body").animate({ scrollTop: 0 }, 3000);
});


$(function() {
    $("a").each(function() {
        if ($(this).attr('href') == '#' || $(this).attr('href') == ' ') {
            $(this).attr('href', 'javascript:void(0)');
        }
    });
    new WOW().init();

    for(i=0;i<$('.bs-benefit .btn-list .item').length;i++) {
        setTimeout(function() {
            $('.bs-benefit .btn-list .item label').removeClass('active');
            $('.bs-benefit .btn-list .item').eq(i).find('label').addClass('active');
        },2000);
    }
    var i = 0;
    var benefitsLoop = setInterval(function() {
        if(i<$('.bs-benefit .btn-list .item').length) {
            $('.bs-benefit .btn-list .item label').removeClass('active');
            $('.bs-benefit .btn-list .item').eq(i).find('label').addClass('active');
            $('.bs-benefit .rel-info .item').removeClass('active');
            $($('.bs-benefit .btn-list .item').eq(i).find('label.active').data("target")).addClass('active');
            i+=1;
        }else {
            i=0;
        }
    },4000);

    if ($('.mobile .bs-benefit').length != 0) {
        benefitsScroll();
    }

    $('.winH').height(winH - 230);
    $('.winW').height(winW - 230);

    if ($('.js-bg-img').length != 0) {
        imgToBg('.js-bg-img', '.addto', '.img');
    }
    if ($('.bs-banner').length != 0) {
        bannerheight();
    }
    if ($('.bs-banner.typ-slider').length != 0) {
        bannerSlider();
    }
    if ($('.bs-video').length != 0) {
        videoSlider();
        showVideo();
    }

    if ($('.bs-sec.typ-testimonials').length != 0) {
        testislider();
    }
    if ($('.bs-sec.typ-no-pad').length != 0) {
        appendDynamic();
    }

    if ($('.bs-header').length != 0) {
        stickyNav();
        menuActive();
        menuDashAnimation();
        hamburgerMenu();
    }
    if ($('.lyt-products').length != 0) {
        productSlider();
    }
    $('.bs-benefit .btn-group .item label').hover(function(e) {
        // console.log('hovered');
        $('.bs-benefit .btn-list .item label').removeClass('active');
        $('.bs-benefit .rel-info .item').removeClass('active');
        $($(this).data("target")).addClass('active');
        clearInterval(benefitsLoop);
    });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        productSwiper1.destroy();
        productSwiper1 = new Swiper('#productSlider1', productSetting);
    });
    if ($('.lyt-login').length != 0) {
        form();
    }
    if ($('.mobile .bs-logo').length != 0) {
        clientSlider();
    }
    $('a.scrollbtn').on('click', function(e) {
        if ($(this).attr('href').indexOf('#') > -1) {
            var hashText = $(this).attr('href').split('#')[1];
            if ($('.desktop').length != 0) {
                $('html,body').animate({
                    scrollTop: $('#' + hashText).offset().top - 75
                }, 2000);
            } else {
                $(".bs-header .menu").removeClass("active");
                $(".bs-header .hamburger").removeClass("active");
                $('html,body').animate({
                    scrollTop: $('#' + hashText).offset().top - 60
                }, 2000);
            }
        }
    });
        $('a.scrollbtn').on('click', function(e) {
        if ($(this).attr('href').indexOf('#') > -1) {
            var hashText = $(this).attr('href').split('#')[1];
            if ($('.desktop').length != 0) {
                $('html,body').animate({
                    scrollTop: $('#' + hashText).offset().top - 75
                }, 2000);
            } else {
                $(".bs-header .menu").removeClass("active");
                $(".bs-header .hamburger").removeClass("active");
                $('html,body').animate({
                    scrollTop: $('#' + hashText).offset().top - 60
                }, 2000);
            }
        }
    });
    mobMenu();
});