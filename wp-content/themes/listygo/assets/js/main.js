(function($) {
    "use strict";

    function listygo_ajax_search() {

        $(".location-category-item").on("change", "select", function (e) {
          e.preventDefault();
          var $this = $(this),
                taxonomy = $this.data("taxonomy"),
                parent = $this.data("parent"),
                value = $this.val(),
                slug = $this.find(":selected").attr("data-slug") || "",
                classes = $this.attr("class"),

                termHolder = $this.closest(".brand-child-cats").find("input.rtcl-term-hidden"),
                termValueHolder = $this.closest(".brand-child-cats").find("input.rtcl-term-hidden-value");

                termHolder.val(value).attr("data-slug", slug);
                termValueHolder.val(slug);
                $this.parent().find("div:first").remove();

            if (parent !== value) {
                $this.parent().append('<div class="rtcl-spinner"><span class="rtcl-icon-spinner animate-spin"></span></div>');
                var data = {
                  action: "rtcl_child_dropdown_terms",
                  taxonomy: taxonomy,
                  parent: value,
                  "class": classes
                };
                $.post(ListygoObj.ajaxurl, data, function (response) {
                  $this.parent().find("div:first").remove();
                  $this.parent().append(response);
                });
            }
        });
    }

    function listygo_scroll_effect(){

        // Detect request animation frame
        var scroll = window.requestAnimationFrame ||
        // IE Fallback
        function(callback){ window.setTimeout(callback, 1000/60)};
        var elementsToShow = document.querySelectorAll('.show-on-scroll'); 

        function loop() {
            Array.prototype.forEach.call(elementsToShow, function(element){
                if (isElementInViewport(element)) {
                    element.classList.add('is-visible');
                } else {
                    element.classList.remove('is-visible');
                }
            });
            scroll(loop);
        }

        // Call the loop for the first time
        loop();

        // Helper function from: http://stackoverflow.com/a/7557433/274826
        function isElementInViewport(el) {
            // special bonus for those using jQuery
            if (typeof jQuery === "function" && el instanceof jQuery) {
                el = el[0];
            }
            var rect = el.getBoundingClientRect();
            return (
                (rect.top <= 0
                    && rect.bottom >= 0)
                ||
                (rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight))
                ||
                (rect.top >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
            );
        }

        // // Create the observer, same as before:
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    return;
                }
                entry.target.classList.remove("visible");
            });
        });
        let sectionR = document.querySelectorAll(".workflow-progress.animated");
        sectionR.forEach((ele) => observer.observe(ele));

        // Animation - appear 
        $('.animated').appear(function() {
            $(this).each(function(){ 
                    var $target =  $(this);
                    var delay = $(this).data('animation-delay');
                    setTimeout(function() {
                        $target.addClass($target.data('animation')).addClass('visible')
                    }, delay);
            });
        });

        /*---------------------------------------
          Background Parallax
          --------------------------------------- */
        if ($(".rt-parallax-bg-yes").length) {
            $(".rt-parallax-bg-yes").each(function () {
                var speed = $(this).data('speed');
                $(this).parallaxie({
                    speed: speed ? speed : 0.5,
                    offset: 0,
                });
            });
        }

        new WOW().init();
    }

    function listyo_isotope_filtering(){
        // Isotope
        if ( typeof $.fn.isotope == 'function' && typeof $.fn.imagesLoaded == 'function') {

            // Run 1st time
            var $isotopeContainer = $('.rt-el-isotope-container');
            
            $isotopeContainer.imagesLoaded( function() {
                $isotopeContainer.each(function() {
                    var $container = $(this).find('.rt-el-isotope-wrapper'),
                    filter = $(this).find('.rt-el-isotope-tab a.current').data('filter');
                    runIsotope($container,filter);
                });
            });

            // Run on click even
            $('.rt-el-isotope-tab a').on('click',function(){
                $(this).closest('.rt-el-isotope-tab').find('.current').removeClass('current');
                $(this).addClass('current');
                var $container = $(this).closest('.rt-el-isotope-container').find('.rt-el-isotope-wrapper'),
                filter = $(this).attr('data-filter');
                runIsotope($container,filter);
                return false;
            });
        }
    }

    function runIsotope($container,filter){
        $container.isotope({
            filter: filter,
            animationOptions: {
                duration: 750,
                // easing: 'linear',
                queue: false
            }
        });
    }


    /*-------------------------------------
    Active Menu
    -------------------------------------*/
    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= $("body").offset().top + 50) {
            $("body").addClass("mn-top");
        } else {
            $("body").removeClass("mn-top");
        }

        // Back Top Button
        if ($(window).scrollTop() > 500) {
            $(".scrollup").addClass("back-top");
        } else {
            $(".scrollup").removeClass("back-top");
        }
    });

    function mobile_nav_class() {
        var a = $( '.offscreen-navigation .main-menu' );
        if (a.length) {
            $(".menu-item-has-children").append("<span></span>");
            $(".page_item_has_children").append("<span></span>");

            a.children("li").addClass("menu-item-parent");

            $('.menu-item-has-children > span').on('click', function () {
                var _self = $(this),
                    sub_menu = _self.parent().find('>.sub-menu');
                if (_self.hasClass('open')) {
                    sub_menu.slideUp();
                    _self.removeClass('open');
                } else {
                    sub_menu.slideDown();
                    _self.addClass('open');
                }
            });
            $('.page_item_has_children > span').on('click', function () {
                var _self = $(this),
                    sub_menu = _self.parent().find('>.children');
                if (_self.hasClass('open')) {
                    sub_menu.slideUp();
                    _self.removeClass('open');
                } else {
                    sub_menu.slideDown();
                    _self.addClass('open');
                }
            });
        }
        $('.mean-bar .sidebarBtn').on('click', function (e) {
            e.preventDefault();
            if ($('.rt-slide-nav').is(":visible")) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            } else {
                $('.rt-slide-nav').slideDown();
                $('body').addClass('slidemenuon');
            }
        });
    }

    mobile_nav_class();

    // $("#review-form #submit").prop("value", "Submit Your Review");

    /*-------------------------------------
    Menu fixded
    -------------------------------------*/
    if ($('header .header-main').length && $('header .header-main').hasClass('header-sticky')) {
        var stickyPlaceHolder = $("#sticky-placeholder"),
            menu = $(".header-sticky"),
            menuH = menu.outerHeight();
        var header_position = $('header .header-main').offset(),
            lastScroll = 100;
        $(window).on('scroll load', function (event) {
            var st = $(this).scrollTop();
            if (st > header_position.top) {
                ($(".header-table").length) ? $('header .header-table').addClass("header-fixed"): $('header .header-main').addClass("header-fixed");
                stickyPlaceHolder.height(menuH);
            } else {
                ($(".header-table").length) ? $('header .header-table').removeClass("header-fixed"): $('header .header-main').removeClass("header-fixed");
                stickyPlaceHolder.height(0);
            }

            if (st > lastScroll && st > header_position.top + 130) {
                ($(".header-table").length) ? $('header .header-table').addClass("hidden-menu"): $('header .header-main').addClass("hidden-menu");
            } else if (st <= lastScroll) {
                ($(".header-table").length) ? $('header .header-table').removeClass("hidden-menu"): $('header .header-main').removeClass("hidden-menu");
            }

            lastScroll = st;

            if (st === 0) {
                ($(".header-table").length) ? $('header .header-table').removeClass("header-fixed"): $('header .header-main').removeClass("header-fixed");
            }
        });
    } 

    /*-------------------------------------
    Page Preloader
    -------------------------------------*/
    if ($('#pageoverlay').length) {
        window.onload = function() {
            document.getElementById('pageoverlay').className = 'pageoverlay loaded';
            $('#pageoverlay').delay(800).fadeOut();
        }
    }

    /*-------------------------------------
    = Section background image 
    -------------------------------------*/
    function imageFunction() {
        $("[data-bg-image]").each(function() {
            let img = $(this).data("bg-image");
            $(this).css({
                backgroundImage: "url(" + img + ")",
            });
        });
    }

    $("#wrapper").on("click", ".offcanvas-menu-btn", function(e) {
        e.preventDefault();
        var $this = $(this),
            wrapper = $(this).parents("body").find(">#wrapper"),
            wrapMask = $("<div />").addClass("offcanvas-mask"),
            offCancas = $("#offcanvas-wrap"),
            position = offCancas.data("position") || "right";
    
        if ($this.hasClass("menu-status-open")) {
            wrapper.addClass("open").append(wrapMask);
            $this.removeClass("menu-status-open").addClass("menu-status-close");
            offCancas.css({
                transform: "translateX(0)",
            });
        } else {
            removeOffcanvas();
        }
    
        function removeOffcanvas() {
            wrapper.removeClass("open").find("> .offcanvas-mask").remove();
            $this.removeClass("menu-status-close").addClass("menu-status-open");
            if (position === "right") {
                offCancas.css({
                    transform: "translateX(105%)",
                });
            } else {
                offCancas.css({
                    transform: "translateX(105%)",
                });
            }
        }
        $(".offcanvas-mask, .offcanvas-close").on("click", function() {
            removeOffcanvas();
        });
    
        return false;
    });


    //function Load
    function listygo_main_scripts_load() {
        var $ = jQuery;

        // Price Filter range slider  
        if ($.fn.ionRangeSlider) {
            $(".ion-rangeslider").each(function () {
                var $this = $(this);
                var rangeType = $this.data('type');
                $this.ionRangeSlider({
                    type: rangeType || "double",
                    drag_interval: true,
                    min_interval: null,
                    max_interval: null,
                    onChange: function (data) {
                        var $inp = data.input;
                        $inp.parent().find('.min-volumn').val(data.from);
                        $inp.parent().find('.max-volumn').val(data.to);
                    },
                });
            });

            $(".advance-search-form .rtcl-range-slider-input").each(function () {
                var $this = $(this);
                var rangeType = $this.data('type');
                $this.ionRangeSlider({
                    drag_interval: true,
                    min_interval: null,
                    max_interval: null,
                    onChange: function (data) {
                        var $inp = data.input;
                        $inp.parent().find('.min-volumn').val(data.from);
                        $inp.parent().find('.max-volumn').val(data.to);
                    },
                });
            });
            $('.rtcl-ordering').each(function(){
                $('.orderby').select2({
                    allowClear: true
                });
            });
        }

        if ($('select.select2').length) {
            $('select.select2').select2({
                theme: 'classic',
                dropdownAutoWidth: true,
                width: '100%',
            });
        }

        if ( typeof $.fn.countdown == 'function') {
            $('.event-countdown')
            .each(function () {
                var $_counter = $(this);
                var eventCountdownTime = $_counter.data('countdown'),
                day    = (ListygoObj.day == 'Days') ? 'Day%!D' : ListygoObj.day,
                hour   = (ListygoObj.hour == 'Hrs') ? 'Hr%!D' : ListygoObj.hour,
                minute = (ListygoObj.minute == 'Mins') ? 'Min%!D' : ListygoObj.minute,
                second = (ListygoObj.second == 'Secs') ? 'Sec%!D' : ListygoObj.second;
                $_counter
                .countdown(eventCountdownTime)
                .on('update.countdown', function(event) {
                    $(this).html(event.strftime(''
                        + '<div class="countdown-section"><span class="c-unit">%D</span><span class="c-title">'+day+'</span></div>'
                        + '<div class="countdown-section"><span class="c-unit">%H</span><span class="c-title">'+hour+'</span></div>'
                        + '<div class="countdown-section"><span class="c-unit">%M</span><span class="c-title">'+minute+'</span></div>'
                        + '<div class="countdown-section"><span class="c-unit">%S</span><span class="c-title">'+second+'</span></div>'));
                })      
                .on('finish.countdown', function(event) {
                    $(this).html(event.strftime(''));
                });
            });     
        }

        /*-------------------------------------
        Tooltip
        -------------------------------------*/
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)) 
    }

    function listygo_lightbox1() {
        /*-------------------------------------
            PhotoSwipe Lightbox
        -------------------------------------*/
        // Define click event on gallery item
        $('.rtcl-archive-gallery-trigger').click(function(event) {
            // Prevent location change
            event.preventDefault();
            // Loop over gallery items and push it to the array
            let container = [];
            $(this).parent().find('.rtcl-pswp-item').each(function() {
                let $link = $(this).find('img'),
                item = {
                    src: $link.attr('src'),
                    w: $link.attr('width'),
                    h: $link.attr('height')
                };
                container.push(item);
            });
            // Define object and gallery options
            let $pswp = $('.pswp')[0],
            options = {
                index: $(this).parent('.rtcl-pswp-item').index(),
                bgOpacity: 0.8,
                zoomEl: true,
                tapToClose: true,
                fullscreenEl: true,
                preloaderEl: true,
                shareEl: true,
                captionEl: false,
            };
            // Initialize PhotoSwipe
            let gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }

    function listygo_lightbox2() {
        // Define click event on gallery item
        $('.listing-gallery-item .listing-popup-btn').click(function(event) {
            // Prevent location change
            event.preventDefault();
            // Init empty gallery array
            var container = [];
            var allPhotos = $(this).parents('.photo-swip-gallery-wrap').find('.listing-gallery-item');

            var currentClickedIndex = allPhotos.index($(this).parent());
            // Loop over gallery items and push it to the array
            allPhotos.each(function() {
                var $link = $(this).find('.listing-popup-btn'),
                    item = {
                        src: $link.attr('href'),
                        w: $link.data('width'),
                        h: $link.data('height'),
                    };
                container.push(item);
            });

            // Define object and gallery options
            var $pswp = $('.pswp')[0],
            options = {
                index: currentClickedIndex,
                bgOpacity: 0.85,
                showHideOpacity: true,
                zoomEl: true,
                tapToClose: true,
                fullscreenEl: true,
                preloaderEl: true,
                shareEl: true,
                captionEl: false,
            };

            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }

    function listygo_lightbox3() {
        /*-------------------------------------
            PhotoSwipe Lightbox
        -------------------------------------*/
        // Init empty gallery array
        var container = [];
        $(".viewer-enabler a").attr( 
            {width: "860",  height:"460"}
        );
        // Loop over gallery items and push it to the array
        $('.viewer-enabler').find('figure').each(function() {
            var $link = $(this).find('a'),
            item = {
                src: $link.attr('href'),
                w: $link.attr('width'),
                h: $link.attr('height')
            };
            container.push( item );
        });

        // Define click event on gallery item
        $('.viewer-enabler a').click(function(event) {

            // Prevent location change
            event.preventDefault();

            // Define object and gallery options
            var $pswp = $('.pswp')[0],
            options = {
                index: $(this).parent('figure').index(),
                bgOpacity: 0.85,
                showHideOpacity: true
            };

            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }

    function listygo_lightbox4() {
        /*-------------------------------------
            PhotoSwipe Lightbox
        -------------------------------------*/
        // Init empty gallery array
        var container = [];
        // Loop over gallery items and push it to the array
        $('.single-listing-carousel-wrap').find('.photoswip-item').each(function() {
            var $link = $(this).find('a'),
            item = {
                src: $link.attr('href'),
                w: $link.attr('width'),
                h: $link.attr('height')
            };
            container.push(item);
        });

        // Define click event on gallery item
        $('.single-listing-carousel-wrap .photoswip-item a').click(function(event) {

            // Prevent location change
            event.preventDefault();

            // Define object and gallery options
            var $pswp = $('.pswp')[0],
            options = {
                index: $(this).parent('.photoswip-item').index(),
                bgOpacity: 0.85,
                showHideOpacity: true
            };

            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }

    function listygo_lightbox5(){

        var initPhotoSwipeFromDOM = function(gallerySelector) {

            // parse slide data (url, title, size ...) from DOM elements 
            // (children of gallerySelector)
            var parseThumbnailElements = function(el) {
                var thumbElements = el.childNodes,
                    numNodes = thumbElements.length,
                    items = [],
                    figureEl,
                    linkEl,
                    size,
                    item;
                for(var i = 0; i < numNodes; i++) {
                    
                    figureEl = thumbElements[i]; // <figure> element

                    // include only element nodes 
                    if(figureEl.nodeType !== 1) {
                        continue;
                    }

                    linkEl = figureEl.children[0]; // <a> element
                    size = linkEl.getAttribute('data-size').split('x');

                    // create slide object
                    item = {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10)
                    };

                    if(figureEl.children.length > 1) {
                        // <figcaption> content
                        item.title = figureEl.children[1].innerHTML; 
                    }
                    if(linkEl.children.length > 0) {
                        // <img> thumbnail element, retrieving thumbnail url
                        item.msrc = linkEl.children[0].getAttribute('src');
                    } 
                    item.el = figureEl; // save link to element for getThumbBoundsFn
                    items.push(item);
                }
                return items;
            };

            // find nearest parent element
            var closest = function closest(el, fn) {
                return el && ( fn(el) ? el : closest(el.parentNode, fn) );
            };

            // triggers when user clicks on thumbnail
            var onThumbnailsClick = function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                var eTarget = e.target || e.srcElement;
                // find root element of slide
                var clickedListItem = closest(eTarget, function(el) {
                    return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                });
                if(!clickedListItem) {
                    return;
                }
                // find index of clicked item by looping through all child nodes
                // alternatively, you may define index via data- attribute
                var clickedGallery = clickedListItem.parentNode,
                    childNodes = clickedListItem.parentNode.childNodes,
                    numChildNodes = childNodes.length,
                    nodeIndex = 0,
                    index;
                for (var i = 0; i < numChildNodes; i++) {
                    if(childNodes[i].nodeType !== 1) { 
                        continue; 
                    }

                    if(childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }
                if(index >= 0) {
                    // open PhotoSwipe if valid index found
                    openPhotoSwipe( index, clickedGallery );
                }
                return false;
            };

            // parse picture index and gallery index from URL (#&pid=1&gid=2)
            var photoswipeParseHash = function() {
                var hash = window.location.hash.substring(1),
                params = {};

                if(hash.length < 5) {
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if(!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');  
                    if(pair.length < 2) {
                        continue;
                    }           
                    params[pair[0]] = pair[1];
                }

                if(params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                    gallery,
                    options,
                    items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {
                    // define gallery index (for URL)
                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function(index) {
                        // See Options -> getThumbBoundsFn section of documentation for more info
                        var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                            rect = thumbnail.getBoundingClientRect(); 
                        return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                    }

                };

                // PhotoSwipe opened from URL
                if(fromURL) {
                    if(options.galleryPIDs) {
                        // parse real index when custom PIDs are used 
                        for(var j = 0; j < items.length; j++) {
                            if(items[j].pid == index) {
                                options.index = j;
                                break;
                            }
                        }
                    } else {
                        // in URL indexes start from 1
                        options.index = parseInt(index, 10) - 1;
                    }
                } else {
                    options.index = parseInt(index, 10);
                }

                // exit if index not found
                if( isNaN(options.index) ) {
                    return;
                }

                if(disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            };

            // loop through all gallery elements and bind events
            var galleryElements = document.querySelectorAll( gallerySelector );

            for(var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i+1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if(hashData.pid && hashData.gid) {
                openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
            }
        };

        // execute above function
        initPhotoSwipeFromDOM('.single-listing-food-menu');
    }

    listygo_lightbox1();
    listygo_lightbox2();
    listygo_lightbox3();
    listygo_lightbox4();
    listygo_lightbox5();

   /*-------------------------------------
   Scroll to Bottom
   -------------------------------------*/
   $(".listing-details .listing-actions").on('click', '.favourite-btn', function (e) {
      e.preventDefault();
      $('html, body').animate({
         scrollTop: $("#respond").offset().top
      }, 1000);
   });

   $(document).ready(function(){
        /* Get iframe src attribute value i.e. YouTube video url
        and store it in a variable */
        var url = $("#modal-autoplay").attr('src');
        
        /* Remove iframe src attribute on page load to
        prevent autoplay in background */
        $("#modal-autoplay").attr('src', '');
        
        /* Assign the initially stored url back to the iframe src
        attribute when modal is displayed */
        $("#modal-popup").on('shown.bs.modal', function(){
            $("#modal-autoplay").attr('src', url);
        });
        
        /* Assign empty url value to the iframe src attribute when
        modal hide, which stop the video playing */
        $("#modal-popup").on('hide.bs.modal', function(){
            $("#modal-autoplay").attr('src', '');
        });

        // Delete Logo Image
        $(".remove-logo-image a").on("click", function (e) {
            e.preventDefault();
            let attachmentID = $(this).data('attachment_id');
            let postID = $(this).data('post_id');
            let container = $(this).parents('.logo-image');
            let inputWrapper = $('.logo-input-wrapper');

            let r = confirm('Are you want to delete this attachment?');

            if (r) {
                $.ajax({
                    type: "post",
                    url: rtcl.ajaxurl,
                    data: {
                        action: "delete_listing_logo_attachment",
                        attachment_id: attachmentID,
                        post_id: postID,
                    },
                    success: function (response) {
                        if (response === 'success') {
                            container.fadeOut(function () {
                                container.remove();
                                inputWrapper.toggleClass('d-none');
                            });
                        }
                    }
                })
            }
        });

    });

    /*-------------------------------------
    = Accordion
    -------------------------------------*/
    $('.faq-box .panel-group')
        .on('show.bs.collapse', function(e) {
            $(e.target).parent('.panel-default').addClass('active');
        })
        .on('hide.bs.collapse', function(e) {
            $(e.target).parent('.panel-default').removeClass('active');
        });
      
    
    // Window Load+Resize 
    $(window).on('load resize', function (){
        // Elementor Frontend Load
        $(window).on('elementor/frontend/init', function () {
            if (elementorFrontend.isEditMode()) {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                listygo_main_scripts_load();
                listygo_scroll_effect();
                listyo_isotope_filtering();
                imageFunction();
                });
            }
        });

        var wow = new WOW({
            boxClass:     'wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset:       0,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
            callback:     function(box) {
           // the callback is fired every time an animation is started
           // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null,    // optional scroll container selector, otherwise use window,
            resetAnimation: true,     // reset animation on end (default is true)
        });
        wow.init();
    });


    function rtcl_sidebar_categories_dropdown() {
        $( ".rtcl-widget-categories >.rtcl-category-list >li ul" ).parent().append('<span class="dropdown"></span>');
        $('.rtcl-widget-categories >.rtcl-category-list >li > span').on('click', function () {
            var _self = $(this),
                sub_cats = _self.parent().find('>.rtcl-category-list');
            if (_self.hasClass('open')) {
                sub_cats.slideUp();
                _self.removeClass('open');
            } else {
                sub_cats.slideDown();
                _self.addClass('open');
            }
        });
    }
    rtcl_sidebar_categories_dropdown();

    function rtcl_cf_by_category(catId, targetEl) {

        var parentElementor = targetEl.parents('.advance-search-form');
        var container = parentElementor.find('.rtcl_cf_by_category_html');

        var parentEl = targetEl.closest(".rtcl-category-ajax");
        var cfId = parentEl.data('fb-id') ?? ''

        $.ajax({
            type: "post",
            url: ListygoObj.ajaxurl,
            data: {
                action: "rtcl_cf_by_category",
                cat_id: catId > 0 ? parseInt(catId) : 'all',
                fb_id: cfId > 0 ? parseInt(cfId) : null
            },
            beforeSend: function () {
                container.addClass('is-loading')
            },
            success: function (data) {
                container.html(data);
                container.removeClass('is-loading')
            }
        })
    }


    //Advanced custom field rendering by category filter
    function rtclCategoryAjax() {

        var searchForm = $('.advance-search-form');

        if (searchForm.length < 1) {
            return
        }

        document.querySelector(".rtcl-category-search").addEventListener("change", function() {
            let selectedValue = this.value; // Selected option er value dorbo
            let targetEl = $(this); // jQuery object banalam

            if (selectedValue) {
                rtcl_cf_by_category(selectedValue, targetEl); // Function ke call korchi
            }
        });


        //Default category on load
        var defaultTargetEl = $('.advance-search-form option');
        var defaultCategory = $('.advance-search-form option:first-child').data('category');
        rtcl_cf_by_category(defaultCategory, defaultTargetEl)


        //Category select2 on change
        $('.rtcl-category-search-ajax').each(function () {
            var currentElement = $(this);
            currentElement.on('select2:select', function (e) {
                var categoryId = $(e.params.data.element).data('term_id');
                rtcl_cf_by_category(categoryId, currentElement)
            });
        });

    }

    $( document ).ready( function () {
        listygo_main_scripts_load();
        listygo_scroll_effect();
        listyo_isotope_filtering();
        imageFunction();
        rtclCategoryAjax();
        /* Theia Side Bar */
        if (typeof ($.fn.theiaStickySidebar) !== "undefined") {
            $('.custom-row .custom-column--one').theiaStickySidebar({'additionalMarginTop': 60});
            $('.blog-posts-layout .container .row .col-lg-4').theiaStickySidebar({'additionalMarginTop': 0});
            $('.listygo-listing-map-wrapper .map-right-side-content').theiaStickySidebar({'additionalMarginTop': 60});
            $('.listing-archvie-page .has-map .map-right-side-content').theiaStickySidebar({'additionalMarginTop': 60});
            $('.page-template-listing-map .listygo-listing-map-wrapper .rtcl-search-map').theiaStickySidebar({'additionalMarginTop': 60});
        }
    });

})(jQuery);