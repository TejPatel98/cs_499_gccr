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
