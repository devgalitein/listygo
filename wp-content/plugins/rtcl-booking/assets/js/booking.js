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
    rtclFilter.add('dateRangePickerOptions', function (options) {
      if (Array.isArray(options.bookedDateList) && options.bookedDateList.length || Array.isArray(options.pendingDateList) && options.pendingDateList.length) {
        options.isCustomDate = function (param) {
          if (options.bookedDateList.length && options.bookedDateList.includes(param.format(options.locale.format))) {
            return 'booked';
          }
          if (options.pendingDateList.length && options.pendingDateList.includes(param.format(options.locale.format))) {
            return 'pending';
          }
        };
      }
      return options;
    });
    rtclInitDateField();
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
    $(".rtcl-my-booking-wrap").on('click', '.rtcl-booking-pagination a', function (e) {
      e.preventDefault();
      var $this = $(this),
        $container = $this.closest('.rtcl-booking-pagination'),
        $wrapper = $container.closest('.rtcl-my-booking-wrap'),
        posts_per_page = $container.attr('data-per-page'),
        pagination_for = $container.attr('data-booking'),
        page = $this.attr('data-page');
      if (!$this.hasClass('current')) {
        $container.find('a').removeClass('current');
        $this.addClass('current');
        var data = {
          posts_per_page: posts_per_page,
          page: page,
          pagination_for: pagination_for,
          action: 'rtcl_booking_pagination',
          __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
        };
        $.ajax({
          url: rtcl_booking.ajax_url,
          data: data,
          type: 'POST',
          beforeSend: function beforeSend() {
            $wrapper.rtclBlock();
          },
          success: function success(data) {
            $wrapper.rtclUnblock();
            $wrapper.find('.rtcl-single-booking-wrap').html(data);
            $('html, body').animate({
              scrollTop: $('.rtcl-MyAccount-wrap').offset().top - 150
            }, 800);
          },
          error: function error(e) {
            console.log(e);
            $wrapper.rtclUnblock();
          }
        });
      }
    });
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
    $(document).on('click', '.rent-guest-quantity .plus-sign', function (e) {
      var _self = $(this),
        _input = _self.closest('.rent-guest-quantity').find('#guest_number'),
        max = _input.data('max'),
        count = _input.val();
      count++;
      if (count > max) {
        alert('Maximum allowed ' + max + ' guests');
        return false;
      }
      _input.val(count);
    });
    $(document).on('click', '.rent-guest-quantity .minus-sign', function (e) {
      var _self = $(this),
        _input = _self.closest('.rent-guest-quantity').find('#guest_number'),
        count = _input.val();
      count--;
      if (count < 1) {
        return false;
      }
      _input.val(count);
    });
    var $range_drp_elements = $('<div class="calendar-status">' + '<div class="status-available"><span>' + rtcl_booking.rent_calendar.available_text + '</span></div>' + '<div class="status-booked"><span>' + rtcl_booking.rent_calendar.booked_text + '</span></div>' + '<div class="status-pending"><span>' + rtcl_booking.rent_calendar.pending_text + '</span></div>' + '<div class="status-past"><span>' + rtcl_booking.rent_calendar.past_text + '</span></div>' + '<div class="status-active"><span>' + rtcl_booking.rent_calendar.active_text + '</span></div>' + '</div>');
    $('body.rent-type-booking').find('.daterangepicker .drp-buttons').before($range_drp_elements);
  });
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
    if (confirm(rtcl_booking.lang.confirm)) {
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
  $(".rtcl-listing-booking-wrap").on('change', '#booking_date', function () {
    var $this = $(this),
      $wrapper = $this.closest('.rtcl-listing-booking-wrap'),
      msgHolder = $("<div class='alert rtcl-response'></div>"),
      $form = $wrapper.find('form'),
      listing_id = $form.find('#listing_id').val();
    var data = {
      post_id: listing_id,
      booking_date: $(this).val(),
      action: 'rtcl_booking_service_day_slots',
      __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
    };
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: data,
      type: 'POST',
      beforeSend: function beforeSend() {
        $form.find('.alert.rtcl-response').remove();
        $form.find('#service_slots').remove();
        $form.find('button[type=submit]').prop("disabled", true);
        $form.rtclBlock();
      },
      success: function success(data) {
        $form.find('button[type=submit]').prop("disabled", false);
        $form.rtclUnblock();
        $(data).insertAfter($this);
        $form.find('.bhs-timepicker').each(function () {
          call_daterangepicker(this, 'time');
        });
      },
      error: function error(e) {
        msgHolder.removeClass('alert-success').addClass('alert-danger').html(e.responseText).appendTo($form);
        $form.find('button[type=submit]').prop("disabled", false);
        $form.rtclUnblock();
      }
    });
  }).on('change keyup', '#no_of_ticket', function () {
    var $this = $(this),
      $wrapper = $this.closest('.rtcl-listing-booking-wrap'),
      $form = $wrapper.find('form'),
      $fee_container = $wrapper.find('.rtcl-booking-cost'),
      no_of_ticket = $this.val() ? $this.val() : 1,
      booking_fee = $form.find('#booking_fee').val();
    $fee_container.find('span > span').text(no_of_ticket * booking_fee);
  }).on('change keyup', '#booking_rent_date', function () {
    var $this = $(this),
      $wrapper = $this.closest('.rtcl-listing-booking-wrap'),
      $form = $wrapper.find('form'),
      $fee_container = $wrapper.find('.rtcl-booking-cost'),
      date_range = $this.val(),
      post_id = $wrapper.find('#post_id').val();
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        booking_date: date_range,
        action: 'rtcl_booking_rent_fee_calculation',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $form.rtclBlock();
      },
      success: function success(response) {
        $form.rtclUnblock();
        if (response.status) {
          $fee_container.find('span > span').text(response.fee);
        }
      },
      error: function error(jqXhr, json, errorThrown) {
        $form.rtclUnblock();
      }
    });
  });
  $(document).on('submit', '.rtcl-listing-booking-wrap form', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.rtcl-listing-booking-wrap'),
      msgHolder = $("<div class='alert rtcl-response'></div>"),
      $form = $wrapper.find('form'),
      no_of_ticket = $form.find('#no_of_ticket').val(),
      no_of_guest = $form.find('#guest_number').val(),
      booking_date = $form.find('#booking_date').val(),
      rent_date = $form.find('#booking_rent_date').val(),
      service_slot = $form.find('#service_slot').val(),
      post_id = $form.find('input#post_id').val();
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        no_of_ticket: no_of_ticket,
        no_of_guest: no_of_guest,
        time_slot: service_slot,
        booking_date: booking_date,
        booking_rent_date: rent_date,
        action: 'rtcl_booking_verification',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $form.find('.alert.rtcl-response').remove();
        $form.find('button[type=submit]').prop("disabled", true);
        $form.rtclBlock();
      },
      success: function success(response) {
        $form.rtclUnblock();
        var msg = '';
        if (response.message.length) {
          msg += "<p>" + response.message + "</p>";
        }
        if (!response.error) {
          if (msg) {
            msgHolder.removeClass('alert-danger').addClass('alert-success').html(msg).appendTo($form);
          }
          if (response.redirect_url) {
            setTimeout(function () {
              window.location.href = response.redirect_url;
            }, 500);
          }
        } else {
          $form.find('button[type=submit]').prop("disabled", false);
          if (msg) {
            msgHolder.removeClass('alert-success').addClass('alert-danger').html(msg).appendTo($form);
          }
        }
      },
      error: function error(jqXhr, json, errorThrown) {
        msgHolder.removeClass('alert-success').addClass('alert-danger').html(e.responseText).appendTo($form);
        $form.find('button[type=submit]').prop("disabled", false);
        $form.rtclUnblock();
      }
    });
  });
  $(document).on('submit', '.rtcl-booking-confirmation-wrapper form', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.rtcl-booking-confirmation-wrapper'),
      msgHolder = $("<div class='alert rtcl-response'></div>"),
      $form = $wrapper.find('form');
    var data = new FormData(this);
    data.append("__rtcl_wpnonce", rtcl_booking.__rtcl_wpnonce);
    data.append("action", "rtcl_booking_confirmation");
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      type: 'POST',
      beforeSend: function beforeSend() {
        $form.find('.alert.rtcl-response').remove();
        $form.find('button[type=submit]').prop("disabled", true);
        $form.rtclBlock();
      },
      success: function success(response) {
        $form.rtclUnblock();
        var msg = '';
        if (response.message.length) {
          response.message.map(function (message) {
            msg += "<p>" + message + "</p>";
          });
        }
        if (response.success) {
          if (msg) {
            msgHolder.removeClass('alert-danger').addClass('alert-success').html(msg).appendTo($form);
          }
          if (response.redirect_url) {
            setTimeout(function () {
              window.location.href = response.redirect_url;
            }, 500);
          }
        } else {
          $form.find('button[type=submit]').prop("disabled", false);
          if (msg) {
            msgHolder.removeClass('alert-success').addClass('alert-danger').html(msg).appendTo($form);
          }
        }
      },
      error: function error(jqXhr, json, errorThrown) {
        msgHolder.removeClass('alert-success').addClass('alert-danger').html(e.responseText).appendTo($form);
        $form.find('button[type=submit]').prop("disabled", false);
        $form.rtclUnblock();
      }
    });
  });
  $('.rtcl-my-booking-wrap').on('click', '#approve-booking', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.single-booking'),
      cancel_btn = '<button id="cancel-booking" class="btn btn-warning">' + rtcl_booking.cancel_text + '</button>',
      post_id = $wrapper.attr('data-id'),
      booking_id = $wrapper.attr('data-booking-id');
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        booking_id: booking_id,
        action: 'rtcl_booking_request_approve',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $this.prop("disabled", true);
        $wrapper.rtclBlock();
      },
      success: function success(response) {
        $this.prop("disabled", false);
        if (response.success) {
          toastr.success(response.message);
          $wrapper.find('.booking-status').text(rtcl_booking.approve_text);
          $wrapper.find('.booking-btn').html(cancel_btn);
        } else {
          toastr.error("Something Wrong!");
        }
        $wrapper.rtclUnblock();
      },
      error: function error(err) {
        console.log(err);
        $this.prop("disabled", false);
        $wrapper.rtclUnblock();
      }
    });
  }).on('click', '#reject-booking', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.single-booking'),
      delete_btn = '<button id="delete-booking" class="btn btn-danger">' + rtcl_booking.delete_text + '</button>',
      post_id = $wrapper.attr('data-id'),
      booking_id = $wrapper.attr('data-booking-id');
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        booking_id: booking_id,
        action: 'rtcl_booking_request_reject',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $this.prop("disabled", true);
        $wrapper.rtclBlock();
      },
      success: function success(response) {
        $this.prop("disabled", false);
        if (response.success) {
          toastr.success(response.message);
          $wrapper.find('.booking-status').text(rtcl_booking.reject_text);
          $wrapper.find('.booking-btn').html(delete_btn);
        } else {
          toastr.error("Something Wrong!");
        }
        $wrapper.rtclUnblock();
      },
      error: function error(err) {
        console.log(err);
        $this.prop("disabled", false);
        $wrapper.rtclUnblock();
      }
    });
  }).on('click', '#cancel-booking', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.single-booking'),
      delete_btn = '<button id="delete-booking" class="btn btn-danger">' + rtcl_booking.delete_text + '</button>',
      post_id = $wrapper.attr('data-id'),
      booking_id = $wrapper.attr('data-booking-id');
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        booking_id: booking_id,
        action: 'rtcl_booking_request_cancel',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $this.prop("disabled", true);
        $wrapper.rtclBlock();
      },
      success: function success(response) {
        $this.prop("disabled", false);
        if (response.success) {
          toastr.success(response.message);
          $wrapper.find('.booking-status').text(rtcl_booking.canceled_text);
          $wrapper.find('.booking-btn').html(delete_btn);
        } else {
          toastr.error("Something Wrong!");
        }
        $wrapper.rtclUnblock();
      },
      error: function error(err) {
        console.log(err);
        $this.prop("disabled", false);
        $wrapper.rtclUnblock();
      }
    });
  }).on('click', '#delete-booking', function (e) {
    e.preventDefault();
    var $this = $(this),
      $wrapper = $this.closest('.single-booking'),
      post_id = $wrapper.attr('data-id'),
      booking_id = $wrapper.attr('data-booking-id');
    $.ajax({
      url: rtcl_booking.ajax_url,
      data: {
        post_id: post_id,
        booking_id: booking_id,
        action: 'rtcl_booking_delete_data',
        __rtcl_wpnonce: rtcl_booking.__rtcl_wpnonce
      },
      type: 'POST',
      beforeSend: function beforeSend() {
        $this.prop("disabled", true);
        $wrapper.rtclBlock();
      },
      success: function success(response) {
        $this.prop("disabled", false);
        if (response.success) {
          toastr.success(response.message);
          $wrapper.slideUp().remove();
        } else {
          toastr.error("Something Wrong!");
        }
        $wrapper.rtclUnblock();
      },
      error: function error(err) {
        console.log(err);
        $this.prop("disabled", false);
        $wrapper.rtclUnblock();
      }
    });
  });
})(jQuery);
