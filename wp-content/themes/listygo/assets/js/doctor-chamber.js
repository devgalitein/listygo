(function ($) {
    "use strict";

    /*-------------------------------------
    Listing Floor Repeater
    -------------------------------------*/
    function updateClinicIndexing() {
        $('.dr-clinic-wrap').find('.dr-clinic-item').each(function (i, item) {
            $('input, textarea', item).each(function () {
                // Rename first array value from name to group index
                var _recipe_input = $(this);
                _recipe_input.attr('name', _recipe_input.attr('name').replace(/listygo_doctor_chamber\[[^\]]*\]/, 'listygo_doctor_chamber[' + i + ']'));
            });
            $('.listygo-chamber-image', item).each(function () {
                // Rename first array value from name to group index
                var _ingredient = $(this);
                _ingredient.attr('name', _ingredient.attr('name').replace(/listygo_chamber_img\[[^\]]*\]/, 'listygo_chamber_img[' + i + ']'));
            });
        });
    }

    function getClinictHtml() {
        var time       = ListygoObj.time;
        var phone      = ListygoObj.phone;
        return '<div class="rn-ingredient-item">' +
            '<div class="rn-ingredient-fields">' +
            '<input type="text" placeholder="'+time+'" class="form-control" name="listygo_doctor_chamber[][time]">' +
            '<input type="text" placeholder="'+phone+'" class="form-control" name="listygo_doctor_chamber[][phone]">' +
            '</div>' +
            '</div>' +
            '<div class="chamber-image-wrap"><div class="chamber-input-wrapper"><input name="listygo_chamber_img[]" class="listygo-chamber-image" type="file"/></div></div>';
    }

    $(document).on('click', '.doctor-chamber-wrapper .add-recipe', function (e) {
        e.preventDefault();
        var cname       = ListygoObj.cname;
        var location      = ListygoObj.location;
        var _self = $(this),
            recipe = '<div class="dr-clinic-item">' +
                '<span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>' +
                '<div class="rn-recipe-title">' +
                '<input type="text" name="listygo_doctor_chamber[][cname]" class="form-control" placeholder="'+cname+'">' +
                '<textarea name="listygo_doctor_chamber[][cloaction]" class="form-control" placeholder="'+location+'"></textarea>' +
                '</div>' +
                '<div class="rn-ingredient-wrap">' + getClinictHtml() + '</div>' +
                '</div>';
        _self.closest('.doctor-chamber-wrapper').find('.dr-clinic-wrap').append(recipe);
        updateClinicIndexing();
    });

    $(document).on('click', '.dr-clinic-item > .rn-remove', function (e) {
        e.preventDefault();
        var _self = $(this);
        if (_self.closest('.doctor-chamber-wrapper').find('.dr-clinic-item').length >= 0) {
            _self.closest('.dr-clinic-item').slideUp('slow', function () {
                $(this).remove();
                updateClinicIndexing();
            });
        } else {
            alert('You are not permited to remove all floor. If you do not want this remove from settings');
        }
    });

    // Delete Floor Image
    $(".remove-chamber-image a").on("click", function (e) {
        e.preventDefault();
        let attachmentID = $(this).data('attachment_id');
        let indexNo = $(this).data('index');
        let postID = $(this).data('post_id');
        let container = $(this).parents('.chamber-image');
        let inputWrapper = $('.chamber-input-wrapper');

        let r = confirm('Are you want to delete this attachment?');

        if (r) {
            $.ajax({
                type: "post",
                url: rtcl.ajaxurl,
                data: {
                    action: "delete_clinic_attachment",
                    index: indexNo,
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

})(jQuery);