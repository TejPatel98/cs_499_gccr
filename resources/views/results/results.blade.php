@extends('layouts/main')

@section('content')

<canvas id="chart-canvas" width="600" height="600" style=""></canvas>


<script>
    var ctx = document.getElementById('chart-canvas').getContext('2d');
    var chartOptions = {
            responsive: false, 
            fill: false,
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: "Date",
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    },
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: "Value ($)",
                    }
                }]
            }
        };
    var chartData = {
            labels: [new Date(2019, 11, 1), new Date(2019, 11, 2), new Date(2019, 11, 3), new Date(2019, 11, 4), new Date(2019, 11, 5), new Date(2019, 11, 6), new Date(2019, 11, 7), new Date(2019, 11, 8), new Date(2019, 11, 9), new Date(2019, 11, 10), new Date(2019, 11, 11), new Date(2019, 11, 12), new Date(2019, 11, 13), new Date(2019, 11, 14), new Date(2019, 11, 15), new Date(2019, 11, 16), new Date(2019, 11, 17), new Date(2019, 11, 18), new Date(2019, 11, 19), new Date(2019, 11, 20), new Date(2019, 11, 21), new Date(2019, 11, 22), new Date(2019, 11, 23), new Date(2019, 11, 24), new Date(2019, 11, 25), new Date(2019, 11, 26), new Date(2019, 11, 27), new Date(2019, 11, 28), new Date(2019, 11, 29), new Date(2019, 11, 30)],
            datasets: [{
                fill: false,
                label: 'Portfolio Value',
                data: [67, 5, 123, 8, 33, 21, 176, 160, 3, 150, 170, 195, 184, 107, 63, 80, 93, 130, 88, 61, 84, 196, 159, 124, 122, 136, 40, 18, 59, 91],
                borderColor: '#1fce97',
                backgroundColor: '#1fce97',
                lineTension: 0,
            }]
        };
    var chart = new Chart(ctx, {type: 'line', data: chartData, options: chartOptions});
    

//     canvas.onclick = function(evt) {
//     var points = chart.getBarsAtEvent(evt);
//     var value = chart.datasets[0].points.indexOf(points[0]);
//     if(value == 5){
//       $('#myModal').modal('show');
//     } else if(value == 4){
//       $('#myModal1').modal('show');
//     }


//   };
</script>

@endsection