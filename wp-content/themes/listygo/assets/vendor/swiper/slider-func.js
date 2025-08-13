(function ($) {
    "use strict";

    //function Load
    function listygo_slider_scripts_load() {
        var $ = jQuery;

        $(".swiper-container1").each(function () {
            let __swiper = $(this)[0];
            var options = $(this).find('.swiper-wrapper').data('carousel-options');
            var swiper = new Swiper(__swiper, {
                slidesPerView: options['col_lg'],
                spaceBetween: 30,
                speed: 1000,
                // loop: true,
                autoplay: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    1400: {
                        slidesPerView: options['col_lg'],
                    },
                    992: {
                        slidesPerView: options['col_md'],
                    },
                    576: {
                        slidesPerView: options['col_sm'],
                    },
                    300: {
                        slidesPerView: options['col_xs'],
                    },
                },
            });

            if (options['autoplay']) {
                $(this).each(function() {
                    swiper.autoplay.start();
                });
                swiper.params.autoplay.delay = options['speed'];
            }

            swiper.init();
        });

        
        $(".swiper-container2").each(function () {
            var options2 = $(this).find('.swiper-wrapper').data('carousel-options');
            var autoplayconditon = options2['autoplay'];
            var slider = new Swiper ('.slider-content', {
                slidesPerView: 1,
                centeredSlides: true,
                loopedSlides: 5,
                parallax: true,
                effect: 'slide',
                roundLengths: false,
                speed: 1000,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: autoplayconditon ? { delay: options2['speed'] } : false,
            });
            slider.init();
        });

        $(".swiper-container3").each(function () {
            let __swiper = $(this)[0];
            var options = $(this).find('.swiper-wrapper').data('carousel-options');
            var swiper = new Swiper(__swiper, {
                slidesPerView: options['col_lg'],
                speed: 1000,
                autoplay: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    1400: {
                        slidesPerView: options['col_lg'],
                    },
                    992: {
                        slidesPerView: options['col_md'],
                    },
                    576: {
                        slidesPerView: options['col_sm'],
                    },
                    300: {
                        slidesPerView: options['col_xs'],
                    },
                },
            });

            if (options['autoplay']) {
                $(this).each(function() {
                    swiper.autoplay.start();
                });
                swiper.params.autoplay.delay = options['speed'];
            }
            swiper.init();
        });

        $(".swiper-container4").each(function () {
            let __swiper = $(this)[0];

            var options = $(this).find('.swiper-wrapper').data('carousel-options');

            var swiper = new Swiper(__swiper, {
                slidesPerView: options['col_lg'],
                speed: 1000,
                autoplay: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    1400: {
                        slidesPerView: options['col_lg'],
                    },
                    992: {
                        slidesPerView: options['col_md'],
                    },
                    576: {
                        slidesPerView: options['col_sm'],
                    },
                    300: {
                        slidesPerView: options['col_xs'],
                    },
                },
            });

            if (options['autoplay']) {
                $(this).each(function() {
                    swiper.autoplay.start();
                });
                swiper.params.autoplay.delay = options['speed'];
            }
            swiper.init();
        });

    }


    // Window Load+Resize
    $(window).on('load resize', function (){
        // Elementor Frontend Load
        $(window).on('elementor/frontend/init', function () {
            if (elementorFrontend.isEditMode()) {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                    listygo_slider_scripts_load();
                });
            }
        });
    });

    $( document ).ready( function () {
        listygo_slider_scripts_load();
    });

})(jQuery);