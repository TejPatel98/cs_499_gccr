function formatData(){
    var data = [];
    let x = 0;
    for(let i = 0; i < Object.keys(results).length; i++){
        var index = Object.keys(results)[i];
        for (let j = 0; j < results[index].information.length; j++){
            data.push({});
            data[x].date = Object.keys(results)[i]; 
            data[x].portfolioValue = results[index].portfolioValue;
            data[x].cashBalance = results[index].balance;
            data[x].ticker = results[index].information[j].name;
            data[x].purchaseDate = moment.utc(new Date(results[index].information[j].purchaseDate)).format('MM/DD/YYYY');
            data[x].expire = moment.utc(new Date(results[index].information[j].expDate)).format('MM/DD/YYYY');
            data[x].strike = results[index].information[j].strike;
            data[x].purchasePrice = results[index].information[j].amountSpent;
            data[x].numberOfContracts = results[index].information[j].numberOfOptions;
            data[x].currentPrice = results[index].information[j].bid;
            data[x].currentValue = results[index].information[j].currentValue.toFixed(2);
            x += 1;
        }
    }
    console.log(data);
    return data;
}

function convertArrayOfObjectsToCSV(args) {  
    var result, ctr, keys, columnDelimiter, lineDelimiter, data;

    data = args.data || null;
    if (data == null || !data.length) {
        return null;
    }

    columnDelimiter = args.columnDelimiter || ',';
    lineDelimiter = args.lineDelimiter || '\n';

    keys = Object.keys(data[0]);

    result = '';
    result += keys.join(columnDelimiter);
    result += lineDelimiter;

    data.forEach(function(item) {
        ctr = 0;
        keys.forEach(function(key) {
            if (ctr > 0) result += columnDelimiter;

            result += item[key];
            ctr++;
        });
        result += lineDelimiter;
    });

    return result;
}

function downloadCSV(args) {  
    var data, filename, link;
    var csv = convertArrayOfObjectsToCSV({
        data: formatData()
    });
    if (csv == null) return;

    filename = args.filename || 'export.csv';

    if (!csv.match(/^data:text\/csv/i)) {
        csv = 'data:text/csv;charset=utf-8,' + csv;
    }
    data = encodeURI(csv);

    link = document.createElement('a');
    link.setAttribute('href', data);
    link.setAttribute('download', filename);
    link.click();
}



