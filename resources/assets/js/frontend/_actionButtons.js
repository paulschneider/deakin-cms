(function($) {
  'use strict';

  var $links = $('.three-column-wysiwyg .action-button, .two-column-wysiwyg .action-button');

  if ($links.length) {
    $links.each(function() {
      var $self = $(this),
          $wrapper = $self.closest('.content-wrapper'),
          $clone = $self.clone();

      $clone.addClass('hidden-xs hidden-sm action-bottom');
      $wrapper.after($clone);
    });

    $links.addClass('invisible-lg invisible-md');
  }
}(jQuery));