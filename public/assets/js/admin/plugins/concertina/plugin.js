/**
 * Add ckeditor plugin to handle 2 div elements for title and content.
 * Content can be hidden and shown by clicking on the expand/shrink
 * icon at the Title bar
 */
CKEDITOR.plugins.add( 'concertina', {
    requires: 'widget',

    icons: 'concertina',

    init: function( editor ) {
        editor.widgets.add( 'concertina', {

            button: 'Create a concertina content',

            template:
                '<div class="concertina collapsible-element">' +
                    '<div class="header clickable"><h2 class="concertina-title">Title</h2>' +
                    '<div class="actions clickable"><a href="#" class="open-content">open</a><a href="#" class="close-content">close</a></div></div>' +
                    '<div class="detail"><div class="row"><div class="concertina-content col-sm-12"><p>Content...</p></div></div></div>' +
                '</div>',

            editables: {
                title: {
                    selector: '.concertina-title',
                    allowedContent: 'br strong em'
                },
                content: {
                    selector: '.concertina-content'
                }
            },

            allowedContent:
                'div(!concertina); div(!collapsible-element); div(!header); div(!clickable); div(!actions); a(!open-content);' +
                'a(!close-content); div(!detail); div(!concertina-content); div(!col-sm-12);' +
                'div(!row); h2(!concertina-title); h2(!text-center)',

            requiredContent: 'div(concertina)',

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'concertina' );
            }
        } );
    }

} );
