(function($, undefined) {
  var collapsible = function(classes) {

    // Closed by default.
    $('.collapsible-element').removeClass('open').addClass('closed');

    $(document).on('click', classes, function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).closest('.collapsible-element').toggleClass('open');
      $(this).closest('.collapsible-element').toggleClass('closed');
      $(document).trigger('height-changed');
    });
  };

  if (window.CMSAdmin !== undefined) {
    CMSAdmin.collapsible = collapsible;
  }

  if (window.CMS !== undefined) {
    CMS.collapsible = collapsible;
  }
}(jQuery));