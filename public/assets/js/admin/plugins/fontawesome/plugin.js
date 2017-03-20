(function($) {

  CKEDITOR.plugins.add('fontawesome', {
    requires: ['widget', 'iframedialog'],
    icons: 'fontawesome',
    init: function(editor) {
      CKEDITOR.dialog.add('fontawesome', this.path + 'dialogs/fontawesome.js');

      if (typeof editor.config.fontawesome_url === 'undefined') {
        editor.config.fontawesome_url = '/admin/icons/fontawesome';
      }

      if ( editor.contextMenu ) {
          editor.addMenuGroup( 'fontawesomeGroup' );
          editor.addMenuItem( 'fontawesomeItem', {
            label: 'Edit FontAwesome',
            icon: this.path + 'icons/fontawesome.png',
            command: 'fontawesome',
            group: 'fontawesomeGroup'
          });

          editor.contextMenu.addListener(function(element) {
            if (element.find('.fa').$.length) {
              return { fontawesomeItem: CKEDITOR.TRISTATE_OFF };
            }
          });
      }

      editor.widgets.add('fontawesome', {
        button: 'Insert a FontAwesome icon',
        inline: true,
        draggable: true,
        template: '<span class="fontawesome"></span>',
        requiredContent: 'span(fontawesome);i(!fa);',
        allowedContent:
          'span(!fontawesome)[data-icon]; i(!fa)[class];',
        dialog: 'fontawesome',
        init: function() {
          if (this.element.$.getAttribute('data-icon')) {
            this.setData('icon', this.element.$.getAttribute('data-icon'));
          }

          CKEDITOR.dialog.addIframe('fontawesome_frame', 'Browse FontAwesome icons', editor.config.fontawesome_url, 1000, 400, function(){
            var log = CKEDITOR.dialog.getCurrent();
            document.getElementById(log.getButton('ok').domId).style.display = 'none';
          });
        },
        upcast: function(element) {
          return element.name == 'span' && element.hasClass('fontawesome');
        },
        data: function() {
          var that = this;
          this.element.$.removeAttribute('data-icon');

          if (this.data.icon) {
            this.element.$.setAttribute('data-icon', this.data.icon);

            // Set the icon class
            this.element.$.innerHTML = '<i class="fa '+this.data.icon+'"></i>';
          }
        }
      });
    }
  });
}(jQuery));

