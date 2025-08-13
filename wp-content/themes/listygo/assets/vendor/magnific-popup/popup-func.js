(function ($) {
    "use strict";

    //function Load
    function listygo_popup_scripts_load() {
        var $ = jQuery;

        /*-------------------------------------
        Video Popup
        -------------------------------------*/
        let videoPopUp = $(".play-btn");
        if (videoPopUp.length) {
            videoPopUp.magnificPopup({
                type: "iframe",
                iframe: {
                    markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                        "</div>",
                    patterns: {
                        youtube: {
                            index: "youtube.com/",
                            id: "v=",
                            src: "https://www.youtube.com/embed/%id%?autoplay=1",
                        },
                        vimeo: {
                            index: "vimeo.com/",
                            id: "/",
                            src: "//player.vimeo.com/video/%id%?autoplay=1",
                        },
                        gmaps: {
                            index: "//maps.google.",
                            src: "%id%&output=embed",
                        },
                    },
                    srcAction: "iframe_src",
                },
            });
        }

    }

    // Window Load+Resize
    $(window).on('load resize', function (){
        // Elementor Frontend Load
        $(window).on('elementor/frontend/init', function () {
            if (elementorFrontend.isEditMode()) {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                    listygo_popup_scripts_load();
                });
            }
        });
    });

    $( document ).ready( function () {
        listygo_popup_scripts_load();
    });

})(jQuery);
