/**
 * Created by RadiusTheme on 23/08/2022.
 */
(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {
        function loadCountry(country) {
            $('#country-state-list').html('<li class="col text-center">Loading...</li>');

            $.ajax({
                url: customjs.ajax_url,
                type: 'POST',
                data: {
                    action: 'my_country_state_list_ajax',
                    country: country
                },
                success: function (response) {
                    $('#country-state-list').html(response);
                },
                error: function () {
                    $('#country-state-list').html('<li class="col text-center">Error loading data.</li>');
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

        // city-listings page
        $(document).on('click', '.country-filter .btn', function() {
            var country = $(this).data('country');
            var container = $('#state-city-results');

            // Update active button
            $(this).addClass('active').siblings().removeClass('active');

            // Load listings via AJAX
            $.post(customjs.ajax_url, {
                action: 'load_state_city_grid',
                country: country,
                categories: container.data('categories'),
                per_page: container.data('per-page'),
                paged: 1
            }, function(response){
                container.html(response);
            });
        });

        // AJAX pagination click
        $(document).on('click', '.scg-ajax-page', function(e){
            e.preventDefault();
            var page = $(this).data('page');
            var container = $('#state-city-results');

            var activeCountry = $('.btn-country.active').data('country') || 'usa';

            var data = {
                action: 'load_state_city_grid',
                categories: container.data('categories'),
                per_page: container.data('per-page'),
                paged: page
            };

            // Detect if state or country
            if (container.data('state')) {
                data.state = container.data('state');
            } else {
                data.country = activeCountry;
            }

            $.post(customjs.ajax_url, data, function(response){
                container.html(response);
            });
        });
    });


})(jQuery);