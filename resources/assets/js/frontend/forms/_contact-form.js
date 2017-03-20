(function($, undefined) {
  if (CMS.forms === undefined) {
    CMS.forms = {};
  }

  CMS.forms.contactForm = {
    init: function() {
      this.$form = $('#contact-form');
      this.$document = $(document);
      this.isValid = false;
      this.delegateEvents();

      return this;
    },
    delegateEvents: function() {
      var that = this;

      this.$document.on('invalid.fndtn.abide', '#contact-form', function(e) {
        that.isValid = false;
      });

      this.$document.on('valid.fndtn.abide', '#contact-form', function(e) {
        that.isValid = true;
      });
    }
  };
}(jQuery));