(function($) {

  function removePreviews(element) {
    if (element.name == 'editor') {
      while (element.firstChild) {
        element.removeChild(element.firstChild);
      }
      element.remove();
    }

    for (var i in element.children) {
      removePreviews(element.children[i]);
    }
  }

  CKEDITOR.plugins.add('icons', {
    requires: ['widget', 'iframedialog'],
    icons: 'icons',
    init: function(editor) {
      CKEDITOR.dialog.add('icons', this.path + 'dialogs/icons.js');

      if (typeof editor.config.icon_url === 'undefined') {
        editor.config.icon_url = '/admin/icons/iframe';
        editor.config.icon_contents_url = '/admin/icons/wysiwyg';
      }

      editor.on('toDataFormat', function(evt) {
        var element = evt.data.dataValue; // -> CKEDITOR.htmlParser.fragment instance
        removePreviews(element);
      }, null, null, 12);

      if (editor.contextMenu) {
        editor.addMenuGroup('iconsGroup');
        editor.addMenuItem('iconsItem', {
          label: 'Edit Icon',
          icon: this.path + 'icons/icons.png',
          command: 'icons',
          group: 'iconsGroup'
        });

        editor.contextMenu.addListener(function(element) {
          if (element.find('.svg-icon').$.length) {
            return {
              iconsItem: CKEDITOR.TRISTATE_OFF
            };
          }
        });
      }

      editor.widgets.add('icons', {
        button: 'Insert an icon',
        inline: true,
        draggable: true,
        template: '<span class="svg-icon"></span>',
        requiredContent: 'span(svg-icon)[data-icon-id]',
        allowedContent: 'span(!svg-icon)[class,data-icon-id]',
        dialog: 'icons',
        init: function() {
          if (this.element.$.getAttribute('data-icon-id')) {
            this.setData('iconid', this.element.$.getAttribute('data-icon-id'));
          }

          CKEDITOR.dialog.addIframe('icon_frame', 'Browse icons', editor.config.icon_url, 1000, 400, function() {
            var log = CKEDITOR.dialog.getCurrent();
            document.getElementById(log.getButton('ok').domId).style.display = 'none';
          });
        },
        upcast: function(element) {
          return element.name == 'span' && element.hasClass('svg-icon');
        },
        data: function() {
          this.element.$.removeAttribute('data-icon-id');

          if (this.data.iconid) {

            this.element.$.setAttribute('data-icon-id', this.data.iconid);

            var that = this;

            setTimeout(function() {
              that.getIcon.apply(that, [editor, that.data]);
            }, 300);

          }
        },
        getIcon: function(editor, info, element) {
          var that = this,
            post = {};

          if (info.iconid.match(/^fa-/)) {

            var fa = '<i class="fa ' + info.iconid + '"></i>';

            if (element === undefined) {
              that.element.setHtml('<editor>' + fa + '</editor>');
            } else {
              element.innerHTML = fa;
            }

            return;
          }

          post._token = window.top._token;
          post._ckeditor = true;

          CKEDITOR.ajax.post(
            editor.config.icon_contents_url + '/' + info.iconid,
            JSON.stringify(post),
            'application/json',
            function(data) {
              data = JSON.parse(data);
              if (element === undefined) {
                that.element.setHtml('<editor>' + data.icon.svg + '</editor>');
              } else {
                element.innerHTML = data.icon.svg;
              }
            }
          );
        }
      });
    }
  });
}(jQuery));

