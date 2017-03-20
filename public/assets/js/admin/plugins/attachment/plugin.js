CKEDITOR.plugins.add( 'attachment', {
    requires: ['widget', 'iframedialog'],

    icons: 'attachment',

    init: function( editor ) {


        CKEDITOR.dialog.add( 'attachment', this.path + 'dialogs/attachment.js' );

        if (typeof editor.config.attachment_url === 'undefined') {
            editor.config.attachment_url = '/admin/attachments/iframe';
            editor.config.attachment_preview_url = '/admin/attachments/iframe/preview';
            editor.config.attachment_embed_url = '/admin/attachments/iframe/wysiwyg';
        }

        editor.on( 'toDataFormat', function( evt) {
            var element = evt.data.dataValue; // -> CKEDITOR.htmlParser.fragment instance

            filterPreviews(element);
        }, null, null, 12 );

        var filterPreviews = function(element)
        {
            if (element.name == 'editor') {
                while (element.firstChild) {
                    element.removeChild(element.firstChild);
                }
                element.remove();
            }

            for (var i in element.children) {
                filterPreviews(element.children[i]);
            }
        };

        editor.widgets.add( 'attachment',
        {
            dialog: 'attachment',
            draggable: true,
            inline: true,
            button: 'Upload or insert an attachment',
            template: '<span class="attachment"></span>',
            requiredContent: 'span(attachment)[data-attachment-id]',
            allowedContent:  'span(!attachment)[class,data-attachment-size,data-attachment-align,data-attachment-caption,data-attachment-id,data-attachment-type,data-attachment-mode]',

            upcast: function( element ) {
                return element.name == 'span' && element.hasClass( 'attachment' );
            },

            init: function() {

                if (this.element.$.getAttribute('data-attachment-align')) {
                    this.setData( 'align', this.element.$.getAttribute('data-attachment-align') );
                }
                if (this.element.$.getAttribute('data-attachment-caption')) {
                    this.setData( 'caption', this.element.$.getAttribute('data-attachment-caption') );
                }
                if (this.element.$.getAttribute('data-attachment-id')) {
                    this.setData( 'fileid', this.element.$.getAttribute('data-attachment-id') );
                }
                if (this.element.$.getAttribute('data-attachment-type')) {
                    this.setData( 'filetype', this.element.$.getAttribute('data-attachment-type') );
                }
                if (this.element.$.getAttribute('data-attachment-mode')) {
                    this.setData( 'filemode', this.element.$.getAttribute('data-attachment-mode') );
                }
                if (this.element.$.getAttribute('data-attachment-size')) {
                    this.setData( 'displaysize', this.element.$.getAttribute('data-attachment-size') );
                }
            },

            data: function() {

                this.element.$.removeAttribute('data-attachment-caption');
                this.element.$.removeAttribute('data-attachment-align');
                this.element.$.removeAttribute('data-attachment-size');
                this.element.$.parentElement.removeAttribute('style');

                if (this.data.fileid) {
                    this.element.$.setAttribute('data-attachment-id', this.data.fileid);
                }

                if (this.data.filemode) {
                    this.element.$.setAttribute('data-attachment-mode', this.data.filemode);
                }

                if (this.data.filetype) {
                    this.element.$.setAttribute('data-attachment-type', this.data.filetype);

                    if (this.data.filetype.match(/^image/i)) {

                        if (this.data.filemode && this.data.filemode == 'link') {
                            //this.element.$.setAttribute('data-attachment-mode', this.data.filemode);
                        } else {
                            if (this.data.align) {
                                this.element.$.setAttribute('data-attachment-align', this.data.align);
                                this.element.$.parentElement.setAttribute("style", "float: " + this.data.align);
                            }
                            if (this.data.caption) {
                                this.element.$.setAttribute('data-attachment-caption', this.data.caption);
                            }
                            if (this.data.displaysize) {
                                this.element.$.setAttribute('data-attachment-size', this.data.displaysize);
                            }
                        }
                    }
                }

                if (this.data.fileid) {

                    var element = this.element;
                    var clone = {};
                    var isImage = this.data.filetype.match(/^image/i);

                    Array.prototype.slice.call(element.$.attributes).forEach(function(item) {
                        clone[item.name] = item.value;
                    });

                    clone._token = window.top._token;
                    clone._ckeditor = true;

                    var postData = JSON.stringify( clone );

                    CKEDITOR.ajax.post(
                        editor.config.attachment_embed_url,
                        postData,
                        'application/json',
                        function( data ) {
                            if (data === '') {
                                element.$.parentElement.remove();
                                return;
                            }

                            element.setHtml(data);

                            if (isImage) {
                                element.$.parentElement.className = element.$.parentElement.className + ' cke_attachment_block';
                            }
                        }
                    );
                }
            }

        } );
    }
} );