@extends('layouts/main')

@section('content')

<div class="container" style="margin-top: 20px">    
    <h4 class="float-left" style="text-align: center">Portfolio Value: $106,236.84</h4>
    <canvas id="chart-canvas" style="margin: 0 auto; padding: 10px"></canvas>
    <h5 class="float-right">Net Gain/Loss: $6,236.84</h5>
    <br>
</div>

<div class="container" style="margin-top: 40px">
    <h5>
        <span id="tableDate"></span>
        <span id="tableCash" class="float-right"></span>
    </h5>
    <div class="table-responsive rounded">
        <table id="resultTable" class="table invisible">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Symbol</th>
                <th scope="col">Purchase Date</th>
                <th scope="col">Expire Date</th>
                <th scope="col">Strike</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Number of Contracts</th>
                <th scope="col">Current Price</th>
                <th scope="col">Current Value</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>

	var results = @json($results);

    function generateChartData(){
        data = []
        for(let i = 0; i < 30; i++)
            data.push(Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
        return data;
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
            labels: [new Date(2019, 11, 1), new Date(2019, 11, 2), new Date(2019, 11, 3), new Date(2019, 11, 4), new Date(2019, 11, 5), new Date(2019, 11, 6), new Date(2019, 11, 7), new Date(2019, 11, 8), new Date(2019, 11, 9), new Date(2019, 11, 10), new Date(2019, 11, 11), new Date(2019, 11, 12), new Date(2019, 11, 13), new Date(2019, 11, 14), new Date(2019, 11, 15), new Date(2019, 11, 16), new Date(2019, 11, 17), new Date(2019, 11, 18), new Date(2019, 11, 19), new Date(2019, 11, 20), new Date(2019, 11, 21), new Date(2019, 11, 22), new Date(2019, 11, 23), new Date(2019, 11, 24), new Date(2019, 11, 25), new Date(2019, 11, 26), new Date(2019, 11, 27), new Date(2019, 11, 28), new Date(2019, 11, 29), new Date(2019, 11, 30)],
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

        // Put date and cash balance at top of table
        var date = chartData.labels[point];
        date = moment(new Date(date)).format('MMMM Do, YYYY');
        document.getElementById('tableDate').innerHTML = date;
        document.getElementById('tableCash').innerHTML = 'Cash Balance: $' + (Math.floor(Math.random() * (120000 - 90000 + 1) ) + 90000);

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
                data[i].purchaseDate = moment(new Date()).format('MM/DD/YYYY');
                data[i].expireDate = moment(new Date()).format('MM/DD/YYYY');
                data[i].strike = "$" +(Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
                data[i].purchasePrice = "$" +(Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
                data[i].numContractsOwned = Math.floor(Math.random() * (14 - 4 + 1) ) + 4;
                data[i].currentPrice = "$" +(Math.floor(Math.random() * (400 - 15 + 1) ) + 15);
                data[i].currentValue = "$" +(Math.floor(Math.random() * (120000 - 90000 + 1) ) + 90000);

            }
        }// **************************************************************************************
        

        // Add data to the table
        for(let i = 0; i < numOptions; i++){
            var row =  document.createElement('tr');
            var values = Object.values(data[i]);
            for(let j = 0; j < 8 ; j++){
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
