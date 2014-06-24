<div id="month" style="width:100%; height:325px;">
    <!-- Chart -->
</div>

<script type="text/javascript">

    // Build the month chart
    //
    $( function () { 
        $( '#month' ).highcharts({
            colors: [ '#007ab1', '#009a9c', '#00b158', '#88409c', 
                '#9eb64c', '#b0352c', '#d6a70e', '#484848' ],
            title: {
                text: null
            },
            xAxis: [
                {
                    categories: [<?php echo $data[ 'chart' ][ 'x_days' ]; ?>],
                    type: 'datetime',
                    tickColor: '#bbb',
                    lineColor: '#aaa',
                    plotBands: [
<?php
    // Loop through the weekends array to print the plotbands
    //
    if ( is_array( $data[ 'weekends' ] ) && count( $data[ 'weekends' ] ) > 0 ):
        $count = count( $data[ 'weekends' ] );
        foreach ( $data[ 'weekends' ] as $we => $vals ):
            $sat = ( isset( $vals[ 's' ] ) ) ? $vals[ 's' ] - 1.5 : $vals[ 'u' ] - 1.5;
            $sun = ( isset( $vals[ 'u' ] ) ) ? $vals[ 'u' ] - 0.5 : $vals[ 's' ] - 0.5;
            echo "
                        { 
                            color: 'rgba(120,120,120,.1)', 
                            from: ".$sat.", 
                            to: ".$sun.", 
                            id: 'weekend-".$we."' 
                        }"; 
            echo ( $we < $count ) ? "," : "";
        endforeach;
    endif;

    // Loop through the vacations array to show those plotbands
    //
    if ( is_array( $data[ 'vacations' ] ) && count( $data[ 'vacations' ] ) > 0 ):
        $have_vacations = 1;
        foreach ( $data[ 'vacations' ] as $v => $vals ):
            $start = date( 'j', strtotime( $vals->date_start ) );
            $start = ( $start - 1.5 > 0 ) ? $start - 1.5 : 0;
            $end = date( 'j', strtotime( $vals->date_end ) );
            $end = ( $end - 0.5 > 0 ) ? $end - 0.5 : 0;
            echo "
                        ,
                        { 
                            color: 'rgba(0,122,177,.1)', 
                            from: ".$start.", 
                            to: ".$end.", 
                            id: 'vacation-".$v."' 
                        }";
        endforeach;
    endif; ?>

                    ]
                }
            ],
            yAxis: [
                {
                    title: null,
                    labels: {
                        format: '{value} kWh',
                        style: {
                            fontSize: '11px'
                        }
                    }
                }, {
                    title: null,
                    labels: {
                        format: '{value}°F',
                        style: {
                            fontSize: '11px'
                        }
                    },
                    opposite: true
                }
            ],
            tooltip: {
                enabled: true,
                animation: false,
                backgroundColor: 'rgba(0,0,0,.8)',
                borderWidth: 0,
                shadow: false,
                shared: true,
                style: {
                    color: '#fff',
                    fontSize: '13px',
                    padding: 0
                },
                useHTML: true,
                formatter: function() {

                    var day = moment( this.x+'-04-2014', 'ddd DD-MM-YYYY' ).format( 'dddd (M/D)' );
                    var s = '<div style="text-align:center;padding:6px 10px 3px;">'+day+'<br /><b>';

                    var chart = this.points[0].series.chart;
                    var cats = chart.xAxis[0].categories;
                    var index = 0;

                    // compute index of corresponding y value in each data array
                    while ( this.x !== cats[index] ) { 
                        index++; 
                    }

                    // loop through series array, using index to get values
                    var val = '';
                    $.each( chart.series, function( i, series ) { 
                        if ( series.name == 'Series 1' ) {
                            if ( series.chart.series[0].visible ) {
                                val = '<b style="color:#e3faff">'+series.data[index].low+'°</b>|'+
                                      '<b style="color:#dfc4bf">'+series.data[index].high+'°</b> &nbsp;';
                            }
                        } else {
                            val = series.data[index].y+'kWh ';
                        }
                        s += val;
                    });  

                    return s;

                }
            },
            legend: {
                enabled: false
            },
            series: [
                {
                    name: null,
                    id: 'weather',
                    yAxis: 1,
                    type: 'errorbar',
                    color: 'rgba(0,0,0,.2)',
                    stemWidth: 9,
                    whiskerWidth: 0,
<?php   if ( is_array( $data[ 'weather' ] ) && count( $data[ 'weather' ] ) > 0 ):
            echo "
                    data: [ ";
            $total_days = count( $data[ 'weather' ] );
            $i = 1;
            foreach ( $data[ 'weather' ] as $wd => $w ):
                if ( $i == $total_days ):
                    echo "[".$w->temp_low.",".$w->temp_high."]";
                else:
                    echo "[".$w->temp_low.",".$w->temp_high."],";
                endif;
                $i++;
            endforeach;
            echo "
                    ]";
        else:
            echo " 
                    visible: false";
        endif; ?>

                }, {
                    name: null,
                    id: 'watts',
                    type: 'line',
                    lineWidth: 3,
                    data: [<?php echo $data[ 'chart' ][ 'y_vals' ]; ?>]
                }
            ],
            plotOptions: {
                series: {
                    marker: {
                        fillColor: '#fff',
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    }
                }
            },
            exporting: {
                buttons: {
                    contextButton: { enabled: false }
                },
                chartOptions: {
                    title: {
                        text: '<?php echo $data[ 'chart' ][ 'title' ]; ?>'
                    }
                }
            },
            credits: false
        });

        // Get the chart instance to work with it
        //
        var chart = $( '#month' ).highcharts();

        // Toggle the weather overlay
        //
        $( '#toggleWeather' ).on( 'click', function() {
            var series = chart.series[0];
            if ( series.visible ) {
                series.hide();
                $( this ).removeClass( 'on' );
            } else {
                series.show();
                $( this ).addClass( 'on' );
            }
        });

        // Toggle the weekend plotband overlay
        //
        var showing_weekends = true;
        $( '#toggleWeekends' ).on( 'click', function() {
            if ( ! showing_weekends ) {
<?php 
    // loop through the weekends array (again) to print the plotbands
    //
    if ( is_array( $data[ 'weekends' ] ) && count( $data[ 'weekends' ] ) > 0 ) {
        foreach ( $data[ 'weekends' ] as $we => $vals ) {
            $sat = ( isset( $vals[ 's' ] ) ) ? $vals[ 's' ] - 1.5 : $vals[ 'u' ] - 1.5;
            $sun = ( isset( $vals[ 'u' ] ) ) ? $vals[ 'u' ] - 0.5 : $vals[ 's' ] - 0.5;
            echo "
                chart.xAxis[0].addPlotBand({
                    color: 'rgba(120,120,120,.1)', from: ".$sat.", to: ".$sun.", id: 'weekend-".$we."' 
                });";
        }
    } 
?>

                $( this ).addClass( 'on' );
            } else {
<?php
    if ( is_array( $data[ 'weekends' ] ) && count( $data[ 'weekends' ] ) > 0 ) {
        foreach ( $data[ 'weekends' ] as $we => $vals ) {
            echo "
                chart.xAxis[0].removePlotBand( 'weekend-".$we."' );";
        }
    }
?>

                $( this ).removeClass( 'on' );
            }
            showing_weekends = ! showing_weekends;
        });

        // Toggle the vacation plotband overlay
        //
        var showing_vacation = true;
        $( '#toggleVacation' ).on( 'click', function() {
            if ( ! showing_vacation ) {
<?php
    if ( $have_vacations == 1 ):
        foreach ( $data[ 'vacations' ] as $v => $vals ):
            $start = date( 'j', strtotime( $vals->date_start ) );
            $start = ( $start - 1.5 > 0 ) ? $start - 1.5 : 0;
            $end = date( 'j', strtotime( $vals->date_end ) );
            $end = ( $end - 0.5 > 0 ) ? $end - 0.5 : 0;
            echo "
                    chart.xAxis[0].addPlotBand({
                        color: 'rgba(0,122,177,.1)', from: ".$start.", to: ".$end.", id: 'vacation-".$v."'
                    });";
        endforeach;
    endif; ?>

                $( this ).addClass( 'on' );
            } else {
<?php
    if ( $have_vacations == 1 ):
        foreach ( $data[ 'vacations' ] as $v => $vals ):
            echo"
                chart.xAxis[0].removePlotBand( 'vacation-".$v."' );";
        endforeach;
    endif; ?>

                $( this ).removeClass( 'on' );
            }
            showing_vacation = ! showing_vacation;
        });

        // Export buttons
        //
        $( '#exportChart' ).click( function() {
            $( '#exportChartMenu' ).toggle().parent().toggleClass( 'active' );
            $( '#exportChartMenu .png' ).click( function() {
                chart.exportChart({
                    sourceWidth: 960,
                    sourceHeight: 320,
                    scale: 1,
                    type: 'image/png'
                });
            });
            $( '#exportChartMenu .svg' ).click( function() {
                chart.exportChart({
                    sourceWidth: 960,
                    scale: 1,
                    type: 'image/svg+xml'
                });
            });
            $( '#exportChartMenu .pdf' ).click( function() {
                chart.exportChart({
                    sourceWidth: 850,
                    scale: 2,
                    type: 'application/pdf'
                });
            });
        });

    });
</script>

<div class="clear"></div>