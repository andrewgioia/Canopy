<?php 
/**
 * Green Button Data Viewer
 * 
 * Import XML files and convert to JSON objects
 * Display beautiful charts and tables to learn
 *
 */

// Load the XML file

$file = 'data/2014-04.xml';

if ( file_exists( $file ) ) {
    $xml = simplexml_load_file( $file );
} else {
    exit( 'Failed to open '.$file.'.' );
}

$array = json_decode( json_encode( $xml ) );
$data = $array->entry[3]->content->IntervalBlock->IntervalReading;

// Run through the new array 

$month = array();

foreach ( $data as $num => $entry ) {

    $month[date( 'd', $entry->timePeriod->start )][date( 'H', $entry->timePeriod->start )] = array(
            'kWh' => number_format( $entry->value/1000, 0 ),
            'cost' => number_format( $entry->cost/100000, 4 )
        );

    //echo "$".number_format( $entry->cost/100000, 4 )." | ". 
    //     date( 'm/d h:ia', $entry->timePeriod->start )." | ". 
    //     number_format( $entry->value/1000, 0 )."\n";

}

// Get the X axis (hours in the day)

$hours = "'".implode( "','", array_keys( $month['02'] ) )."'";

// Get the Y axis (kWh for each day)

$series = array();
foreach ( $month['02'] as $vals ) {
    $series[] = $vals[ 'kWh' ];
}
$series = implode( ",", $series );

?>

<div id="container" style="width:100%; height:400px; display:none;"></div>

<script type="text/javascript">
    $(function () { 
        $('#container').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: 'Hourly Energy Consumption (April 2, 2014)'
            },
            xAxis: {
                categories: [<?php echo $hours; ?>]
            },
            yAxis: {
                title: {
                    text: 'kWh'
                }
            },
            tooltip: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            series: [{
                name: null,
                data: [<?php echo $series; ?>]
            }]
        });
    });
</script>


<?php

// Chart 2: April daily

$days = array();
$x_vals = array();
foreach ( $month as $day => $hours ) {
    $x_vals[] = date( 'D d', mktime( 0, 0, 0, 04, $day, 2014 ) );
    if ( is_array( $hours ) ) {
        foreach ( $hours as $hour => $vals ) {
            $days[$day] += $vals['kWh'];
        }
    }
}

$x_days = "'".implode( "','", $x_vals )."'";
$y_vals = implode( ",", $days );

?>

<div id="container2" style="width:100%; height:320px;"></div>

<script type="text/javascript">
    $(function () { 
        $('#container2').highcharts({
            chart: {
                type: 'line'
            },
            colors: [ '#007db6', '#359fa3', '#ab51c4', '#b94f3c', 
                '#2ba02b', '#d1712d', '#d6a70e', '#484848' ],
            title: {
                text: 'Daily Energy Consumption (April 2014)'
            },
            xAxis: {
                categories: [<?php echo $x_days; ?>],
                plotBands: [
                    { color: '#eaf2f6', from: 15.5, to: 22.5 },
                    { color: 'rgba(150,150,150,.1)', from: 3.5, to: 5.5 },
                    { color: 'rgba(150,150,150,.1)', from: 10.5, to: 12.5 },
                    { color: 'rgba(150,150,150,.1)', from: 17.5, to: 19.5 },
                    { color: 'rgba(150,150,150,.1)', from: 24.5, to: 26.5 },
                ],
                tickColor: '#bbb',
                lineColor: '#aaa'
            },
            yAxis: {
                title: null,
                labels: {
                    format: '{value} kWh',
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            tooltip: {
                enabled: true
            },
            legend: {
                enabled: false
            },
            series: [{
                name: null,
                data: [<?php echo $y_vals; ?>]
            }],
            plotOptions: {
                series: {
                    lineWidth: 3,
                    marker: {
                        fillColor: '#fff',
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    }
                }
            },
            credits: false
        });
    });
</script>
