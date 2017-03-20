(function($, undefined) {
  CMS.home = {
    init: function() {
      this.newsletter();
    },

    newsletter: function() {
      var newsletter = Cookies.get('newsletter');
      // Leave small devices alone.
      if ( ! newsletter && $(window).width() > 800) {
        setTimeout(function(){
          $('a[href$="newsletter-signup"]:first').trigger('click');
          Cookies.set('newsletter', true, { expires: 7, secure: (document.location.protocol.match(/^https/)) });
        }, 10000);
      }
    }
  };
}(jQuery));
