function generateChartData(){
    data = []
    for(let i = 0; i < 30; i++)
        data.push(Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
    return data;
}

// Function to generate date labels for each point on chart
function dateLabels(){
    var dateLabels = []
    Object.keys(results).forEach((date) => {
        dateLabels.push(date);
    });
    console.log(dateLabels);
    return dateLabels;
}

var ctx = document.getElementById('chart-canvas').getContext('2d');
var chartOptions = {
        responsive: true, 
        fill: false,
        legend: {display: false},
        scales: {
            xAxes: [{
                type: 'time',
                distribution: 'linear',
                display: true,
                scaleLabel: {
                    display: true,
                    fontSize: 18,
                    labelString: "Date",
                },
                time: {
                    tooltipFormat: 'll'
                },
            }],
            yAxes: [{
                // ticks: {
                //     beginAtZero: true,
                // },
                display: true,
                scaleLabel: {
                    display: true,
                    fontSize: 18,
                    labelString: "Value ($)",
                }
            }],
            
        }
    };
var chartData = {
        // labels: [new Date(2019, 11, 1), new Date(2019, 11, 2), new Date(2019, 11, 3), new Date(2019, 11, 4), new Date(2019, 11, 5), new Date(2019, 11, 6), new Date(2019, 11, 7), new Date(2019, 11, 8), new Date(2019, 11, 9), new Date(2019, 11, 10), new Date(2019, 11, 11), new Date(2019, 11, 12), new Date(2019, 11, 13), new Date(2019, 11, 14), new Date(2019, 11, 15), new Date(2019, 11, 16), new Date(2019, 11, 17), new Date(2019, 11, 18), new Date(2019, 11, 19), new Date(2019, 11, 20), new Date(2019, 11, 21), new Date(2019, 11, 22), new Date(2019, 11, 23), new Date(2019, 11, 24), new Date(2019, 11, 25), new Date(2019, 11, 26), new Date(2019, 11, 27), new Date(2019, 11, 28), new Date(2019, 11, 29), new Date(2019, 11, 30)],
        labels: dateLabels(),
        datasets: [{
            fill: false,
            label: '',
            data: generateChartData(),
            borderColor: '#1fce97',
            backgroundColor: '#1fce97',
            pointBorderColor: '#1c926d',
            pointBackgroundColor: '#1c926d',
            lineTension: 0,
            pointRadius: 6,
            pointHoverRadius: 10,

        }] 
    };
var chart = new Chart(ctx, {type: 'line', data: chartData, options: chartOptions});