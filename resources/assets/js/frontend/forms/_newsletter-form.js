(function($, undefined) {
  if (CMS.forms === undefined) {
    CMS.forms = {};
  }

  CMS.forms.newsletterForm = {
    init: function() {
      this.$form = $('#newsletter-form');
      this.$document = $(document);
      this.isValid = false;
      this.delegateEvents();

      return this;
    },
    delegateEvents: function() {
      var that = this;

      this.$document.on('invalid.fndtn.abide', '#newsletter-form', function(e) {
        that.isValid = false;
      });

      this.$document.on('valid.fndtn.abide', '#newsletter-form', function(e) {
        that.isValid = true;
      });
    }
  };
}(jQuery));
