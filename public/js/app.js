function showLoading() {
    $( '#overlay' ).show();
    $( '#loading' ).show();
    console.log( '[LOG] Loading screen initiated' );
}

function hideLoading( timeout ) {
    timeout = typeof timeout !== 'undefined' ? timeout : 0;
    if ( timeout ) {
        setTimeout( function() {
            $( '#overlay' ).fadeOut();
            $( '#loading' ).fadeOut();
        }, timeout );
    } else {
        $( '#overlay' ).fadeOut();
        $( '#loading' ).fadeOut();
    }
    console.log( '[LOG] Loading screen closed' );
}

function loadDisplayEvents() {

    // Year date picker
    //
    $( '.year.picker' ).datepicker({ 
        changeMonth: false,
        changeYear: true,
        showButtonPanel: true,
        monthNamesShort: ['January','February','March','April','May','June',
            'July','August','September','October','November','December'],
        onClose: function( dateText, inst ) {
            var year = $( '#ui-datepicker-div .ui-datepicker-year :selected' ).val();
            $( this ).val( $.datepicker.formatDate( 'yy', new Date( year, 1, 1 ) ) );
        }
    }).focus( function() {
        $( '.ui-datepicker-calendar' ).hide();
        $( '.ui-datepicker-month' ).attr( 'disabled', 'disabled' );
        $( '#ui-datepicker-div' ).position({
            my: 'center top',
            at: 'center bottom+5',
            of: $( this )
        });  
    });

    // Month date picker
    //
    $( '.month.picker' ).datepicker({
        dateFormat: 'MM yy', 
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        monthNamesShort: ['January','February','March','April','May','June',
            'July','August','September','October','November','December'],
        onClose: function( dateText, inst ) {
            var year = $( '#ui-datepicker-div .ui-datepicker-year :selected' ).val();
            var month = $( '#ui-datepicker-div .ui-datepicker-month :selected' ).val();
            $( this ).val( $.datepicker.formatDate( 'MM yy', new Date( year, month, 1 ) ) );
        }
    }).focus( function() {
        $( '.ui-datepicker-calendar' ).hide();
        $( '#ui-datepicker-div' ).position({
            my: 'center top',
            at: 'center bottom+5',
            of: $( this )
        });  
    });

    // Day date picker
    //
    $( '.day.picker' ).datepicker({
        dateFormat: 'MM d, yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        monthNamesShort: ['January','February','March','April','May','June',
            'July','August','September','October','November','December']
    }).focus( function() {
        $( '#ui-datepicker-div' ).position({
            my: 'center top',
            at: 'center bottom+5',
            of: $( this )
        });  
    });

    // Change the dates forward and back
    //
    $( '#displayNext' ).click( function() {
        var input = $( '.picker:not(.hide)' );
        var orig = input.val(),
            next;
        if ( input.hasClass( 'month' ) ) {
            next = moment( orig, 'MMMM YYYY' ).add( 'months', 1 ).format( 'MMMM YYYY' );
        } else if ( input.hasClass( 'year' ) ) {
            next = moment( orig, 'YYYY' ).add( 'years', 1 ).format( 'YYYY' );
        } else {
            next = moment( orig, 'MMMM D, YYYY' ).add( 'days', 1 ).format( 'MMMM D, YYYY' );
        }
        input.val( next );
    });
    $( '#displayPrev' ).click( function() {
        var input = $( '.picker:not(.hide)' );
        var orig = input.val(),
            prev;
        if ( input.hasClass( 'month' ) ) {
            prev = moment( orig, 'MMMM YYYY' ).subtract( 'months', 1 ).format( 'MMMM YYYY' );
        } else if ( input.hasClass( 'year' ) ) {
            prev = moment( orig, 'YYYY' ).subtract( 'years', 1 ).format( 'YYYY' );
        } else {
            prev = moment( orig, 'MMMM D, YYYY' ).subtract( 'days', 1 ).format( 'MMMM D, YYYY' );
        }
        input.val( prev );
    });

    // Toggle controls for the timeframe
    //
    $( 'menu.controls' ).on( 'click', '.toggle', function() {
        if ( ! $( this ).hasClass( 'on' ) ) {
            $( '.toggle.on' ).removeClass( 'on' );
            $( this ).addClass( 'on' );
        }
        var display = $( this ).data( 'display' );
        $( '.picker:not(.hide)' ).addClass( 'hide' );
        $( '.'+display+'.picker' ).removeClass( 'hide' );
    });

    // Change the timeframe and submit the POST
    //
    $( 'menu.controls' ).on( 'click', '#changeDisplay', function() {

        showLoading();

        var display_type = 'day';
        var display_date = $( '.day.picker' ).val();
        if ( $( '#displayYear' ).hasClass( 'on' ) ) {
            display_type = 'year';
            display_date = $( '.year.picker' ).val();
        } else if ( $( '#displayMonth' ).hasClass( 'on' ) ) {
            display_type = 'month';
            display_date = $( '.month.picker' ).val();
        }

        $.post( 
            'home', 
            { 
                display_type : display_type, 
                display_date : display_date,
                ajax : 1
            }, 
            function( data ) {
                // Update the <main> element with the new charts
                //
                $( 'main' ).html( data );

                loadDisplayEvents();

                // Hide the overlay
                hideLoading( 1000 );
            }
        );

    });

}

$( document ).ready( function() {

    // Load the Dashboard chart events
    //
    if ( $( 'main' ).hasClass( 'dashboard' ) ) {
        loadDisplayEvents();
    }

});