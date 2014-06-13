<?php   ?>

<div id="container" style="width:100%; height:320px;"></div>

<script type="text/javascript">
    $(function () { 
        $('#container').highcharts({
            chart: {
                type: 'line'
            },
            colors: [ '#007ab1', '#009a9c', '#00b158', '#88409c', 
                '#9eb64c', '#b0352c', '#d6a70e', '#484848' ],
            title: {
                text: 'Daily Energy Consumption (April 2014)'
            },
            xAxis: {
                categories: [<?php echo $data[ 'x_days' ]; ?>],
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
            series: [{
                name: null,
                data: [<?php echo $data[ 'y_vals' ]; ?>]
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
