(function($) {
  CMSAdmin.banners = {
    imageUrl: '/admin/bananas/ajax/',
    selector: function() {
      $('#attach-banner-select').change(function(){

        var $into = $('#attach-banner-parent');
        $into.find('option').remove();

        if ( ! $(this).val() ) {
          var newOption = $('<option/>').attr('value', '').text('Please select a group');
          $into.append(newOption);
        } else {
          $.get('/admin/bananas/' + $(this).val() + '/images/ajax', function(data){
            var keys = Object.keys(data);
            for (i = 0; i < keys.length; i++) {
              var key = keys[i];
              var newOption = $('<option/>').attr('value', data[i].key).text(data[i].value);

              $into.append(newOption);
            }
          });
        }
      });
    },
    // Delegate events for the page
    delegateEvents: function() {
      var that = this;

      $(document).on('change', '.banner-selector #attach-banner-parent', function() {
        var $self = $(this),
            value = $self.val();

        if (value) {
          var promise = that.getImage(value);

          promise.done(function(data) {
            that.injectBanner(data, $self);
          }).fail(function(data) { console.log(data); });
        } else {
          that.removeImage($self);
        }
      });

      $('.banner-selector #attach-banner-parent').trigger('change');
    },
    injectBanner: function(data, $element) {
      var $image = $('<img class="banner-image" src="'+data.banner+'">');
      $element.find('.banner-image').remove();
      $element.after($image);
    },
    removeImage: function($self) {
      $self.parent().find('.banner-image').remove();
    },
    getImage: function(file) {
      // Get the file url
      return $.ajax({
        url: this.imageUrl + file
      });
    }
  };
}(jQuery));
