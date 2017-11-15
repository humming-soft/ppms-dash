$i(function() {
    $i('#chart1').highcharts({
        credits: false,
        chart: {
            type: 'spline',
        },
        exporting: {
            enabled: false
        },
        title: {
            text: 'Actual vs Baseline'
        },
        subtitle: {
//                        enabled: false,
//                        text: 'Current and Targeted'
        },
        xAxis: {
            categories: ['Jan ', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: '% Completed'
            },
            labels: {
                //                            formatter: function() {
                //                                return this.value + '°';
                //                            }
            },
            lineWidth: 2
        },
        legend: {
            enabled: false
        },
        tooltip: {
            valueSuffix: '%'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        plotOptions: {
            spline: {
                marker: {
                    enable: false
                }
            }
        },
        series: [{
                name: 'Baseline',
                data: [3.11, 9.55, 22.23, 37.85, 49.23, 57.26, 64.58, 71.24, 82.92, 89.17, 93.86, 97.59]
            },
            {
                name: 'Actual',
                data: [3.11, 9.55, 21.92, 37.00, 47.50, 55.75, 63.25, 68.75, 80.25, 82.50, 84.00, 84.75],
            }
        ]
    });

    //dial

    $i('#dial1').highcharts({
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            margin: [0, 0, 0, 0],
        },
        title: {
            text: '39%',
            style: {
                color: 'rgb(255, 170, 66)',
            },            
            align: 'center',
            verticalAlign: 'middle',
            y: 10
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: 0,
                endAngle: 360
            }
        },
        series: [{
                type: 'pie',
                name: 'Pier Head',
                innerSize: '90%',
                data: [
                    {
                        name: 'Current',
                        y: 7,
                        color: '#5CD2FC'
                    },
                    {
                        name: 'Remaing',
                        y: 11,
                        color: 'rgba(0,0,0,0.2)'
                    },
                ]
            }]
    });

    $i('#dial2').highcharts({
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            margin: [0, 0, 0, 0],
        },
        title: {
            text: '56%',
            style: {
                color: 'rgb(255, 170, 66)',
            }, 
            align: 'center',
            verticalAlign: 'middle',
            y: 10
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: 0,
                endAngle: 360
            }
        },
        series: [{
                type: 'pie',
                name: 'Pier',
                innerSize: '90%',
                data: [
                    {
                        name: 'Current',
                        y: 10,
                        color: '#5CD2FC'
                    },
                    {
                        name: 'Remaining',
                        y: 8,
                        color: 'rgba(0,0,0,0.2)'
                    },
                ]
            }]
    });

    $i('#dial3').highcharts({
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            margin: [0, 0, 0, 0],
        },
        title: {
            text: '55%',
            style: {
                color: 'rgb(255, 170, 66)',
            }, 
            align: 'center',
            verticalAlign: 'middle',
            y: 10
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: 0,
                endAngle: 360
            }
        },
        series: [{
                type: 'pie',
                name: 'Pile Cap',
                innerSize: '90%',
                data: [
                    {
                        name: 'Current',
                        y: 25,
                        color: '#5CD2FC'
                    },
                    {
                        name: 'Remaining',
                        y: 20,
                        color: 'rgba(0,0,0,0.2)'
                    },
                ]
            }]
    });

    $i('#dial4').highcharts({
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            margin: [0, 0, 0, 0],
        },
        title: {
            text: '65%',
            style: {
                color: 'rgb(255, 170, 66)',
            }, 
            align: 'center',
            verticalAlign: 'middle',
            y: 10
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: 0,
                endAngle: 360
            }
        },
        series: [{
                type: 'pie',
                name: 'Bored Piles',
                innerSize: '90%',
                data: [
                    {
                        name: 'Current',
                        y: 222,
                        color: '#5CD2FC'
                    },
                    {
                        name: 'Remaining',
                        y: 119,
                        color: 'rgba(0,0,0,0.2)'
                    },
                ]
            }]
    });

    $i('#dial5').highcharts({
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            margin: [0, 0, 0, 0],
        },
        title: {
            text: '7%',
            style: {
                color: 'rgb(255, 170, 66)',
            }, 
            align: 'center',
            verticalAlign: 'middle',
            y: 10
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: 0,
                endAngle: 360
            }
        },
        series: [{
                type: 'pie',
                name: 'Spun Piles',
                innerSize: '90%',
                data: [
                    {
                        name: 'Current',
                        y: 23,
                        color: '#5CD2FC'
                    },
                    {
                        name: 'Remaining',
                        y: 309,
                        color: 'rgba(0,0,0,0.2)'
                    },
                ]
            }]
    });
});


$i(function() {
    $i('#prog1').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Percentage']
        },
        yAxis: {
            tickInterval: 100,
            min: 0,
            max: 100,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px',
                    }
                }
            },
        },
        series: [{
                name: 'Current',
                data: [100],
                color: '#F1834E',
            }]
    });
});

$i(function() {
    $i('#prog2').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Piles']
        },
        yAxis: {
            tickInterval: 328,
            min: 0,
            max: 328,
            //endOnTick: false,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'Current',
                data: [222]
            }, {
                name: 'Incomplete',
                data: [106]
            }]
    });
});

$i(function() {
    $i('#prog3').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Pilecaps']
        },
        yAxis: {
            tickInterval: 44,
            min: 0,
            max: 44,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'Current',
                data: [25]
            }, {
                name: 'Incomplete',
                data: [19]
            }]
    });
});

$i(function() {
    $i('#prog4').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Piers']
        },
        yAxis: {
            tickInterval: 33,
            min: 0,
            max: 33,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'Current',
                data: [10]
            }, {
                name: 'Incomplete',
                data: [23]
            }]
    });
});

$i(function() {
    $i('#prog5').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['']
        },
        yAxis: {
            tickInterval: 3,
            min: 0,
            max: 3,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'Current',
                data: [3]
            }]
    });
});

$i(function() {
    $i('#prog6').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Rigs']
        },
        yAxis: {
            tickInterval: 11,
            min: 0,
            max: 11,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'Current',
                data: [7]
            }, {
                name: 'Incomplete',
                data: [4]
            }]
    });
});

$i(function() {
    $i('#legend').highcharts({
        tooltip: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
        ,
        chart: {
            type: 'bar',
            margin: [0, 5, 20, 5],
        },
        title: false,
        xAxis: {
            labels: {
                enabled: false,
            },
            categories: ['Rigs']
        },
        yAxis: {
            lineWidth: 0,
            minorGridLineWidth: 0,
            lineColor: 'transparent',
            minorTickLength: 0,
            tickLength: 0,
            tickInterval: 10,
            min: 0,
            max: 10,
            labels: {
                enabled: false,
            },
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    color: '#333333',
                    enabled: true,
                    format: '{series.name}',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '10px'
                    }
                }
            }
        },
        series: [{
                name: 'remaining',
                data: [5]
            }, {
                name: 'current',
                data: [5]
            }]
    });
});

//Upper part

paths = [
    //["M112.55,658.879c-0.843-0.005-1.686-0.011-2.529-0.017     c-0.706-2.075,0.736-3.454,1.64-4.995c3.193-5.437,6.676-10.679,9.419-16.407c5.158-10.769,2.337-20.279-3.837-29.428     c-1.758-2.604-4.034-2.747-6.299-0.631c-3.284,3.065-6.432,6.275-9.737,9.316c-0.852,0.783-1.691,2.711-3.178,1.441     c-1.853-1.58,0.232-2.735,1.104-3.586c4.478-4.372,8.177-9.723,14.403-11.98c0.654-0.237,1.178-0.351,1.208-1.201     c0.1-2.799-5.705-10.712-8.772-11.135c-3.587-0.495-5.109-2.278-5.823-5.663c-0.667-3.166-2.614-5.542-5.636-7.189     c-4.559-2.485-8.169-5.974-11.198-10.362c-6.704-9.713-13.936-19.061-20.854-28.628c-2.084-2.882-3.558-6.103-2.403-9.803     c0.245-0.785,0.565-2.164,1.818-1.883c1.072,0.24,1.558,1.385,1.141,2.392c-1.492,3.603,0.909,6.101,2.555,8.622     c6.916,10.59,15.601,19.893,22.393,30.592c1.825,2.876,4.894,4.549,7.786,6.131c3.776,2.066,6.187,5.116,7.178,9.105     c0.591,2.381,1.69,3.325,3.989,3.573c3.473,0.375,5.553,2.518,6.977,5.647c3.779,8.31,8.045,16.416,11.468,24.866     c2.513,6.205,1.456,12.79-0.705,18.979C121.848,644.682,116.066,651.178,112.55,658.879z",80],
    //["M261.055,618.955c0.852,0.835,1.704,1.668,2.554,2.5     c0.461,1.761,0.844,3.551,1.399,5.285c3.083,9.637,1.203,17.264-6.606,24.617c-17.919,16.875-35.038,34.603-52.39,52.075     c-4.702,4.734-9.671,7.208-16.457,4.611c-4.329-1.656-9.269-1.97-13.3-4.093c-7.254-3.819-14.717-3.301-22.339-2.786     c-9.002,0.607-17.943-0.125-26.875-1.214c-5.06-0.616-8.533-3.673-9.913-8.049c-3.394-10.759-6.002-21.756-7.106-33.04     c0.848-0.678,1.691-0.78,2.529,0.017c1.917,9.088,3.709,18.204,5.785,27.256c1.839,8.021,4.336,10.209,12.431,11.17     c9.603,1.139,19.268,1.357,28.877,0.428c5.472-0.53,10.493,0.179,15.459,2.379c5.64,2.5,11.616,3.855,17.62,5.253     c3.976,0.925,6.855-0.056,9.456-2.645c19.555-19.462,39.086-38.947,58.582-58.469c2.94-2.943,3.353-6.643,2.744-10.636     C262.758,628.715,260.538,624.062,261.055,618.955z",80],
    ["M411.223,583.677c-3.313,2.814-7.998,5.854-11.956,5.711c-11.073-0.396-21.756,5.479-32.94,1.662     c-2.487-0.849-5.306-0.689-7.844-1.433c-2.808-0.822-4.624,0.197-6.699,1.952c-6.422,5.429-14.246,6.256-21.869,4.375     c-11.931-2.946-23.614-6.905-35.385-10.491c-5.555-1.692-10.001-0.396-13.784,4.139c-5.756,6.902-11.671,13.681-17.732,20.315     c-2.546,2.787-3.385,5.579-1.958,9.048c0.964,0.717,0.437,2.956,2.548,2.501c-1.505-5.899,1.642-9.835,5.404-13.79     c4.816-5.063,9.363-10.396,13.802-15.795c2.76-3.354,5.831-4.451,10.092-3.393c12.44,3.089,24.451,7.747,36.893,10.614     c7.738,1.785,16.076,1.545,22.898-4.176c1.553-1.304,2.975-2.6,5.293-2.002c10.735,2.766,21.483,2.624,32.402,0.764     c5.684-0.968,11.189-1.293,16.596-4.181c3.207-1.713,6.267-3.759,9.418-5.633c-0.705-0.801-1.356-1.648-1.97-2.526     C413.329,582.027,412.257,582.799,411.223,583.677z", 39],
            //["M1205.751,603.867c-5.438-6.076-10.651-12.367-16.335-18.203c-4.552-4.677-8.542-4.839-14.48-1.631     c-6.539,3.534-9.185,3.1-11.357-4.108c-2.601-8.618-8.694-11.609-16.423-13.037c-5.756-1.063-10.995-3.118-16.319-5.704     c-8.02-3.892-16.024-8.895-25.888-6.812c-3.188,0.674-6.658-0.288-9.767-1.587c-10.738-4.487-21.212-9.531-31.479-15.023     c-7.365-3.938-13.378-9.921-20.672-13.823c-11.312-6.056-21.715-13.573-32.956-19.708c-1.48-0.808-2.661-1.603-3.224-3.478     c-4.107-13.664-8.439-27.262-12.576-40.917c-3.982-13.14-12.279-27.976-28.844-30.315c-1.104-0.156-2.115-1.521-3.016-2.467     c-4.711-4.941-9.93-9.525-13.909-15.005c-5.041-6.941-11.089-7.399-18.376-5.171c-4.181,1.28-8.439,2.379-11.754-2.063     c-0.502-0.674-1.756-1.139-2.644-1.113c-6.282,0.184-12.575-1.581-18.864,0.446c-3.847,1.238-7.924,0.939-11.906,0.456     c-7.125-0.867-13.894,0.282-20.501,3.024c-4.601,1.91-9.387,3.368-14,5.249c-5.803,2.367-12.685,3.281-16.648,8.518     c-4.833,6.386-11.962,9.446-17.923,14.156c-2.777,2.193-5.72,2.051-8.518-0.62c-3.364-3.211-7.125-6.009-10.487-9.224     c-7.411-7.084-15.665-12.97-25.038-17.057c-7.107-3.101-13.914-7.311-21.96-7.721c-9.65-0.489-19.307-0.902-28.965-1.14     c-2.808-0.069-5.181-0.824-7.432-2.371c-5.212-3.581-10.777-6.731-15.607-10.766c-7.587-6.335-15.224-9.724-24.474-3.625     c-0.132,0.087-0.319,0.09-0.479,0.137c-4.145,1.211-7.503-0.354-8.306-4.521c-0.846-4.387-1.492-8.927-1.326-13.365     c0.288-7.685,0.579-7.669-6.941-10.304c-18.483-6.478-35.991-14.652-50.875-27.811c-7.302-6.454-15.951-10.824-25.297-13.619     c-9.844-2.943-17.305-0.234-22.035,8.642c-5.331,10.003-10.098,20.329-8.067,32.25c0.541,3.176-0.728,5.398-3.836,5.925     c-8.956,1.516-14.946,7.271-20.857,13.533c-7.051,7.47-7.104,7.204-1.786,16.31c2.295,3.928,5.571,7.851,4.86,12.74     c-2.107,14.504-9.888,25.486-22.898,31.892c-8.669,4.27-18.044,5.312-25.771-2.815c-4.108-4.32-8.906-5.693-14.785-5.241     c-5.046,0.388-10.118,0.555-15.104-1.806c-3.441-1.629-6.787-0.25-8.398,3.811c-1.53,3.857-3.346,7.599-4.846,11.465     c-0.969,2.496-1.901,4.358-5.245,3.982c-2.249-0.253-3.302,1.883-4.042,3.817c-2.398,6.271,0.114,13.296,5.994,16.776     c8.667,5.133,9.225,7.017,6.209,16.371c-2.509,7.784-2.753,15.642,0.387,23.455c1.737,4.323,3.368,8.896,0.98,13.468     c-6.057,11.593-7.726,23.843-6.44,36.749c0.509,5.104,0.444,10.299-0.257,15.459c-0.365,2.686-1.513,3.936-4.131,4.795     c-5.358,1.76-10.815,3.174-15.685,6.216c0.613,0.878,1.265,1.726,1.97,2.526c4.238-2.52,8.644-4.729,13.803-5.386     c5.092-0.649,6.928-4.272,7.041-9.28c0.106-4.661,0.394-9.298,0.042-13.973c-0.855-11.39-0.501-22.742,5.09-33.026     c2.997-5.512,3.356-10.996,1.121-16.313c-3.792-9.021-3.413-17.684-0.216-26.696c2.477-6.981,0.978-10.442-4.93-14.781     c-2.782-2.041-6.075-3.486-7.853-6.779c-2.086-3.862-1.156-10.206,1.894-10.249c4.306-0.062,5.627-2.372,6.833-5.794     c1.318-3.743,3.391-7.219,4.757-10.948c1.061-2.897,3.309-3.913,5.417-2.636c5.958,3.613,12.336,1.852,18.516,2.043     c3.384,0.104,5.994,0.939,8.511,3.195c2.807,2.516,5.345,5.761,9.104,6.711c6.576,1.661,13.267,1.629,19.569-1.291     c13.013-6.031,21.238-16.27,25.197-29.895c1.087-3.741,2.263-7.748,0.195-11.677c-2.315-4.398-4.098-9.097-7.306-12.99     c-0.872-1.06-1.808-1.996-0.296-3.324c7.222-6.349,12.27-15.432,23.25-16.962c4.645-0.648,7.771-4.058,6.828-9.371     c-1.928-10.882,2.446-20.246,7.034-29.487c4.063-8.179,9.511-10.54,18.55-8.381c9.225,2.203,17.405,6.669,24.597,12.735     c13.758,11.606,28.898,20.657,46.083,26.208c3.158,1.021,6.163,2.514,9.294,3.636c1.886,0.676,2.503,1.693,2.168,3.721     c-0.884,5.344,0.184,10.634,0.98,15.878c0.972,6.377,5.897,9.29,11.971,7.414c2.54-0.785,5.173-1.414,7.545-2.563     c3.102-1.507,5.553-0.838,8.218,1.029c7.224,5.06,14.711,9.751,21.813,14.972c3.441,2.528,6.925,3.335,11.079,3.417     c10.314,0.205,21.036-0.775,30.829,1.72c13.98,3.563,27.07,10.176,38.129,20.004c5.224,4.641,10.538,9.179,15.758,13.825     c2.236,1.992,4.847,2.803,7.334,1.235c6.718-4.235,14.19-7.548,19.341-13.857c2.189-2.683,4.542-4.949,7.713-6.062     c16.169-5.672,31.469-14.917,49.754-12.019c3.633,0.577,7.291-1.219,10.876-1.104c6.198,0.199,13.003-2.009,18.219,3.621     c1.178,1.273,2.879,1.444,4.654,0.936c2.717-0.778,5.479-1.397,8.229-2.058c4.629-1.113,8.494-0.745,11.918,3.48     c4.81,5.939,10.346,11.288,15.538,16.919c1.263,1.368,2.751,3.352,4.397,2.947c4.721-1.16,7.009,2.543,10.408,4.167     c8.52,4.069,12.295,12.241,14.771,19.795c4.175,12.74,9.062,25.352,11.933,38.495c1.564,7.165,5.3,11.381,11.696,15.236     c13.348,8.042,27.181,15.328,39.752,24.73c10.816,8.09,23.548,12.971,35.69,18.789c4.749,2.274,9.95,4.523,14.827,3.579     c7.659-1.486,10.517,3.228,13.896,8.013c0.479,0.677,0.937,1.373,1.352,2.091c3.184,5.507,7.192,10.372,11.514,15.008     c0.694,0.744,1.28,1.874,2.339,1.152c1.147-0.783,0.919-2.064,0.071-3.063c-1.4-1.651-2.842-3.271-4.329-4.842     c-4.387-4.635-7.02-10.403-10.494-15.762c4.275,0.878,8.206,2.122,11.768,4.276c4.31,2.604,8.775,4.835,13.839,5.359     c8.334,0.864,14.509,3.708,17.512,12.719c2.735,8.203,7.335,9.171,14.631,4.555c3.973-2.514,7.324-2.439,10.847,0.697     c3.251,2.894,6.076,6.148,8.871,9.454c3.244,3.838,6.527,7.642,10.196,11.931     C1206.713,606.525,1206.897,605.146,1205.751,603.867z",80],
];
$i(window).load(function() {




    var $img = $('#mapimg');
    //Width and height
    var w = $img.width();
    var h = $img.height();


    svg = d3.select("#mapcontainer")
            .append("svg")
            .attr("width", w)
            .attr("height", h)
            .style("position", "absolute")
            .style("top", "0")
            .style("left", "0");

    var v1 = svg.selectAll("path")
            .data(paths)
            .enter()
            .append("path")
            .attr("fill-rule", "evenodd")
            .attr("clip-rule", "evenodd")
            .attr("fill", "#555")
            .attr("class", function(d, i) {
                var status = d[1];
                var c = "viaduct ";
                if (status < 40) {
                    c += "glow-red on";
                } else
                if (status < 60) {
                    c += "glow-yellow on";
                } else
                {
                    c += "glow-green on";
                }
                return c;
            })
            .attr("transform", "translate(-254, -570)")
            .attr("d", function(d, i) {
                return d[0];
            })
            .style("filter", function(d, i) {

                var status = d[1];
                if (status < 40) {
                    return "url(#drop-shadow)"
                } else //delayed
                if (status < 60) { /*return "url(#drop-shadow)"*/
                } else //critical
                {
                    return "";
                }


            })





    var defs = svg.append("defs");
    // create filter with id #drop-shadow
    // height=130% so that the shadow is not clipped
    var filter = defs.append("filter")
            .attr("id", "drop-shadow")
            .attr("height", "200%")
            .attr("width", "200%")
            .attr("y", "-40%")
            .attr("x", "-40%");

    // SourceAlpha refers to opacity of graphic that this filter will be applied to
    // convolve that with a Gaussian with standard deviation 3 and store result
    // in blur
    filter.append("feGaussianBlur")
            .attr("in", "SourceGraphic")
            .attr("stdDeviation", 5)
            .attr("result", "blur");

    // translate output of Gaussian blur to the right and downwards with 2px
    // store result in offsetBlur
    filter.append("feOffset")
            .attr("in", "blur")
            .attr("dx", 0)
            .attr("dy", 0)
            .attr("result", "offsetBlur");

    // overlay original SourceGraphic over translated blurred opacity by using
    // feMerge filter. Order of specifying inputs is important!
    var feMerge = filter.append("feMerge");

    feMerge.append("feMergeNode")
            .attr("in", "offsetBlur")
    feMerge.append("feMergeNode")
            .attr("in", "SourceGraphic");

    $i('#chart').highcharts({
        credits: {
            enabled: false,
        },
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            tickLength: 0,
            gridLineWidth: 0,
            minorGridLineColor: '#E0E0E0',
            minorGridLineWidth: 0,
            minorTickLength: 0,
            minorTickInterval: 'auto',
            categories: ['Progress'],
            labels: {
                enabled: false
            },
        },
        yAxis: {
            tickLength: 0,
            gridLineWidth: 0,
            minorGridLineColor: '#E0E0E0',
            minorGridLineWidth: 0,
            minorTickLength: 0,
            minorTickInterval: 'auto',
            labels: {
                enabled: false,
            },
            title: {
                enabled: false,
            },
            min: 0,
            max: 100,
            //tickInterval: 50,
        },
        tooltip: {
            enabled: true,
            followTouchMove: true,
            followPointer: true,
            formatter: function() {
                return this.series.name + " : " + this.y + ' %';
            }
        },
        legend: {
            reversed: true,
            enabled: true
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    color: '#000',
                    style: {
                        fontSize: '20px',
                    },
                    formatter: function() {
                        if (this.y != 0) {
                            return this.y + " %";
                        } else {
                            return null;
                        }
                    }
                },
                stacking: 'normal',
            },
        },
        series: [{
                name: 'Delayed',
                data: [20],
                color: 'rgb(198, 54, 77)'
            }, {
                name: 'Actual',
                data: [80],
                color: 'rgb(255, 170, 66)'

            }
//                        , {
//                            name: 'Projected',
//                            data: [0, 80],
//                            color: 'rgb(117, 235, 255)'
//
//                        }
        ]
    });


    $i('#donut1').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '<span><span style="color:rgb(255, 170, 66);font-size: 35px;">75%</span><br/><span style="font-size: 12px;">Paid<span></span>',
            align: 'center',
            verticalAlign: 'middle',
//            y: -10,
//            useHTML: true,
        },
        tooltip: {
            enabled: false
                    //pointFormat: '{series.name}:<b>{point.y}%</b>'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
					style: { fontSize: '17px' }
                },
//                            distance: -50,
                //allowPointSelect: true,
                //cursor: 'pointer',
                /*dataLabels: {
                 enabled: true,
                 format: '<b>{point.name}</b>: <b>{point.y}%</b>',
                 style: {
                 color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                 fontSize: '12px'
                 }
                 },*/

                startAngle: 0,
                endAngle: 360
                        //center: ['50%', '75%']
            },
            series: {
                //allowPointSelect: true,
                //slicedOffset: 10
            }
        },
        series: [{
                type: 'pie',
                name: 'Progress',
                innerSize: '95%',
                data: [
                    {
                        //id: 1,
                        name: '75%',
                        y: 75,
//                                    color: '#5CD2FC'
                    },
                    {
                        y: 25,
                        color: 'rgba(0,0,0,0.3)',
                        dataLabels: {enabled: false},
                    }
                ]
            }]
    });

    $i(function() {
        $i('#vo2').highcharts({
            credits: {
                enabled: false,
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: 'Variation <br/> Order',
                align: 'center',
                verticalAlign: 'middle',
                y: 60,
//                            useHTML: true,
            },
            tooltip: {
                enabled: false,
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        inside: true,
                        enabled: true,
                        distance: 10,
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black',
							fontSize: '16px'
                        }
                    },
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%']
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Browser share',
                    innerSize: '95%',
                    data: [
                        {
                            name: '5%',
                            y: 5,
                        },
                        {
                            name: 'Max 20%',
                            y: 15,
                            color: 'rgb(255, 170, 66)',
                            dataLabels: {enabled: true},
                        },
                    ]
                }]
        });
    });

    $i('#chartS').highcharts({
        credits: false,
        chart: {
            type: 'spline',
        },
        exporting: {
            enabled: false
        },
        title: {
            text: 'S-Curve'
        },
        xAxis: {
            categories: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', ]
        },
        yAxis: {
            max: 100,
            title: {
                text: '% Completed'
            },
            labels: {
                //                            formatter: function() {
                //                                return this.value + '°';
                //                            }
            },
            lineWidth: 2,
            min: 0
        },
        legend: {
            enabled: false
        },
        tooltip: {
            valueSuffix: '%'
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        plotOptions: {
            spline: {
                marker: {
                    enable: false
                }
            }
        },
        series: [{
                name: 'Projected Work Progress',
                data: [0.83, 7.14, 16.27, 33.63, 46.42, 55.15, 68.99, 76.60, 87.21, 92.80, 98.38, 100]
            },
            {
                name: 'Baseline Work Progress',
                data: [0.67, 5.72, 13.02, 26.90, 37.14, 44.12, 55.19, 63.68, 72, 79, 88, 100]
            },
            {
                name: 'Actual Progress',
                data: [0.74, 6.36, 14.48, 29.93, 41.31, 49.09, 61.40, 67, 73, 75]
            }
        ]
    });
    
    $i('#bigCurve').highcharts({
        credits: false,
        chart: {
            type: 'spline',
        },
        exporting: {
            enabled: false
        },
        title: {
            text: 'S-Curve'
        },
        xAxis: {
            categories: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', ]
        },
        yAxis: {
            max: 100,
            title: {
                text: '% Completed'
            },
            labels: {
                //                            formatter: function() {
                //                                return this.value + '°';
                //                            }
            },
            lineWidth: 2,
            min: 0
        },
        legend: {
            enabled: false
        },
        tooltip: {
            valueSuffix: '%'
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        plotOptions: {
            spline: {
                marker: {
                    enable: false
                }
            }
        },
        series: [{
                name: 'Projected Work Progress',
                data: [0.83, 7.14, 16.27, 33.63, 46.42, 55.15, 68.99, 76.60, 87.21, 92.80, 98.38, 100]
            },
            {
                name: 'Baseline Work Progress',
                data: [0.67, 5.72, 13.02, 26.90, 37.14, 44.12, 55.19, 63.68, 72, 79, 88, 100]
            },
            {
                name: 'Actual Progress',
                data: [0.74, 6.36, 14.48, 29.93, 41.31, 49.09, 61.40, 67, 73, 75]
            }
        ]
    });

    $i('#chart_progressClaim').highcharts({
        credits: false,
        chart: {
            type: 'spline',
        },
        exporting: {
            enabled: false
        },
        title: {
            text: 'Progress vs Claim (2013-2014)'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [ 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul']
        },
        yAxis: {
            title: {
                text: '% Completed'
            },
            labels: {
                //                            formatter: function() {
                //                                return this.value + '°';
                //                            }
            },
            lineWidth: 2,
            min: 0
        },
        legend: {
            enabled: false
        },
        tooltip: {
            valueSuffix: '%'
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        plotOptions: {
            spline: {
                marker: {
                    enable: false
                }
            }
        },
        series: [{
                name: 'Claim Made',
                data: [15, 15, 15, 15, 50, 50, 50, 75, 75, 75]
            },
            {
                name: 'Progress',
				color: 'rgb(119, 221, 119)',
                data: [0.74, 6.36, 14.48, 29.93, 41.31, 49.09, 61.40, 70.84, 70.64, 70.53],
            }
        ]
    });

}
);