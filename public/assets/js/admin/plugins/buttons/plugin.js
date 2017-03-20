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

  CKEDITOR.plugins.add('buttons', {
    requires: ['widget', 'iframedialog'],
    icons: 'buttons',
    init: function(editor) {
      CKEDITOR.dialog.add('buttons', this.path + 'dialogs/buttons.js');

      if (typeof editor.config.icon_url === 'undefined') {
        editor.config.icon_url = '/admin/icons/iframe';
      }

      editor.on('toDataFormat', function(evt) {
        var element = evt.data.dataValue; // -> CKEDITOR.htmlParser.fragment instance
        removePreviews(element);
      }, null, null, 12);

      if (editor.contextMenu) {
        editor.addMenuGroup('buttonsGroup');
        editor.addMenuItem('buttonsItem', {
          label: 'Edit Icon',
          icon: this.path + 'icons/buttons.png',
          command: 'buttons',
          group: 'buttonsGroup'
        });

        editor.contextMenu.addListener(function(element) {
          if (element.find('.icon-wrapper').$.length) {
            return {
              buttonsItem: CKEDITOR.TRISTATE_OFF
            };
          }
        });
      }

      editor.widgets.add('buttons', {
        button: 'Insert a button',
        inline: true,
        draggable: true,
        template: '<span class="widget-icon-link"></span>',
        requiredContent: 'span(widget-icon-link)[data-text,data-url,data-icon-id]',
        allowedContent: 'span(!widget-icon-link)[class,data-url,data-protocol,data-text,data-icon-id,data-target]',
        dialog: 'buttons',
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
          if (this.element.$.getAttribute('data-icon-id')) {
            this.setData('iconid', this.element.$.getAttribute('data-icon-id'));
          }
          if (this.element.$.getAttribute('data-target')) {
            this.setData('target', this.element.$.getAttribute('data-target'));
          }

          CKEDITOR.dialog.addIframe('icon_frame', 'Browse icons', editor.config.icon_url, 1000, 400, function() {
            var log = CKEDITOR.dialog.getCurrent();
            document.getElementById(log.getButton('ok').domId).style.display = 'none';
          });
        },
        upcast: function(element) {
          return element.name == 'span' && element.hasClass('widget-icon-link');
        },
        data: function() {
          var that = this;

          this.element.$.removeAttribute('data-text');
          this.element.$.removeAttribute('data-url');
          this.element.$.removeAttribute('data-protocol');
          this.element.$.removeAttribute('data-icon-id');
          this.element.$.removeAttribute('data-target');

          if (this.data.text) {
            this.element.$.setAttribute('data-text', this.data.text);
          }

          if (this.data.url) {
            this.element.$.setAttribute('data-url', this.data.url);
          }

          if (this.data.protocol) {
            this.element.$.setAttribute('data-protocol', this.data.protocol);
          }

          if (this.data.target) {
            this.element.$.setAttribute('data-target', this.data.target);
          }

          if (this.data.iconid) {

            this.element.$.setAttribute('data-icon-id', this.data.iconid);

          }

          setTimeout(function() {
            that.getIcon.apply(that, [editor, that.data]);
          }, 300);
        },
        getIcon: function(editor, info, element) {
          var that = this,
            post = {};

          if (typeof info.protocol === undefined) {
            return;
          }

          var protocol = info.protocol === undefined ? '' : info.protocol;
          var text = info.text === undefined ? '' : info.text;
          var url = info.url === undefined ? '' : info.url;
          var target = info.target === undefined ? '' : info.target;

          if (info.iconid === '' || info.iconid === undefined) {
            if (element === undefined) {
              that.element.setHtml('<a href="' + protocol + url + '" target="'+target+'"><span class="text">' + text + '</span></a>');
            } else {
              element.innerHTML = '';
            }

            return;
          }

          if (info.iconid.match(/^fa-/)) {

            var fa = '<i class="fa ' + info.iconid + '"></i>';

            if (element === undefined) {
              that.element.setHtml('<a href="' + protocol + url + '" target="'+target+'"><span class="text">' + text + '</span><span class="icon-wrapper"><editor>' + fa + '</editor></span></a>');
            } else {
              element.innerHTML = fa;
            }

            return;
          }

          post._token = window.top._token;
          post._ckeditor = true;

          CKEDITOR.ajax.post(
            editor.config.icon_contents_url + '/' + that.data.iconid,
            JSON.stringify(post),
            'application/json',
            function(data) {
              data = JSON.parse(data);

              if (element === undefined) {
                var protocol = info.protocol === undefined ? '' : info.protocol;
                var template = '<a href="' + protocol + url + '" target="'+target+'"><span class="text">' + text + '</span><span class="icon-wrapper"><editor>' + data.icon.svg + '</editor></span></a>';
                that.element.setHtml(template);
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

