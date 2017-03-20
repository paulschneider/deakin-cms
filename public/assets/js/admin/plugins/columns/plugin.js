/**
 * Plugin to have to columns
 */
CKEDITOR.plugins.add( 'columns', {
    requires: 'widget',

    icons: 'columns',

    init: function( editor ) {
        editor.widgets.add( 'columns', {

            button: 'Create a columns content',

            template:
                '<div class="widget-columns clearfix">' +
                    '<div class="column-one">First column</div>' +
                    '<div class="column-two">Second column</div>' +
                '</div>',

            editables: {
                one: {
                    selector: '.column-one',
                },
                two: {
                    selector: '.column-two'
                }
            },

            allowedContent:
                'div(!widget-columns); div(!clearfix); div(!column-one); div(!column-two)',

            requiredContent: 'div(widget-columns)',

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'columns' );
            }
        } );
    }

} );