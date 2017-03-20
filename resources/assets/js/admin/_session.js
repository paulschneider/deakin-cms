(function($) {

  CMSAdmin.session = {
    // Write a cookie to the session
    writeCookie: function(name, value, days) {
      var date, expires;
      if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires=" + date.toGMTString();
          }else{
        expires = "";
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    },

    // Read a cookie
    readCookie: function(name) {
      var i, c, ca, nameEQ = name + "=";
      ca = document.cookie.split(';');
      for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
          c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
          return c.substring(nameEQ.length,c.length);
        }
      }
      return '';
    },

    // Check the admin nav bar size
    checkNavSize: function() {
      var $button = $('.navbar-header .navbar-minimalize'),
          $nav = $('body'),
          size = this.readCookie('navbar-size');

      // Check the size that it needs to be
      if (size === 'small') {
        $nav.addClass('mini-navbar');
      }

      $button.on('click', function() {
        if ($nav.hasClass('mini-navbar')) {
          CMSAdmin.session.writeCookie('navbar-size', 'small', 1);
        } else {
          CMSAdmin.session.writeCookie('navbar-size', 'large', 1);
        }
      });
    }
  };

}(jQuery));