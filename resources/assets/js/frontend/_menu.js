(function($) {
  CMS.menu = {
    $menu: $("body:not(.iframe) #navbar-menu"),
    init: function() {

      var $menu = this.$menu;

      if (!$menu.length) {
        return;
      }

      var $hamburger = $(".hamburger");
      var $header = $('.site-header .sticky');

      /**
       * MMENU
       */

      var options = {
        "offCanvas": {
          "position": "right"
        },

        "extensions": [
          "mm-border-full",
          "multiline",
          "effect-panels-zoom",
          "pagedim-black"
        ],

        "iconPanels": true,

        "navbar": {
          "add" : false,
          "title" : "Deakin Digital"
        },

        "navbars": [
          {
            "position": "top",
            "height": 3,
            "content": [
              "<span class='logo'><img src='/assets/images/deakin-digital-logo-white.png'></span>"
            ]
          },
          {
            "position": "bottom",
            "content": [
              "<a class='fa fa-envelope' href='#/'></a>",
              "<a class='fa fa-twitter' href='#/'></a>",
              "<a class='fa fa-facebook' href='#/'></a>"
            ]
          },
        ]
      };

      var config = {
        "classNames": {
          "selected": "active"
        }
      };
        console.log(JSON.stringify(options));
      $menu.mmenu(options, config).removeClass('hidden');

      /**
       * MMENU BINDING
       */

      var api = $menu.data("mmenu");

      api.bind("close", function() {
         $hamburger.removeClass('is-active');
      });

      api.bind("open", function() {
         //$header.css('top', $menu.height());
      });

      $hamburger.on('click', function() {
        $hamburger.toggleClass("is-active");
        if ($hamburger.hasClass('is-active')) {
          api.open($menu);
        } else {
          api.close($menu);
        }
      });

    },

    close: function() {
      var $menu = this.$menu;
      var api = $menu.data("mmenu");
      api.close($menu);
    }
  };
}(jQuery));
