jQuery.noConflict();
(function($) {

    let fmWrapper;
    let fmItems;

    /*** Init **/
    $(document).ready(function(){

        //vars
        fmWrapper = $('.postbox .fm-wrapper');
        fmItems = $('.fm-wrapper.fmjs-sortable > .fm-item');

        //Close all content sections on page load
        closeSections();

        //content section toggle action
        $('body').on('click', '.postbox .fm-wrapper > .fm-item > .fmjs-drag', toggleSection);

    });

    /**
     * closeSections
     *
     * Loops any fieldmanager content groups and add css class to close them all on page load
     *
     * @author	Ben Moody
     */
    function closeSections() {

        if( fmItems.length > 0 ) {

            //Loop sections and add closed css class to each one
            fmItems.each(function( index ){

                if( !$(this).hasClass('fmjs-proto') ) {
                    $(this).addClass( 'closed' );
                }

            });

        }

    }

    /**
     * toggleSection
     *
     * Opens and closes felx content groups when cliking on group title
     *
     * @param	type	name
     * @var		type	name
     * @return	type	name
     * @access 	public
     * @author	Ben Moody
     */
    function toggleSection( event ) {

        event.preventDefault();

        const item = $(this).parent('.fm-item');

        if( item.hasClass('closed') ) {

            item.removeClass( 'closed' );

        } else {

            item.addClass( 'closed' );

        }

    }

})(jQuery);