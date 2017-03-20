(function($) {
  $(function(){
    // Router to run js funcitons per page
    var bodyClasses = $('body').attr('class').split(' ');
    $(bodyClasses).each(function(key, value) {
      if (value.length) {
        switch(value) {
        case 'multiple-widgets':
          CMSAdmin.widgets();
          break;
        case 'multiple-sections':
          CMSAdmin.sections();
          break;
        case 'multiple-related':
          CMSAdmin.related.initialize();
          break;
        case 'multiple-schedule':
          CMSAdmin.schedule();
          break;
        case 'menu-selector':
          CMSAdmin.menu_selector();
          break;
        case 'banner-selector':
          CMSAdmin.banners.selector();
          CMSAdmin.banners.delegateEvents();
          break;
        case 'collapsibles':
          CMSAdmin.collapsible('.collapsible-element .actions a');
          break;
        case 'icon-picker':
          CMSAdmin.iconPicker.init();
          break;
        case 'editor':
          CMSAdmin.editor.init();
          break;
        case 'videos':
          CMSAdmin.video.initialize();
          break;
        }
      }
    });

    CMSAdmin.session.checkNavSize();
    CMSAdmin.dateTimePickers.init();

    // Focus the search bar so it's quick to search
    $('#top-search').trigger('focus');
  });
}(jQuery));