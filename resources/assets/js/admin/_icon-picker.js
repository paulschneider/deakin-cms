(function($, undefined) {
  CMSAdmin.iconPicker = {
    $picker: $('.icon-picker'),
    $icons: $('.icon-picker .icon input[type=checkbox]'),
    init: function() {
      this.delegateEvents();
    },
    toggleSelected: function(e) {
      e.stopPropagation();

      var $self = $(this),
          $others = CMSAdmin.iconPicker.$icons.not(this);
      $self.closest('.icon').toggleClass('selected');
      $others.closest('.icon').removeClass('selected');
      $others.prop('checked', '');
    },
    removeSelected: function(e) {
      CMSAdmin.iconPicker.$icons.closest('.icon').removeClass('selected');
      CMSAdmin.iconPicker.$icons.prop('checked', '');
    },
    selectIcon: function(e, plugin) {
      e.preventDefault();
      var $icon = $(this).closest('.icon'),
          $input = $icon.find('input[type=checkbox]'),
          params = plugin.getJsonFromUrl(),
          type = 'ckeditor';

      if (params.hasOwnProperty('type')) {
        type = params.type;
      }

      // Trigger the parent click
      switch (type) {
        case 'ckeditor':
          if (window.parent.CKEDITOR !== undefined) {
            var dialog = window.parent.CKEDITOR.dialog.getCurrent();
            if (dialog) {
              dialog.fireOnce("setIcon", { value: $input.val() });
            }
          }
          break;
        case 'related-links':
          if (window.parent.CMSAdmin.related !== undefined) {
            window.parent.CMSAdmin.related.setIcon({ value: $input.val() });
          }
          break;
        case 'video-icon':
          if (window.parent.CMSAdmin.video !== undefined) {
            window.parent.CMSAdmin.video.setIcon({ value: $input.val() });
          }
          break;
      }
    },
    delegateEvents: function() {
      var that = this;

      // When an icon is selected
      this.$picker.on('change', 'input[type=checkbox]', this.toggleSelected);
      // Stop other events bubbling up
      this.$picker.on('click', '.icon', function(e) {
        e.stopPropagation();
      });
      // Unselect an item when the parent has been clicked
      $(document).on('click', 'body.icon-picker', this.removeSelected);
      this.$picker.on('click', '.icon-selector a', function(e) {
        that.selectIcon.apply(this, [e, that]);
      });
    },
    getJsonFromUrl: function() {
      var query = location.search.substr(1);
      var result = {};
      query.split("&").forEach(function(part) {
        var item = part.split("=");
        result[item[0]] = decodeURIComponent(item[1]);
      });
      return result;
    }
  };
}(jQuery));