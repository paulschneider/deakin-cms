(function($, undefined) {
  if (CMS.forms === undefined) {
    CMS.forms = {};
  }

  CMS.forms.tick = {
    init: function() {
      $(function(){
          $('.trigger').addClass('drawn');

          $('.iframe a.close').on('click', function(e){
            e.preventDefault();
            if (window.parent == window.top) {
              window.parent.$.magnificPopup.close();
            }
          });
      });
    }
  };
}(jQuery));
