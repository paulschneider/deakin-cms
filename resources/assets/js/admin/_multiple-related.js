(function($) {
  CMSAdmin.related = {
    $related: $('.related-multiple-fields'),
    initialize: function() {

      this.delegateEvents();

      // Initialize the widget
      this.$related.multipleFields({
        fieldName: 'related_links',
        removeButtonText: '<i class="fa fa-trash"></i>'
      });
    },
    selectIcon: function(e) {
      e.preventDefault();
      var $self = $(this);

      // Remove all elements are requesting
      CMSAdmin.related.$related.find('.requesting-icon').removeClass('.requesting-icon');
      // Add the requesting class to this item
      $self.parent().addClass('requesting-icon');

      $(this).magnificPopup({
        type: 'iframe'
      }).trigger('click');
    },
    bindSelectChanges: function () {
      var $selects = this.$related.find('.multiple-field select').not('.bound');

      // Find each of the selects and get their values
      $selects.each(function() {

        var $select = $(this);

        $select.on('change', function(){
          var value = $(this).val(),
              $row = $(this).parents('.row:first');
              $url = $row.find('input[name*="external_url"]');

          if (value) {
            $url.val('');
            $url.parents('div:first').hide();
          } else {
            $url.parents('div:first').show();
          }
        });

        $select.trigger('change').addClass('bound');
      });
    },
    setIcon: function(value) {
      var icon = JSON.parse(value.value),
          $related = CMSAdmin.related.$related.find('.requesting-icon');

      if ($related.length) {
        $related.find('.svg').html(icon.svg);
        $related.find('.related-icon').val(icon.id);
        $related.removeClass('requesting-icon');
        $('.mfp-close').trigger('click');
      }
    },
    delegateEvents: function() {
      var that = this;

      // Once the multiple selector has been initialized fire this function
      this.$related.on('initialized', function() {
        // Cache the elements to toggle
        that.bindSelectChanges();
      });

      // When a widget has been added
      this.$related.on('added removed', function(e) {
        that.bindSelectChanges();
      });

      // Open the icon picker
      this.$related.on('click', '.select-icon', that.selectIcon);
      // Clear out the svg element when a new field has been created
      this.$related.on('added', function(e, newfield) {
        newfield.find('.svg').html('');
      });
    }
  };
}(jQuery));
