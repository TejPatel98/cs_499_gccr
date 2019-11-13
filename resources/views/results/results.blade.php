@extends('layouts/main')

@section('content')

<div class="container" style="margin-top: 20px">
    <h3 class="float-left" style="text-align: center">Portfolio Value: $106,236.84</h3>
    <canvas id="chart-canvas" style="margin: 0 auto;"></canvas>
    <h5 class="float-right" style="text-align: center;">Net Gain/Loss: $6,236.84</h5>

    <table id="resultTable" class="table table-bordered invisible" style="margin-top: 60px;">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Symbol</th>
            <th scope="col">Purchase Date</th>
            <th scope="col">Number of Contracts</th>
            <th scope="col">Price</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    var ctx = document.getElementById('chart-canvas').getContext('2d');
    var chartOptions = {
            responsive: true, 
            fill: false,
            legend: {display: false},
            scales: {
                xAxes: [{
                    type: 'time',
                    distribution: 'series',
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
                }],
                
            }
        };
    var chartData = {
            labels: [new Date(2019, 11, 1), new Date(2019, 11, 2), new Date(2019, 11, 3), new Date(2019, 11, 4), new Date(2019, 11, 5), new Date(2019, 11, 6), new Date(2019, 11, 7), new Date(2019, 11, 8), new Date(2019, 11, 9), new Date(2019, 11, 10), new Date(2019, 11, 11), new Date(2019, 11, 12), new Date(2019, 11, 13), new Date(2019, 11, 14), new Date(2019, 11, 15), new Date(2019, 11, 16), new Date(2019, 11, 17), new Date(2019, 11, 18), new Date(2019, 11, 19), new Date(2019, 11, 20), new Date(2019, 11, 21), new Date(2019, 11, 22), new Date(2019, 11, 23), new Date(2019, 11, 24), new Date(2019, 11, 25), new Date(2019, 11, 26), new Date(2019, 11, 27), new Date(2019, 11, 28), new Date(2019, 11, 29), new Date(2019, 11, 30)],
            datasets: [{
                fill: false,
                label: '',
                data: [67, 5, 123, 8, 33, 21, 176, 160, 3, 150, 170, 195, 184, 107, 63, 80, 93, 130, 88, 61, 84, 196, 159, 124, 122, 136, 40, 18, 59, 91],
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
    
    // Open table on point click ******************************************
    canvas = document.getElementById('chart-canvas');
    canvas.onclick = function(evt) {
        var clickedPoint =  chart.getElementsAtEvent(evt);
        if(clickedPoint.length > 0){
            point = clickedPoint[0]._index;
            console.log(point + " " + chartData.datasets[0].data[point]);
            generateTable(point);
        }
    }; //****************************************************************** 


    function generateTable(point){

        //*********************** 
        // AJAX request to get data
        //*********************** 

        var tbl = document.getElementById('resultTable');
        var tblBody = tbl.children[1];

        // Remove current table body and append a new blank one
        tblBody.remove();
        tbl.appendChild(document.createElement('tbody'));
        tblBody = tbl.children[1];

        
        // Generate random data*******************************************************************
        // Will be replaced with actual data from AJAX request from above
        var numOptions = Math.floor(Math.random() * (20 - 6 + 1) ) + 6;
        var data = [];
        for(let i = 0; i < numOptions; i++){
            data.push({});
            for (let j = 0; j < 4; j++){
                data[i].symbol = i;
                data[i].datePurchased = Date.now();
                data[i].numOwned = Math.floor(Math.random() * (14 - 4 + 1) ) + 4;
                data[i].contractPrice = "$" +( Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
            }
        }// **************************************************************************************
        

        // Dynamically add data to the table
        for(let i = 0; i < numOptions; i++){
            var row =  document.createElement('tr');
            var values = Object.values(data[i]);
            for(let j = 0; j < 4; j++){
                var td = document.createElement('td');
                td.innerHTML = values[j];
                row.appendChild(td);
            }
            tblBody.appendChild(row);
        }

        tbl.classList.remove('invisible');
        tbl.scrollIntoView({behavior: 'smooth'});
    }

    
</script>

@endsection