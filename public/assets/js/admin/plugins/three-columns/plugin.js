/**
 * Plugin to have three columns
 */
CKEDITOR.plugins.add( 'three-columns', {
    requires: 'widget',

    icons: 'three-columns',

    init: function( editor ) {
        editor.widgets.add( 'three-columns', {

            button: 'Create three-columns content',

            template:
                '<div class="widget-three-columns clearfix">' +
                    '<div class="column-one">First column</div>' +
                    '<div class="column-two">Second column</div>' +
                    '<div class="column-three">Three column</div>' +
                '</div>',

            editables: {
                one: {
                    selector: '.column-one',
                },
                two: {
                    selector: '.column-two'
                },
                three: {
                    selector: '.column-three'
                }
            },

            allowedContent:
                'div(!widget-three-columns); div(!clearfix); div(!column-one); div(!column-two); div(!column-three)',

            requiredContent: 'div(widget-three-columns)',

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'three-columns' );
            }
        } );
    }

} );