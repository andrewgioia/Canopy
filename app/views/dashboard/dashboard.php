<h2 class="float-left home">
    <?php echo $data[ 'chart' ][ 'title' ]; ?>
</h2>

<menu class="controls">
    <a href="javascript:;" id="toggleWeather" class="square icon button on">
        <i class="fa fa-sun-o"></i>
    </a>
    <a href="javascript:;" id="toggleWeekends" class="square button on">
        <span>Su</span>
    </a>
    <a href="javascript:;" id="toggleVacation" class="square icon button on right-pad">
        <i class="fa fa-plane" style="top:1px;position:relative"></i>
    </a>

    <a href="javascript:;" class="toggle button left">Year</a>
    <a href="javascript:;" class="toggle button inner on">Month</a>
    <a href="javascript:;" class="toggle button right">Hourly</a>
    <input type="text" size="15" class="control monthPicker" value="April 2014" />
</menu>

<div id="month" style="width:100%; height:325px;"></div>

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
            xAxis: [{
                    categories: [<?php echo $data[ 'chart' ][ 'x_days' ]; ?>],
                    plotBands: [
                        { color: '#eaf2f6', from: 15.5, to: 22.5 },
                        { color: 'rgba(120,120,120,.1)', from: 3.5, to: 5.5 },
                        { color: 'rgba(120,120,120,.1)', from: 10.5, to: 12.5 },
                        { color: 'rgba(120,120,120,.1)', from: 17.5, to: 19.5 },
                        { color: 'rgba(120,120,120,.1)', from: 24.5, to: 26.5 },
                    ],
                    tickColor: '#bbb',
                    lineColor: '#aaa'
            }],
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
                backgroundColor: 'rgba(0,0,0,.7)',
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
                    return '<div style="text-align:center;line-height:1.8em;padding:6px 10px 3px;">'+
                        day + '<br /><b>'+ this.y+' kWh</b></div>';
                }
            },
            legend: {
                enabled: false
            },
            series: [
                {
                    name: null,
                    yAxis: 1,
                    type: 'errorbar',
                    color: 'rgba(0,0,0,.2)',
                    stemWidth: 9,
                    whiskerWidth: 0,
                    data: [ [37,61], [41,61], [45,66], [44,51], [42,56],
                            [35,59], [37,53], [49,66], [42,66], [40,68], [53,79], [54,73],
                            [51,82], [62,79], [37,69], [31,49], [37,53], [39,55], [42,68],
                            [41,60], [39,69], [48,76], [47,61], [41,64], [43,67], [48,72],
                            [46,63], [44,64], [45,50], [44,65] ]
                }, {
                    name: null,
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
            credits: false
        });

        // Show/Hide the Weather Overlay
        //
        var chart = $( '#month' ).highcharts(),
            $button = $( '#toggleWeather' );
        $button.click( function() {
            var series = chart.series[0];
            if ( series.visible ) {
                series.hide();
                $button.removeClass( 'on' );
            } else {
                series.show();
                $button.addClass( 'on' );
            }
        });

    });
</script>

<div style="clear:both;"></div>
