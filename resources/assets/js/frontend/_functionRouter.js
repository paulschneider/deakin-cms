(function($, undefined) {
  $(function() {

    CMS.menu.init();
    CMS.magnific.init();
    CMS.tableFormatter.init();

    // Router to run js funcitons per page
    var classes = $('body').attr('class');

    if (classes && classes.length) {
      var bodyClasses = classes.split(' ');
      $(bodyClasses).each(function(key, value) {
        if (value.length) {
          switch (value) {
            case 'collapsibles':
              CMS.collapsible('.collapsible-element .actions a, .collapsible-element .clickable');
              break;
            case 'contact-form':
              CMS.forms.contactForm.init();
              break;
            case 'newsletter-form':
              CMS.forms.newsletterForm.init();
              break;
            case 'searchable-banner':
              CMS.search.bindBanners();
              break;
            case 'social':
              CMS.social();
              break;
            case 'home':
              CMS.home.init();
              break;
            case 'has-video-list':
              CMS.videoList.wrapVideos();
              break;
            case 'tick-form':
              CMS.forms.tick.init();
              break;
            case 'use-abide':
              $(document).foundation({
                abide: {
                  live_validate: true,
                  validate_on_blur: true,
                  error_labels: true,
                  focus_on_invalid: true,
                  timeout: 100
                }
              });
              break;
          }
        }
      });
    }

    // Add anchor classes to empty a tags
    $('a:empty').addClass('anchor');
  });
}(jQuery));
