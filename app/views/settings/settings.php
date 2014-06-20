<form action="settings/weather/add_day" method="post" id="addDay" style="float:left;">
    <input type="text" name="date" id="date" class="datepicker" 
        value="<?php echo date( 'F j, Y' ); ?>" />
    <a href="javascript:;" class="blue button" id="submitAddDay">
        Add weather for day
    </a>
</form>

<div id="message" style="padding: 9px 0 0 20px; float: left;"></div>

<script type="text/javascript">
    $( '.datepicker' ).datepicker({
        dateFormat: 'MM d, yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        monthNamesShort: ['January','February','March','April','May','June',
            'July','August','September','October','November','December']
    }).focus( function() {
        $( '#ui-datepicker-div' ).position({
            my: 'left top',
            at: 'left bottom+5',
            of: $( this )
        });  
    });

    $( '#submitAddDay' ).click( function() {
        var date = $( '#date' ).val();
        showLoading();
        $.post( 
            'settings/weather/add_day', 
            { 
                date : date, 
                ajax : 1
            }, 
            function( data ) {
                $( '#message' ).html( data );
                console.log(data);
                hideLoading( 1000 );
            }
        );
    });
</script>