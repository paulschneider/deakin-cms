(function($) {

  /* Create MagnificPopups particular links */
  CMS.magnific = {
    init: function() {
      this.$document = $(document);
      this.ajaxPopUp();
      this.enquireNow();
      this.browseCredentials();
      this.startWindowScroll = 0;
      this.delegateEvents();
      this.$callbackIframe = null;
    },
    ajaxPopUp: function() {
      $('.ajax-popup-link').magnificPopup({
        type: 'iframe'
      });
    },
    browseCredentials: function() {
      $('a[href$="browse-credentials"], a[href$="credentials-and-degrees"]').magnificPopup({
        type: 'iframe',
        fixedContentPos:true,
        callbacks: {
          beforeOpen: function() {
            CMS.menu.close();
            CMS.magnific.startWindowScroll = $(window).scrollTop();
          },
          elementParse: function(item) {
            item.src += item.src.match(/\?/) ? '&' : '?';
            item.src += 'modal=true';
          },
          open: function() {
            $('body').css({
              height: $('.mfp-iframe-holder').height()
            });
            $('.mfp-content').addClass('mfp-large browse-credential-popup');
            $('html,body').addClass('mfp-open');

            this.$callbackIframe = $('.mfp-iframe-scaler iframe');

            var that = this;

            this.$callbackIframe.load(function() {
              that.$callbackIframe.contents().find("html").addClass("mfp-open browse-credential-popup");
              CMS.magnific.adjustIframePadding(that.$callbackIframe.contents().find("html body .main-wrapper"));
            });
          },
          close: function() {
            $('.mfp-content').removeClass('mfp-large');
            $('html,body').removeClass('mfp-open');
            $('body').css({
              height: 'auto'
            });
            $(window).scrollTop(CMS.magnific.startWindowScroll);
          }
        }
      });
    },
    enquireNow: function() {
      /* Request a callback */
      $('a[href*="enquire-now"], a[href$="newsletter-signup"]').magnificPopup({
        type: 'iframe',
        fixedContentPos:true,
        callbacks: {
          beforeOpen: function() {
            CMS.magnific.startWindowScroll = $(window).scrollTop();
          },
          elementParse: function(item) {
            item.src += item.src.match(/\?/) ? '&' : '?';
            item.src += 'modal=true';
          },
          open: function() {

            $('body').css({
              height: $('.mfp-iframe-holder').height()
            });

            $('.mfp-content').addClass('mfp-large');
            $('html,body').addClass('mfp-open');


            // disable overscroll but allow scrollable div to scroll normally
            /*
            $(document).on('touchmove', function(e) {
              e.preventDefault();
            });
            */
            var scrolling = false;

            $('body').on('touchstart', '.mfp-open', function(e) {
              if (!scrolling) {
                scrolling = true;
                if (e.currentTarget.scrollTop === 0) {
                  e.currentTarget.scrollTop = 1;
                } else if (e.currentTarget.scrollHeight === e.currentTarget.scrollTop + e.currentTarget.offsetHeight) {
                  e.currentTarget.scrollTop -= 1;
                }
                scrolling = false;
              }
            });


            $('body').on('touchmove', '.mfp-open', function(e) {
              e.stopPropagation();
            });

            this.$callbackIframe = $('.mfp-iframe-scaler iframe');

            var that = this;

            this.$callbackIframe.load(function() {
              that.$callbackIframe.contents().find("html").addClass("mfp-open");
              CMS.magnific.adjustIframePadding(that.$callbackIframe.contents().find("html body .main-wrapper"));

              // adjust the height dynamically
              var checkRecaptchaExist = setInterval(function() {
                if (that.$callbackIframe.contents().find("html body .g-recaptcha iframe").length) {
                  CMS.magnific.adjustIframePadding(that.$callbackIframe.contents().find("html body .main-wrapper"));
                  clearInterval(checkRecaptchaExist);
                }
              }, 100);
            });
          },
          close: function() {
            $('.mfp-content').removeClass('mfp-large');
            $('html,body').removeClass('mfp-open');
             $('body').css({
               height: 'auto'
             });
            $(window).scrollTop(CMS.magnific.startWindowScroll);
          }
        }
      });
    },
    video: function(e) {
      e.stopPropagation();

      var $self = $(this);

      if ($self.is('a')) {
        e.preventDefault();
      }

      $.magnificPopup.open({
        items: {
          src: $(this).data('video-url')
        },
        iframe: {
          patterns: {
            youtube: {
              src: '//www.youtube.com/embed/%id%?autoplay=1&amp;rel=0'
            }
          }
        },
        type: 'iframe'
      });
    },
    resizeWindow: function(iframe) {
      var iframeHeight = $(iframe).height();
      var windowHeight = $('body').height();
      var newHeight = iframeHeight;

      if (iframeHeight >= windowHeight) {
        newHeight = windowHeight;
      } else if (iframeHeight <= windowHeight) {
        newHeight = iframeHeight;
      }
      return newHeight;
    },
    adjustIframePadding: function(el) {
      $('.mfp-iframe-scaler').animate({
        "padding-top": CMS.magnific.resizeWindow(el)
      }, 500);
    },
    delegateEvents: function() {
      this.$document.on('click', '.video-element', this.video);
      this.$document.on('click', '.video-testimonial-element', this.video);
      this.$document.on('click', '.video-list-element', this.video);
    }
  };
}(jQuery));
