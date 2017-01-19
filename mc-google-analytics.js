(function($){
    $(document).ready(function(){   
        if( typeof ga == 'undefined' ) {
            return;
        }

        // TRACK OUTGOING LINKS
        $('a').on('click', function( event ) {
            var a = new RegExp('/' + window.location.host + '/');

            // tracking code not defined
            if( typeof ga == 'undefined' ) {
                // do nothing
            }
            // EMAIL LINK
            else if( this.protocol == 'mailto:' ) {
                if( mcGATrackingParams.email.status ) {
                    // track email click event
                    ga(
                        'send',
                        'event',
                        mcGATrackingParams.email.category,
                        $(this).attr('href').substring(7),
                        location.pathname
                    );
                }
            }
            // LOCAL LINK
            else if( this.hostname == location.hostname ) {
                if( mcGATrackingParams.download.status ) {
                    var extRegEx = '\.(?:'+ mcGATrackingParams.download.extensions +')';
                    var isDoc = this.pathname.match( extRegEx );

                    // is a trackable document
                    if( isDoc ) {
                        var thisCat = mcGATrackingParams.download.category.replace( '{EXT}', isDoc[0].replace( '.', '' ) );

                        // track document download event
                        ga(
                            'send',
                            'event',
                            thisCat,
                            $(this).attr('href'),
                            location.pathname
                        );
                    }
                }
            }
            // EXTERNAL LINK
            else if( !a.test( this.href ) ) {
                if( mcGATrackingParams.external.status ) {
                    // track external link click event
                    ga(
                        'send',
                        'event',
                        mcGATrackingParams.external.category,
                        $(this).attr('href'),
                        location.pathname
                    );
                }
            }
        });
    });
}(jQuery));
