(function($) {

  CKEDITOR.plugins.add('actionbuttons', {
    requires: ['widget'],
    icons: 'actionbuttons',
    init: function(editor) {
      CKEDITOR.dialog.add('actionbuttons', this.path + 'dialogs/actionbuttons.js');

      if ( editor.contextMenu ) {
          editor.addMenuGroup( 'actionbuttonsGroup' );
          editor.addMenuItem( 'actionbuttonsItem', {
            label: 'Edit Button',
            icon: this.path + 'icons/actionbuttons.png',
            command: 'actionbuttons',
            group: 'actionbuttonsGroup'
          });

          editor.contextMenu.addListener(function(element) {
            if (element.find('.action-button').$.length) {
              return { actionbuttonsItem: CKEDITOR.TRISTATE_OFF };
            }
          });
      }

      editor.widgets.add('actionbuttons', {
        button: 'Insert an action button',
        draggable: true,
        template: '<div class="action-button"></div>',
        requiredContent: 'div(action-button)[data-text,data-url]',
        allowedContent:
          'div(!action-button)[class,data-url,data-protocol,data-text];',
        dialog: 'actionbuttons',
        init: function() {
          if (this.element.$.getAttribute('data-text')) {
            this.setData('text', this.element.$.getAttribute('data-text'));
          }
          if (this.element.$.getAttribute('data-protocol')) {
            this.setData('protocol', this.element.$.getAttribute('data-protocol'));
          }
          if (this.element.$.getAttribute('data-url')) {
            this.setData('url', this.element.$.getAttribute('data-url'));
          }
        },
        upcast: function(element) {
          return element.name == 'div' && element.hasClass('action-button');
        },
        data: function() {
          var that = this;

          this.element.$.removeAttribute('data-text');
          this.element.$.removeAttribute('data-url');
          this.element.$.removeAttribute('data-protocol');

          if (this.data.text) {
            this.element.$.setAttribute('data-text', this.data.text);
          }

          if (this.data.url) {
            this.element.$.setAttribute('data-url', this.data.url);
          }

          if (this.data.protocol) {
            this.element.$.setAttribute('data-protocol', this.data.protocol);
          }
          var protocol = this.data.protocol === undefined ? '' : this.data.protocol;
          this.element.setHtml('<a href="'+protocol+this.data.url+'">'+this.data.text+'</a>');
        }
      });
    }
  });
}(jQuery));

