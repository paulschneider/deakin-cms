(function($, undefined) {
  CMS.recaptcha = {
    reCaptchaCallback: function(response) {
      var $required = $('.g-recaptcha-required');

      $required.parent().removeClass('error');
      $required.removeAttr('required');
    },
    reCaptchaExpired: function() {
      var $required = $('.g-recaptcha-required');

      $required.parent().removeClass('error');
      $required.attr('required', true);
    }
  };
}(jQuery));

function reCaptchaCallback(response) {
  CMS.recaptcha.reCaptchaCallback(response);
}

function reCaptchaExpired() {
  CMS.recaptcha.reCaptchaCallback();
}