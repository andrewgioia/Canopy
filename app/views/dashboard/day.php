<div id="day" style="width:100%; height:325px;">
    <!-- Chart -->
</div>

<script type="text/javascript">

    // Build the day chart
    //
    $( function () { 
        $( '#day' ).highcharts({
            colors: [ '#007ab1', '#009a9c', '#00b158', '#88409c', 
                '#9eb64c', '#b0352c', '#d6a70e', '#484848' ],
            title: {
                text: null
            },
            xAxis: [
                {
                    categories: [<?php echo $data[ 'chart' ][ 'x_hours' ]; ?>],
                    type: 'datetime',
                    tickColor: '#bbb',
                    lineColor: '#aaa'
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

                    // set some initial variables
                    var s = '<div style="text-align:center;padding:6px 10px 3px;">'+this.x+'<br /><b>';
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
                                val = series.data[index].y+'° | ';
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
                    type: 'line',
                    color: 'rgba(0,0,0,.2)',
<?php   if ( is_array( $data[ 'weather' ] ) && count( $data[ 'weather' ] ) > 0 ):
            echo "
                    data: [ ";
            $i = 1;
            $count = count( $data[ 'weather' ] );
            foreach ( $data[ 'weather' ] as $h => $vals ):
                echo ( $i < $count ) ? $vals->temp."," : $vals->temp;
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
                    data: [<?php echo $data[ 'chart' ][ 'y_kwhs' ]; ?>]
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

    });
</script>

<div class="clear"></div>