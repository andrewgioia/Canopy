<!-- ENERGY -->

<h1>Add Energy Data</h1>

<form action="settings/energy/add_month" method="post" enctype="multipart/form-data"
    id="addKwhMonth" style="float:left;">
    <input type="file" name="month_xml" id="monthXml" class="file" />
    <a href="javascript:;" class="blue button" id="submitAddXml">
        Import energy XML file
    </a>
</form>


<!-- WEATHER -->

<h1 class="clear" style="padding-top: 40px;">Add Weather Data</h1>

<form action="settings/weather/add_day" method="post" id="addDay" style="float:left;">
    <input type="text" name="date" id="date" class="datepicker" 
        value="<?php echo date( 'F j, Y' ); ?>" />
    <a href="javascript:;" class="blue button" id="submitAddDay">
        Add weather for day
    </a>
</form>

<div id="messageWeather" style="padding: 9px 0 0 20px; float: left;"></div>


<!-- VACATION -->

<h1 class="clear" style="padding-top: 40px;">Add a New Vacation</h1>

<form action="settings/vacation/add" method="post" id="addVacation">
    <div class="addv-wrapper">
        <label>Vacation:</label>
        <input type="text" name="title" id="title" class="control addv-title" value="" />
    </div>
    <div class="addv-wrapper">
        <div class="float-left addv-date-wrapper">
            <label>Start:</label>
            <input type="text" name="start_date" id="startDate" class="datepicker addv-date" value="" />
            <input type="text" name="start_time" id="startTime" class="timepicker addv-time" value="00:00" />
        </div>
        <div class="float-left addv-date-wrapper">
            <label>End:</label>
            <input type="text" name="end_date" id="endDate" class="datepicker addv-date" value="" />
            <input type="text" name="end_time" id="endTime" class="timepicker addv-time" value="00:00" />
        </div>
        <div class="clear"></div>
    </div>
    <div class="addv-wrapper">
        <input type="checkbox" name="empty" id="empty" value="" checked="checked" />
        No one was in the house
    </div>
    <div class="addv-wrapper">
        <a href="javascript:;" class="blue button float-left" id="submitAddVacation">
            Add vacation time
        </a>
        <div id="messageVacation" style="padding: 9px 0 0 20px; float: left;"></div>
        <div class="clear"></div>
    </div>
</form>


<!-- Javascript Controls -->

<script type="text/javascript">

    $( '#submitAddXml' ).click( function() {
        $( '#addKwhMonth' ).submit();
    })

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
                $( '#messageWeather' ).html( data );
                hideLoading( 1000 );
            }
        );
    });

    $( '.timepicker' ).datetimepicker({
        datepicker: false,
        format: 'H:i'
    });

    $( '#submitAddVacation' ).click( function() {
        showLoading();
        var title = $( '#title' ).val();
        var start_date = $( '#startDate' ).val();
        var start_time = $( '#startTime' ).val();
        var end_date = $( '#endDate' ).val();
        var end_time = $( '#endTime' ).val();
        var empty = ( $( '#empty' ).is( ':checked' ) ) ? 1 : 0;
        $.post( 
            'settings/vacation/add', 
            { 
                start_date : start_date,
                start_time : start_time,
                end_date : end_date,
                end_time : end_time,
                title : title,
                empty: empty,
                ajax : 1
            }, 
            function( data ) {
                $( '#messageVacation' ).html( data );
                hideLoading( 1000 );
            }
        );
    });

</script>


