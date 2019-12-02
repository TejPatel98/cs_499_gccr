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
    date = moment.utc(new Date(date)).format('MMMM Do, YYYY');
    document.getElementById('tableDate').innerHTML = date;
    document.getElementById('tableCash').innerHTML = 'Cash Balance: $' + (Math.floor(Math.random() * (120000 - 90000 + 1) ) + 90000);

    var tbl = document.getElementById('resultTable');
    var tblBody = tbl.children[1];

    // Remove current table body and append a new blank one
    tblBody.remove();
    tbl.appendChild(document.createElement('tbody'));
    tblBody = tbl.children[1];

    
    // Insert needed data from results into a 'data' object
    var currentDay = Object.keys(results)[point];
    var data = [];
    for(let i = 0; i < results[currentDay].information.length; i++){
        data.push({});
        data[i].symbol = results[currentDay].information[i].optionId;
        data[i].purchaseDate = moment.utc(new Date(results[currentDay].information[i].purchaseDate)).format('MM/DD/YYYY');
        data[i].expireDate = moment.utc(new Date(results[currentDay].information[i].expDate)).format('MM/DD/YYYY');
        data[i].strike = "$" +(parseFloat(results[currentDay].information[i].strike).toFixed(2));
        data[i].purchasePrice = "$" +(results[currentDay].information[i].amountSpent.toFixed(2));
        data[i].numContractsOwned = results[currentDay].information[i].numberOfOptions;
        data[i].currentPrice = "$" +(results[currentDay].information[i].priceHistory[0].close_);
        data[i].currentValue = "$" +((data[i].numContractsOwned * results[currentDay].information[i].priceHistory[0].close_).toFixed(2));


    }// **************************************************************************************
    console.log(data);

    // Add data to the table
    for(let i = 0; i < results[currentDay].information.length; i++){
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
