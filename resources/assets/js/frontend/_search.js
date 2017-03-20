(function($, undefined) {
  CMS.search = {
    bindBanners: function() {
      $('.bottom-bar .showhide').on('click', function(e){
        e.preventDefault();

        $('.expanse').slideToggle(function(){
            $(document).trigger('height-changed');
        });
      });
    }
  };

}(jQuery));
