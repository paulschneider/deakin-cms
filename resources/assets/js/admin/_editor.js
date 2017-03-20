(function($) {
  CMSAdmin.editor = {
    init: function() {
      this.delegateEvents();
    },
    confirmPageExit: function(e) {
      var message = 'Are you sure you want to navigate away from this page?';

      // If it's not passed in
      e = e || window.event;

      if (e) e.returnValue = message;

      return message;
    },
    addOnUnload: function() {
      window.onbeforeunload = CMSAdmin.editor.confirmPageExit;
      $(document).off('DOMSubtreeModified', CMSAdmin.editor.addOnUnload);
    },
    delegateEvents: function() {
      var that = this;

      setTimeout(function() {
        $(document).on('DOMSubtreeModified', CMSAdmin.editor.addOnUnload);
      }, 2000);

      // Remove the question and then allow the redirect
      $(document).on('click', '.cancels-navigation-message', function(e) {
        if (window.onbeforeunload !== null) {
          e.preventDefault();
          window.onbeforeunload = null;
          $(this).trigger('click').closest('.form-group').hide();
        }
      });
    }
  };
}(jQuery));