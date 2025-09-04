/**
 * Created by RadiusTheme on 23/08/2022.
 */
(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {
        function loadCountry(country) {
            $('#city-state-list').html('<li class="col text-center">Loading...</li>');

            $.ajax({
                url: customjs.ajax_url,
                type: 'POST',
                data: {
                    action: 'my_country_state_list_ajax',
                    country: country
                },
                success: function (response) {
                    $('#city-state-list').html(response);
                },
                error: function () {
                    $('#city-state-list').html('<li class="col text-center">Error loading data.</li>');
                }
            });
        }

        $('.country-tabs li').on('click', function () {
            var country = $(this).data('country');

            $('.country-tabs li').removeClass('active');
            $(this).addClass('active');

            loadCountry(country);
        });

        loadCountry('USA');
    });


})(jQuery);