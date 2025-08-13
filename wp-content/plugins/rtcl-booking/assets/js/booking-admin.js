;
(function ($) {
  function showBookingFields() {
    var $this = $('.rtcl-booking-type .rtcl-radio-field input[type=radio]:checked'),
      bookingType = $this.val(),
      $wrapper = $this.closest('.rtcl-booking-holder'),
      $service = $wrapper.find('.rtcl-booking-service-fields'),
      $event = $wrapper.find('.rtcl-booking-event-fields'),
      $rent = $wrapper.find('.rtcl-booking-rent-fields'),
      $pre_order = $wrapper.find('.rtcl-booking-pre-order-fields');
    switch (bookingType) {
      case 'event':
        $service.slideUp();
        $rent.slideUp();
        $pre_order.slideUp();
        $event.slideDown();
        break;
      case 'services':
        $rent.slideUp();
        $event.slideUp();
        $pre_order.slideUp();
        $service.slideDown();
        break;
      case 'rent':
        $service.slideUp();
        $event.slideUp();
        $pre_order.slideUp();
        $rent.slideDown();
        break;
      case 'pre_order':
        $rent.slideUp();
        $event.slideUp();
        $service.slideUp();
        $pre_order.slideDown();
        break;
      default:
        $service.slideUp();
        $rent.slideUp();
        $pre_order.slideUp();
        $event.slideDown();
    }
  }
  function call_daterangepicker(element, type) {
    if (type === 'time') {
      var _rtcl_booking$timePic;
      var defaultOptions = {
        autoUpdateInput: false,
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: /H|HH/.test(((_rtcl_booking$timePic = rtcl_booking.timePicker) === null || _rtcl_booking$timePic === void 0 || (_rtcl_booking$timePic = _rtcl_booking$timePic.locale) === null || _rtcl_booking$timePic === void 0 ? void 0 : _rtcl_booking$timePic.format) || 'hh:mm A'),
        timePickerIncrement: 1
      };
      var name = element.name;
      var options;
      if (name && name.includes("[end]")) {
        var endOptions = {};
        if (rtcl_booking.timePickerEnd && Object.keys(rtcl_booking.timePickerEnd).length) {
          endOptions = Object.assign({}, rtcl_booking.timePicker, rtcl_booking.timePickerEnd || {});
        } else {
          endOptions = rtcl_booking.timePicker;
        }
        options = Object.assign({}, defaultOptions, endOptions || {});
      } else {
        options = Object.assign({}, defaultOptions, rtcl_booking.timePicker || {});
      }
      $(element).daterangepicker(options).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
      });
    }
    $(element).on('apply.daterangepicker', function (ev, picker) {
      $(this).val(picker.startDate.format(picker.locale.format));
    }).on('cancel.daterangepicker', function (ev, picker) {
      $(this).val('');
    });
  }
  showBookingFields();
  $(document).ready(function () {
    var disableDateList = [],
      _unavailableDateField = $('#rent_unavailable_date');
    if (_unavailableDateField.length) {
      disableDateList = _unavailableDateField.val().split(',');
    }
    $('.rtcl-booking-type').on('change', '.rtcl-radio-field input[type=radio]', function () {
      var $this = $(this);
      showBookingFields();
      initRentCalender(disableDateList);
    });
    if ($.fn.daterangepicker) {
      $('.bhs-timepicker').each(function () {
        call_daterangepicker(this, 'time');
      });
    }
    initRentCalender(disableDateList);

    // Toggle disable class and set to input field click on cell
    $(document).on('click', '#rtclBookingCalendar .fc-scrollgrid-sync-table .fc-day', function (e) {
      var _self = $(this),
        _date = _self.attr('data-date');
      if (!_self.hasClass('fc-day-past')) {
        var haveDate = disableDateList.findIndex(function (item) {
          return item === _date;
        });
        if (haveDate >= 0) {
          disableDateList.splice(haveDate, 1);
        } else {
          disableDateList.push(_date);
        }
        _self.toggleClass('disable');
        _unavailableDateField.val(disableDateList);
      }
    });
    var $category_wrap_select = $("#rtcl_listing_details #rtcl-category-wrap select"),
      selected_last_cat_id = 0;
    $category_wrap_select.each(function () {
      selected_last_cat_id = $(this).val();
    });
    rtcl_booking_section_show_hide(selected_last_cat_id);
    $(document).on("change", "#rtcl-category-wrap select", function () {
      var category_id = $(this).val();
      rtcl_booking_section_show_hide(category_id);
    });
  });
  function rtcl_booking_section_show_hide(category_id) {
    var booking_wrap = $("#rtcl_booking.rtcl"),
      data = {
        action: "rtcl_booking_fields_listings",
        term_id: category_id
      };
    $.ajax({
      url: rtcl.ajaxurl,
      data: data,
      type: "POST",
      dataType: "json",
      beforeSend: function beforeSend() {
        booking_wrap.show();
      },
      success: function success(response) {
        if (response.hide) {
          booking_wrap.hide();
        } else {
          booking_wrap.show();
        }
      },
      error: function error(e) {
        console.log(e.responseText);
      }
    });
  }
  function initRentCalender() {
    var disableDateList = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    var calendarEl = document.getElementById('rtclBookingCalendar');
    if (calendarEl) {
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,
        locale: rtcl_booking.locale,
        contentHeight: "auto",
        datesSet: function datesSet() {
          addSelectedActiveClass(disableDateList);
        }
      });
      calendar.render();

      // Set disable class to cell for existing data
      addSelectedActiveClass(disableDateList);
    }
  }
  function addSelectedActiveClass() {
    var disableDateList = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    $('.fc-scrollgrid-sync-table tbody tr').each(function (i) {
      var row = $(this);
      $('td', row).each(function (k) {
        var cell = $(this),
          date = cell.data('date');
        if (disableDateList.includes(date)) {
          cell.addClass('disable');
        }
      });
    });
  }
  $(document).on('click', '.rtcl-booking-service-slots .rtcl-icon-plus', function (e) {
    e.preventDefault();
    var _self = $(this);
    var target = _self.closest('.time-slot');
    var wrapper = target.closest('.time-slots');
    var index = wrapper.find('.time-slot').length || 0;
    var clone = $(target).clone(); //increase index here

    $(clone).find("input[type='text']").each(function () {
      $(this).attr("name", $(this).attr("name").replace(/times\]\[([0-9]+)\]/i, "times][" + index + "]"));
      $(this).val("");
    });
    $(clone).find('.bhs-timepicker').each(function () {
      call_daterangepicker(this, 'time');
    });
    _self.removeClass("rtcl-icon-plus").addClass("rtcl-icon-minus");
    $(target).after($(clone));
  }).on('click', '.rtcl-booking-service-slots .rtcl-icon-minus', function (e) {
    e.preventDefault();
    if (confirm(rtcl_business_hours.lang.confirm)) {
      var _self = $(this);
      var wrapper = _self.closest('.time-slots');
      _self.closest('.time-slot').remove();
      wrapper.find('.time-slot').each(function (index, item) {
        $(this).find("input[type='text']").each(function () {
          $(this).attr("name", $(this).attr("name").replace(/times\]\[([0-9]+)\]/i, "times][" + index + "]"));
        });
      });
    }
  });
})(jQuery);
