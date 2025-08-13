//function Load
function listygo_core_scripts_load() {
    var $ = jQuery;
}

(function ($) {
    "use strict";

    // Window Load+Resize
    $(window).on('load resize', function () {
        // Elementor Frontend Load
        $(window).on('elementor/frontend/init', function () {
            if (elementorFrontend.isEditMode()) {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                    listygo_core_scripts_load();
                });
            }
        });
    });

    // Window Load
    $(window).on('load', function () {
        listygo_core_scripts_load();
    });

})(jQuery);