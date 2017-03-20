(function($) {
  CMSAdmin.video = {
    $selector: $('.video-icon-selector'),
    initialize: function() {
      this.delegateEvents();
    },
    selectIcon: function(e) {
      e.preventDefault();

      var $self = $(this);

      // Remove all elements are requesting
      CMSAdmin.video.$selector.find('.requesting-icon').removeClass('.requesting-icon');
      // Add the requesting class to this item
      $self.parents('.selector-group:first').addClass('requesting-icon');

      $(this).magnificPopup({
        type: 'iframe'
      }).trigger('click');
    },
    clearIcon: function(e) {
      e.preventDefault();
      var $self = $(this),
          $changing = $self.parents('.selector-group:first');
      // Add the requesting class to this item
      if ($changing.length) {
        $changing.find('.svg').html('').addClass('sr-only');
        $changing.find('.video-icon').val('');
        $self.addClass('sr-only');
      }
    },
    setIcon: function(value) {
      var icon = JSON.parse(value.value),
          $changing = CMSAdmin.video.$selector.find('.requesting-icon');

      if ($changing.length) {
        $changing.find('.svg').html(icon.svg).removeClass('sr-only');
        $changing.find('.video-icon').val(icon.id);
        $changing.removeClass('requesting-icon');
        $('.mfp-close').trigger('click');
        $changing.find('.clear-icon').removeClass('sr-only');
      }
    },
    delegateEvents: function() {
      var that = this;

      // Open the icon picker
      this.$selector.on('click', '.select-icon', that.selectIcon);
      this.$selector.on('click', '.clear-icon', that.clearIcon);
      // Clear out the svg element when a new field has been created
      this.$selector.on('added', function(e, newfield) {
        newfield.find('.svg').html('').addClass('sr-only');
      });
    }
  };
}(jQuery));
