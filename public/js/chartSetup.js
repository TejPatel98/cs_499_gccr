// Function to get the portfolio value data for each point
function getChartData(){
    portfolioValues = []
    Object.keys(results).forEach((date) => {
        portfolioValues.push(results[date].portfolioValue);
    });
    return portfolioValues;
}

// Function to generate date labels for each point on chart
function dateLabels(){
    var dateLabels = []
    Object.keys(results).forEach((date) => {
        dateLabels.push(moment.utc(new Date(date)).format('MMM Do'));
    });
    return dateLabels;
}

var ctx = document.getElementById('chart-canvas').getContext('2d');
var chartOptions = {
        responsive: true, 
        fill: false,
        legend: {display: false},
        scales: {
            xAxes: [{
                type: 'category',
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
        labels: dateLabels(),
        datasets: [{
            fill: false,
            label: '',
            data: getChartData(),
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

// Function to get end portfolio value and net gain/loss
function getEndValue(){
    var portfolioValueElement = document.getElementById("endPortfolioValue");
    var netGainLossElement = document.getElementById("netGainLoss");
    var netGainLossValueElement = document.getElementById("netGainLossValue");

    var firstDay = Object.keys(results)[0];
    var lastDay = Object.keys(results)[Object.keys(results).length-1];

    var portfolioValue = results[lastDay].portfolioValue;
    var netGainLoss = results[lastDay].portfolioValue - results[firstDay].balance;

    var gainLoss = "";
    if(netGainLoss >= 0){
        gainLoss = "Net Gain: ";
        netGainLossValueElement.style.color = "#1fce97";
    }
    else{
        gainLoss = "Net Loss: "
        netGainLossValueElement.style.color = "red";
    }

    portfolioValueElement.innerHTML = "Portfolio Value: $" + portfolioValue.toLocaleString();
    netGainLossElement.innerHTML = gainLoss;
    netGainLossValueElement.innerHTML = "$" + netGainLoss.toLocaleString();



}
getEndValue();