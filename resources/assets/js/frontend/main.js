/**
 * Main js
 */
(function($) {
  'use strict';

  $.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results === null) {
      return null;
    } else {
      return results[1] || 0;
    }
  };

  // Move to the
  var $alert = $('.alert:first-child');

  if ($alert.length) {
    if ($alert.offset().top > $(window).height()) {
      $('body, html').animate({
        scrollTop: $alert.offset().top - KS.menuHeight - 100
      }, 500);
    }
  };

  $('.team .item, .team .item i').click(function () {
    console.log('icon: ' + icon + ' / ' + 'content: ' + content + ' / ' + 'parent: ' + parent);
    var icon = $(this).children('i');
    var content = $(this).children('.content');
    var parent = $(this);

    content.stop().slideToggle('slow');

    if (icon.hasClass('fa-angle-down')) {
      parent.addClass('open');
      icon.removeClass('fa fa-angle-down');
      icon.addClass('fa fa-angle-up');
    } else {
      parent.removeClass('open');
      icon.removeClass('fa fa-angle-up');
      icon.addClass('fa fa-angle-down');
    }
  });



}(jQuery));

// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}
